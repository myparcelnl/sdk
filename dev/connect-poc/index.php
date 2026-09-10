<?php

declare(strict_types=1);

/**
 * A worked example of connecting a shop to MyParcel Connect and pushing an order.
 *
 * Read this to see what your own integration has to do. Every call into the SDK is in this file, so
 * you can follow the whole flow from top to bottom. See README.md for how to run it.
 *
 * Connect is OAuth 2.0 with DPoP. What that means for you:
 *
 * - The merchant gets sent to MyParcel in a browser, logs in there, and comes back to a callback URL
 *   on your own shop. You never see or store their password.
 * - The shop gets its own key pair, made by the SDK on the first call. Every request is signed with
 *   the private key, so a stolen access token is useless without it.
 * - The token that comes back is short lived. The SDK refreshes it for you when it is near expiry.
 *
 * Your integration needs four things, and this file shows each one:
 *
 * 1. A ConnectConfig: which platform you are, and a key to encrypt what you store. Made below.
 * 2. A place to keep three records, as a ConnectStorageInterface. See FileConnectStorage.php.
 * 3. A route that starts the flow, from a button in your settings screen. See act().
 * 4. A route at your shop URL plus ConnectService::CALLBACK_PATH that finishes it. See
 *    receiveCallback().
 *
 * Once connected, EcommerceApiFactory gives you an API client that signs every call. See pushOrder().
 *
 * The file is in two halves. Everything down to connectExceptionFacts() is what your integration
 * does. Everything after that draws this page and is none of your concern.
 *
 * This file is not part of the SDK, and it is not a template to copy. It keeps state in a JSON file
 * and has no login, so never put it on a host that is reachable from the internet.
 */

namespace MyParcelNL\Sdk\Dev\ConnectPoc;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/FileConnectStorage.php';

/**
 * The API takes money as whole micros, so one euro is a million of them.
 */
const MICROS_PER_EURO = 1000000;

/**
 * Everything you configure, in one place. Nothing further down this file reads the environment.
 *
 * The first two you must supply. The rest have working defaults. `.env.example` lists all of them,
 * and env() below reads `.env` next to this file if you make one.
 */
$settings = [
    // The shop's own https base URL, with no query and no fragment. MyParcel returns the merchant's
    // browser to this URL plus ConnectService::CALLBACK_PATH, so it has to be a URL that works in
    // their browser.
    //
    // Take this from configuration your platform owns, such as home_url() in WordPress or the shop
    // domain in PrestaShop. Never build it from the request: not from $_SERVER['HTTP_HOST'], not
    // from a query parameter, not from a field a user can edit. This is the address MyParcel sends
    // the merchant back to carrying the one time code, so whoever controls it receives that code.
    // The SDK checks the shape of this URL, that it parses, is https, and has no query or fragment,
    // but it cannot know which host is legitimately yours.
    'shopUrl' => env('MYPARCEL_CONNECT_SHOP_URL'),

    // Encrypts the private key and the access token before they reach your storage. Keep it out of
    // the database, because a key stored next to the data it protects protects nothing. In a plugin,
    // read it from wp-config.php, from parameters.php, or from an environment variable.
    //
    // The fallback exists so this page starts with no configuration at all. Never ship a default
    // like it: a key everybody knows encrypts nothing.
    'encryptionKey' => env('MYPARCEL_CONNECT_KEY', 'connect-poc-local-only'),

    // Which platform you are. It decides which e-commerce service the SDK calls.
    // See ConnectPlatform for the accepted values.
    'platform' => env(
        'MYPARCEL_CONNECT_PLATFORM',
        \MyParcelNL\Sdk\Model\Connect\ConnectPlatform::getAllowableEnumValues()[0]
    ),

    // Acceptance is MyParcel's test environment. Build against it, and switch when you go live.
    'acceptance' => 'production' !== env('MYPARCEL_CONNECT_ENV', 'acceptance'),

    // The shop title the merchant sees at MyParcel while they connect, 1 to 64 characters. In a
    // plugin, use the shop name from the platform's own settings.
    'shopName' => env('MYPARCEL_CONNECT_SHOP_NAME', 'Connect PoC'),

    // Only used by this page, to link the backoffice. Not an SDK setting.
    'backofficeUrl' => env('MYPARCEL_BACKOFFICE_URL'),
];

if ('' === $settings['shopUrl'] || '' === $settings['encryptionKey']) {
    exit("Set MYPARCEL_CONNECT_SHOP_URL and MYPARCEL_CONNECT_KEY first. See README.md.\n");
}

// Only for the message this page shows after a redirect. The SDK never uses the session, because it
// also refreshes tokens in cron jobs and webhook handlers where there is no session.
session_start();

// Three objects, built in this order. Build them wherever you need Connect: a settings screen, a
// cron job, an order hook. They open no connection and cache nothing between requests, so building
// them again on every request costs nothing and is what a plugin normally does.

// 1. Your storage. This is the only part you write yourself. The SDK calls it to read and write the
//    shop's connect state, and never keeps that state anywhere else. See FileConnectStorage.php.
$storage = new FileConnectStorage(__DIR__ . '/state.json');

// 2. The configuration. Only the platform and the encryption key are required.
$config = new \MyParcelNL\Sdk\Model\Connect\ConnectConfig($settings['platform'], $settings['encryptionKey']);

// Each with* method returns a copy, so a config can be shared without anything changing it later.
// withAcceptance() is the only one this page uses. The other two are here so you know they exist:
//
//     $config = $config->withExpiryLeewaySeconds(30);
//         How early to refresh the access token, in seconds. The default of 30 keeps a request that
//         is already on its way from expiring halfway through.
//
//     $config = $config->withScopes([ConnectScope::WRITE_ORDERS]);
//         Ask for fewer permissions than the default of all of them. Ask for what you use: a
//         merchant sees this list on the consent screen.
$config = $config->withAcceptance($settings['acceptance']);

// 3. The service. Everything Connect does goes through it, and it needs nothing else.
$connect = new \MyParcelNL\Sdk\Services\Connect\ConnectService($config, $storage);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// A hand written router, because this page has no framework. Your app needs two routes:
//
// - One your own buttons post to, wherever you like. Here that is every POST.
// - One on ConnectService::CALLBACK_PATH, which MyParcel redirects the merchant's browser to. That
//   path is fixed by MyParcel, so this is the one route you do not get to name.
//
// Actions are POSTs that redirect afterwards, so a browser refresh never repeats them. The callback
// is a GET, because a browser redirect is always a GET.
if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? 'GET')) {
    act($connect, $settings, (string) ($_POST['action'] ?? ''));
    exit;
}

if (\MyParcelNL\Sdk\Services\Connect\ConnectService::CALLBACK_PATH === $path) {
    receiveCallback($connect);
    exit;
}

render($connect, $storage, $config, $settings);

/*
 * ---------------------------------------------------------------------------------------------
 * What your integration has to do. These six functions are the whole of it, in the order they
 * happen: connect a shop, take the callback, call the API, read the answer, read a failure.
 * ---------------------------------------------------------------------------------------------
 */

/**
 * Every action Connect offers, one per case. This is the whole API you drive from your own screens.
 *
 * Put all of it behind your platform's own permission check, the way you would any other setting
 * that belongs to a shop administrator: current_user_can('manage_options') in WordPress, an
 * employee token in PrestaShop, whatever your platform uses. This page has no such check, which is
 * one reason it must not be reachable from the internet.
 *
 * That is not a formality. An unprotected uninstall destroys the shop's key pair, and an
 * unprotected connect is worse: whoever reaches it can start a flow, log in with their own MyParcel
 * account, and leave the shop connected to that account. The nonce does not stop that, because it
 * proves a flow was started for this shop and not who started it.
 *
 * @param array<string, mixed> $settings The configuration block at the top of this file.
 */
function act(\MyParcelNL\Sdk\Services\Connect\ConnectService $connect, array $settings, string $action): void
{
    try {
        switch ($action) {
            case 'connect':
                // Begin. start() makes and stores the key pair on the first call, and a nonce that
                // receiveCallback() checks the callback against, then returns the URL to send the
                // merchant to. Send them with a real browser navigation, not an AJAX call or an
                // iframe: MyParcel sets a cookie that its own callback checks.
                //
                // The shop name is the merchant's own shop title, shown to them at MyParcel. Pass a
                // plugin version as the third argument if you have one.
                redirect($connect->start($settings['shopName'], $settings['shopUrl']));

                return;

            case 'refresh':
                // Swap the key for a new access token. You rarely call this yourself: every signed
                // request refreshes the token when it is near expiry. It is here to show the call.
                $state = $connect->refresh();
                flash('ok', 'Token refreshed', ['valid until' => moment($state->getAccessTokenExpiresAt())]);
                break;

            case 'disconnect':
                // Give up the token, keep the key pair and the connection id. Use this when the
                // merchant turns the connection off, so connecting again restores the same
                // connection instead of creating a second one.
                $connect->disconnect();
                flash('ok', 'Disconnected', [
                    'what is left' => 'the key pair and the connection id, so Connect reconnects the same shop',
                ]);
                break;

            case 'uninstall':
                // Delete everything, key pair included. Call this when your plugin is uninstalled.
                // The shop loses its identity at MyParcel, so a shop that comes back is a new shop.
                $connect->uninstall();
                flash('ok', 'Uninstalled', [
                    'what is left' => 'nothing, this shop is a new shop to MyParcel now',
                ]);
                break;

            case 'push-order':
                // The only action that calls the e-commerce API rather than Connect itself.
                pushOrder($connect);
                break;

            default:
                flash('error', 'Unknown action', []);
        }
    } catch (\MyParcelNL\Sdk\Exception\ConnectException $exception) {
        // Everything the SDK refuses arrives as a ConnectException, whether it failed here or at
        // MyParcel. getErrorCode(), getStatusCode() and getResponseBody() say which.
        flash('error', 'ConnectException: ' . $exception->getMessage(), connectExceptionFacts($exception));
    } catch (\Throwable $exception) {
        flash('error', get_class($exception) . ': ' . $exception->getMessage(), [
            'thrown at' => $exception->getFile() . ':' . $exception->getLine(),
        ]);
    }

    redirect('/');
}

/**
 * Finish the flow: turn the request MyParcel sends the merchant back with into a stored token.
 *
 * Where this request comes from, start to finish:
 *
 * 1. Your button calls start(), which stores a nonce and returns a MyParcel URL. You redirect the
 *    browser to it.
 * 2. The merchant logs in at MyParcel and approves the scopes you asked for.
 * 3. MyParcel redirects their browser to your shop URL plus ConnectService::CALLBACK_PATH, which is
 *    how this function gets called. It is an ordinary GET from the merchant's own browser, so route
 *    it like any other page. Nothing calls your server directly.
 * 4. The query string carries a one time code, the nonce from step 1, and the htm and htu that your
 *    later requests have to be signed against.
 *
 * Pass the whole query and let handleCallback() judge it. It trades the code for an access token
 * and stores everything through your storage. After this the shop is connected.
 *
 * For this route you do not need a state parameter, a CSRF token, or a session check of your own.
 * That is what the nonce is, and the SDK owns both ends of it:
 *
 * - start() generates 32 random bytes and saves them through your storage, in the nonces record.
 * - handleCallback() compares the returned nonce against the stored one before it does anything
 *   else, using a constant time comparison. A callback with the wrong nonce, with no nonce, or for
 *   a flow this shop never began is refused, and refusing leaves the running flow untouched, so a
 *   forged callback cannot cancel a merchant who is still logging in.
 * - A matching nonce is thrown away immediately, before the code is redeemed. So the same callback
 *   URL cannot be used twice, even if the merchant reloads it or someone copies it out of a log.
 * - It expires after an hour, which is how long MyParcel keeps its own side of the flow alive.
 *
 * The nonce is the only thing that ties this request to the flow you started, so the one thing you
 * must get right is storing it somewhere a later request can read. A PHP session is the wrong
 * place: see ConnectStorageInterface::saveNonces().
 *
 * What the nonce does not do is prove who started the flow, only that this shop started one. That
 * is why every action needs your own permission check. See the docblock on act().
 *
 * It returns the new state, or throws. A merchant who cancels the login arrives here too, as a
 * ConnectException, because MyParcel reports that in the same query string.
 */
function receiveCallback(\MyParcelNL\Sdk\Services\Connect\ConnectService $connect): void
{
    try {
        $state = $connect->handleCallback($_GET);

        flash('ok', 'Connected', [
            'connection id' => $state->getConnectionId(),
            'scope'         => $state->getScope(),
            'token valid until' => moment($state->getAccessTokenExpiresAt()),
        ]);
    } catch (\MyParcelNL\Sdk\Exception\ConnectException $exception) {
        // The exception message already says a callback was refused, so it is the whole title.
        flash('error', $exception->getMessage(), connectExceptionFacts($exception));
    } catch (\Throwable $exception) {
        flash('error', get_class($exception) . ': ' . $exception->getMessage(), [
            'thrown at' => $exception->getFile() . ':' . $exception->getLine(),
        ]);
    }

    redirect('/');
}

/**
 * Call the e-commerce API once the shop is connected.
 *
 * EcommerceApiFactory::make() hands you the generated API client with the signing middleware
 * already on it. From there you call the endpoint and the SDK does the rest: it reads the stored
 * token, refreshes it when it is near expiry, signs the request with the shop's private key, and
 * retries once if MyParcel asks for a fresh nonce.
 *
 * Building the order below is ordinary use of the generated models. The only thing worth copying is
 * the listInvalidProperties() check: the models carry the API's own rules, so you can catch a bad
 * field before you spend a request on it.
 */
function pushOrder(\MyParcelNL\Sdk\Services\Connect\ConnectService $connect): void
{
    $form = [
        'productName' => trim((string) ($_POST['productName'] ?? '')),
        'quantity'    => max(1, (int) ($_POST['quantity'] ?? 1)),
        'priceEuros'  => (string) ($_POST['priceEuros'] ?? '0'),
        'reference'   => trim((string) ($_POST['reference'] ?? '')),
    ];

    // Kept so the form comes back filled in the way the tester left it.
    $_SESSION['form'] = $form;

    // Your own id for this order. MyParcel stores it, so send the id the order has in your shop and
    // you can recognise it later. A random one here means every press is a new order.
    // In reality, this should be the order ID in your webshop, eg. the Woocommerce Order ID etc.
    $sourceId = 'poc-' . bin2hex(random_bytes(4));

    $order = new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Order([
        'ordered_at'         => new \DateTime('now'),
        'source_id'          => $sourceId,
        'customer_reference' => '' === $form['reference'] ? null : $form['reference'],
        'lines'              => [
            new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\OrderLine([
                'source_id' => $sourceId . '-1',
                'quantity'  => $form['quantity'],
                'product'   => new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Product([
                    'name'      => $form['productName'],
                    'source_id' => 'poc-product-1',
                ]),
                'price' => new \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\Money([
                    'currency' => 'EUR',
                    'micros'   => (int) round(((float) str_replace(',', '.', $form['priceEuros'])) * MICROS_PER_EURO),
                ]),
            ]),
        ],
    ]);

    // The generated model checks the spec's own bounds, so a bad field is caught before the call.
    $problems = $order->listInvalidProperties();

    if ([] !== $problems) {
        flash('error', 'The order does not match the spec', ['rejected because' => implode("\n", $problems)]);

        return;
    }

    // One call. Up to 50 orders per request, and MyParcel answers per order rather than once for
    // the batch, so read every item: some can be accepted while others are refused.
    $results = \MyParcelNL\Sdk\Services\Ecommerce\EcommerceApiFactory::make($connect)
        ->webhookOrdersPost([$order]);

    $ok = accepted($results);

    flash($ok ? 'ok' : 'error', $ok ? 'Order accepted' : 'Order refused', [
        'sourceId'    => $sourceId,
        'sent'        => json_encode($order->jsonSerialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        'per item'    => describeResults($results),
    ]);
}

/**
 * Read whether every item in the answer came back accepted.
 *
 * @param mixed $results
 */
function accepted($results): bool
{
    if (!is_array($results) || [] === $results) {
        return false;
    }

    foreach ($results as $result) {
        if (!is_object($result) || 202 !== (int) $result->getStatus()) {
            return false;
        }
    }

    return true;
}

/**
 * Say what happened per order, because /webhook/orders answers per item.
 *
 * @param  mixed $results
 * @return string
 */
function describeResults($results): string
{
    if (!is_array($results)) {
        // A 400 or a 404 answers with one problem document instead of a list.
        return is_object($results) ? trim(sprintf(
            "%s %s\n%s",
            (string) $results->getStatus(),
            (string) $results->getTitle(),
            (string) $results->getDetail()
        )) : (string) json_encode($results);
    }

    $lines = [];

    foreach ($results as $index => $result) {
        $status = (int) $result->getStatus();

        if (202 === $status) {
            $lines[] = sprintf('%d: 202 accepted', $index);

            continue;
        }

        $lines[] = trim(sprintf(
            '%d: %d %s %s',
            $index,
            $status,
            (string) $result->getTitle(),
            (string) $result->getDetail()
        ));

        foreach ((array) $result->getErrors() as $error) {
            $lines[] = sprintf('    %s %s', (string) $error->getPointer(), (string) $error->getDetail());
        }
    }

    return implode("\n", $lines);
}

/**
 * Pull out everything a ConnectException knows, so a failure is debuggable from the page.
 *
 * @return array<string, mixed>
 */
function connectExceptionFacts(\MyParcelNL\Sdk\Exception\ConnectException $exception): array
{
    return [
        'wire error code' => $exception->getErrorCode(),
        'http status'     => $exception->getStatusCode(),
        'response body'   => $exception->getResponseBody(),
        'hint'            => false === strpos($exception->getMessage(), 'decrypt')
            ? null
            : 'MYPARCEL_CONNECT_KEY does not match the one that wrote state.json. Delete the file and start again.',
    ];
}

/*
 * ---------------------------------------------------------------------------------------------
 * Below here is this page only: HTML, buttons, styling, and reading the .env file. None of it is
 * part of using the SDK, and your app has its own way of doing all of it.
 * ---------------------------------------------------------------------------------------------
 */

/**
 * Draw the whole page.
 */
function render(
    \MyParcelNL\Sdk\Services\Connect\ConnectService $connect,
    FileConnectStorage $storage,
    \MyParcelNL\Sdk\Model\Connect\ConnectConfig $config,
    array $settings
): void {
    $connected = $connect->isConnected();
    $stored    = $storage->everything();
    $token     = $stored['token'] ?? [];
    $install   = $stored['installation'] ?? [];
    $form      = $_SESSION['form'] ?? [
        'productName' => 'Demo hoodie',
        'quantity'    => 1,
        'priceEuros'  => '24.95',
        'reference'   => 'PoC order',
    ];

    header('Content-Type: text/html; charset=utf-8');

    echo '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>MyParcel Connect PoC</title>';
    styles();

    echo '<h1>MyParcel Connect PoC</h1>';

    takeFlash();

    // Environment
    echo '<section><h2>Environment</h2>';
    facts([
        'PHP'          => PHP_VERSION,
        // Call this before you offer Connect at all. It needs ext-openssl, which is a composer
        // suggest rather than a require, so a shop can be missing it. Then your settings screen can
        // say why the feature is unavailable instead of showing a button that throws.
        'ext-openssl'  => \MyParcelNL\Sdk\Services\Connect\ConnectService::isSupported() ? 'usable' : 'cannot run Connect',
        'environment'  => $settings['acceptance'] ? 'acceptance' : 'production',
        'platform'     => $config->getPlatform(),
        'API host'     => $config->getHost(),
        'shop URL'     => $settings['shopUrl'],
        'callback URL' => rtrim($settings['shopUrl'], '/') . \MyParcelNL\Sdk\Services\Connect\ConnectService::CALLBACK_PATH,
        'scopes'       => $config->getScopeString(),
    ]);
    echo '</section>';

    // Connection
    echo '<section><h2>Connection ' . badge($connected ? 'connected' : 'not connected', $connected) . '</h2>';

    facts([
        'connection id'     => $install['connectionId'] ?? null,
        'scope granted'     => $token['scope'] ?? null,
        'token valid until' => isset($token['expiresAt']) ? moment((int) $token['expiresAt']) : null,
        'stored records'    => [] === $stored ? 'nothing stored yet' : implode(', ', array_keys($stored)),
    ]);

    if (!$connected) {
        echo '<p class="note">Press Connect. MyParcel asks you to log in and sends you back to the callback URL above.</p>';
    }

    echo '<div class="row">';
    button('connect', $connected ? 'Reconnect' : 'Connect', !$connected);
    button('refresh', 'Refresh token', false, !$connected);
    button('disconnect', 'Disconnect', false, !$connected);
    button('uninstall', 'Uninstall', false, [] === $stored);
    echo '</div>';

    echo '<p class="note">Disconnect drops the token and keeps the key pair, so Reconnect restores the same '
        . 'connection. Uninstall destroys the key pair too: after that MyParcel sees a new shop.</p>';
    echo '</section>';

    // Order push
    echo '<section><h2>Push a demo order</h2>';

    if (!$connected) {
        echo '<p class="note">Connect first. This call is signed with the access token.</p>';
    }

    echo '<form method="post" action="/">';
    echo '<input type="hidden" name="action" value="push-order">';
    field('productName', 'Product title', $form['productName'], 'text');
    field('quantity', 'Quantity', (string) $form['quantity'], 'number');
    field('priceEuros', 'Price per item in euros', (string) $form['priceEuros'], 'text');
    field('reference', 'Customer reference', (string) $form['reference'], 'text');
    echo '<button type="submit"' . ($connected ? '' : ' disabled') . '>Push order</button>';
    echo '</form>';

    echo '<p class="note">The order id is generated per push, so every press is a new order. MyParcel answers '
        . 'per item with 202 or a problem, and that answer carries no order id, so there is no link to the '
        . 'order itself. ' . backofficeLink($settings) . '</p>';
    echo '</section>';
}

/**
 * Link to the orders list, which is the closest the answer allows.
 */
function backofficeLink(array $settings): string
{
    $url = '' !== $settings['backofficeUrl'] ? $settings['backofficeUrl'] : ($settings['acceptance']
        ? 'https://backoffice.acceptance.myparcel.nl'
        : 'https://backoffice.myparcel.nl');

    return sprintf('Look for it in the <a href="%s" target="_blank" rel="noreferrer">backoffice</a>.', e($url));
}

/**
 * Keep a message for the page the redirect lands on.
 *
 * @param array<string, mixed> $facts
 */
function flash(string $level, string $title, array $facts): void
{
    $_SESSION['flash'] = ['level' => $level, 'title' => $title, 'facts' => $facts];
}

/**
 * Print the message the last action left, once.
 */
function takeFlash(): void
{
    if (!isset($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    printf('<div class="flash %s"><b>%s</b>', 'ok' === $flash['level'] ? 'ok' : 'error', e($flash['title']));
    facts($flash['facts']);
    echo '</div>';
}

/**
 * Print a definition list, skipping everything empty.
 *
 * @param array<string, mixed> $facts
 */
function facts(array $facts): void
{
    $shown = '';

    foreach ($facts as $label => $value) {
        if (null === $value || '' === $value || [] === $value) {
            continue;
        }

        $shown .= '<dt>' . e((string) $label) . '</dt><dd><pre>'
            . e(is_scalar($value) ? (string) $value : (string) json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
            . '</pre></dd>';
    }

    if ('' !== $shown) {
        echo '<dl>' . $shown . '</dl>';
    }
}

/**
 * Print one button, as its own form so it can POST on its own.
 */
function button(string $action, string $label, bool $primary = false, bool $disabled = false): void
{
    printf(
        '<form method="post" action="/"><input type="hidden" name="action" value="%s">'
            . '<button type="submit" class="%s"%s>%s</button></form>',
        e($action),
        $primary ? 'primary' : '',
        $disabled ? ' disabled' : '',
        e($label)
    );
}

/**
 * Print one labelled input.
 */
function field(string $name, string $label, string $value, string $type): void
{
    printf(
        '<label>%s<input type="%s" name="%s" value="%s"%s></label>',
        e($label),
        e($type),
        e($name),
        e($value),
        'number' === $type ? ' min="1"' : ''
    );
}

/**
 * Print a coloured state word.
 */
function badge(string $text, bool $good): string
{
    return sprintf('<span class="badge %s">%s</span>', $good ? 'ok' : 'error', e($text));
}

/**
 * Say when something expires, and whether that is still ahead of us.
 */
function moment(?int $timestamp): ?string
{
    if (null === $timestamp || 0 === $timestamp) {
        return null;
    }

    $seconds = $timestamp - time();

    return sprintf(
        '%s (%s)',
        date('Y-m-d H:i:s', $timestamp),
        $seconds > 0 ? sprintf('in %d min %d s', intdiv($seconds, 60), $seconds % 60) : 'expired'
    );
}

function redirect(string $url): void
{
    header('Location: ' . $url);
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function styles(): void
{
    echo '<style>
        :root { color-scheme: light dark; --line: #8883; --ok: #1a7f37; --bad: #cf222e; }
        body { font: 15px/1.55 system-ui, sans-serif; max-width: 46em; margin: 0 auto; padding: 2em 1em; }
        h1 { font-size: 1.4em; }
        h2 { font-size: 1.05em; margin: 0 0 .6em; }
        section { border: 1px solid var(--line); border-radius: 8px; padding: 1em 1.2em; margin: 1em 0; }
        dl { display: grid; grid-template-columns: 12em 1fr; gap: .1em .8em; margin: .4em 0; }
        dt { color: #8889; }
        dd { margin: 0; }
        pre { margin: 0; white-space: pre-wrap; word-break: break-word; font: 13px/1.5 ui-monospace, monospace; }
        .row { display: flex; flex-wrap: wrap; gap: .5em; margin: .8em 0 0; }
        .row form { margin: 0; }
        button { font: inherit; padding: .45em 1em; border: 1px solid var(--line); border-radius: 6px;
                 background: #8881; cursor: pointer; }
        button.primary { background: var(--ok); border-color: var(--ok); color: #fff; }
        button:disabled { opacity: .45; cursor: not-allowed; }
        label { display: block; margin: .5em 0; }
        input { display: block; width: 100%; box-sizing: border-box; font: inherit; padding: .4em .6em;
                border: 1px solid var(--line); border-radius: 6px; background: #8881; color: inherit; }
        .flash { border-left: 4px solid var(--line); padding: .8em 1em; border-radius: 6px; background: #8881; }
        .flash.ok { border-left-color: var(--ok); }
        .flash.error { border-left-color: var(--bad); }
        .badge { font-size: .75em; padding: .15em .6em; border-radius: 999px; vertical-align: .12em; color: #fff; }
        .badge.ok { background: var(--ok); }
        .badge.error { background: var(--bad); }
        .note { color: #8889; font-size: .9em; margin: .8em 0 0; }
    </style>';
}

/**
 * Read one setting, from the real environment or from `.env` next to this file.
 *
 * A real environment variable wins, which is what docker compose sets. Written out longhand
 * because this file is meant to be read, not reused.
 */
function env(string $name, string $default = ''): string
{
    $fromEnvironment = getenv($name);

    if (false !== $fromEnvironment && '' !== $fromEnvironment) {
        return $fromEnvironment;
    }

    $fromFile = readEnvFile();

    return isset($fromFile[$name]) && '' !== $fromFile[$name] ? $fromFile[$name] : $default;
}

/**
 * Read `.env` next to this file, if there is one. One NAME=value per line, # starts a comment.
 *
 * Your framework almost certainly does this for you. It is here so the PoC needs no dependencies.
 *
 * @return array<string, string>
 */
function readEnvFile(): array
{
    static $parsed = null;

    if (null !== $parsed) {
        return $parsed;
    }

    $parsed = [];
    $path   = __DIR__ . '/.env';

    if (!is_file($path)) {
        return $parsed;
    }

    foreach ((array) file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim((string) $line);

        if ('' === $line || 0 === strpos($line, '#') || false === strpos($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);

        $parsed[trim($name)] = trim(trim($value), '"\'');
    }

    return $parsed;
}
