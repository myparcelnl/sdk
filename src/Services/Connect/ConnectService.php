<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\ApiException;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Configuration;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectStartConfig;
use MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model\ConnectTokenPostRequest;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Crypto\AesGcmCipher;
use MyParcelNL\Sdk\Crypto\DpopKeyPair;
use MyParcelNL\Sdk\Crypto\DpopProofFactory;
use MyParcelNL\Sdk\Exception\ConnectException;
use MyParcelNL\Sdk\Model\Connect\ConnectConfig;
use MyParcelNL\Sdk\Model\Connect\ConnectErrorCode;
use MyParcelNL\Sdk\Model\Connect\ConnectState;
use MyParcelNL\Sdk\Model\Connect\ConnectTokenResponse;
use MyParcelNL\Sdk\Support\Str;
use Throwable;

/**
 * MyParcel Connect, from the shop's side.
 *
 * A shop authorizes the plugin in a browser instead of pasting an API key. What comes back is an
 * access token tied to a key pair the shop owns, so a stolen token is useless without the key.
 *
 * Three calls do the work. start() sends the merchant to MyParcel. handleCallback() takes what comes
 * back and turns it into a token. getAccessToken() hands out a usable token forever after, refreshing
 * on its own. Nothing else needs calling.
 *
 * To use it: supply a ConnectConfig and a ConnectStorageInterface, redirect the browser to what
 * start() returns, and offer one route at {shopUrl}{self::CALLBACK_PATH} that calls
 * handleCallback(). Everything else is here.
 */
final class ConnectService
{
    use HasUserAgent;

    /**
     * The path MyParcel returns the merchant to, appended to the shop URL passed to start().
     *
     * Register a route on this path and call handleCallback() from it. MyParcel builds the return
     * URL as {shopUrl}{self::CALLBACK_PATH}, so the path is not yours to choose.
     */
    public const CALLBACK_PATH = '/myparcel-connect/callback';

    /**
     * How long a started flow stays valid, in seconds.
     *
     * This is MyParcel's own session lifetime, not a number of ours. The spec documents the
     * /connect/start cookie __Host-connect_session with Max-Age 3600, and inside that the merchant
     * gets 300 seconds to reach the consent page and a fresh 300 there, so an abandoned flow can
     * still be bounced back for the rest of the hour.
     *
     * Both directions cost something. Shorter, and a callback carrying a code MyParcel still accepts
     * is refused here. Longer, and this keeps accepting a callback MyParcel will never send, because
     * its session is already gone. Matching it means the two windows close together, so there is one
     * answer to whether a flow is still alive instead of two that can disagree.
     */
    private const START_NONCE_LIFETIME = 3600;

    /**
     * Bytes of randomness in the nonce sent to /connect/start.
     */
    private const START_NONCE_LENGTH = 32;

    private const SHOP_NAME_MAX_LENGTH = 64;

    private const SHOP_URL_MAX_LENGTH = 128;

    private const VERSION_MAX_LENGTH = 256;

    private const CONFIG_MAX_LENGTH = 1024;

    private const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * The response header MyParcel puts a fresh nonce in.
     */
    private const NONCE_HEADER = 'DPoP-Nonce';

    /**
     * Which of the two calls a failure came from.
     *
     * translate() needs to know, because invalid_grant means something different at each.
     */
    private const ENDPOINT_EXCHANGE = 'exchange';

    private const ENDPOINT_REFRESH = 'refresh';

    /**
     * @var \MyParcelNL\Sdk\Model\Connect\ConnectConfig
     */
    private $config;

    /**
     * @var \MyParcelNL\Sdk\Services\Connect\ConnectStateRepository
     */
    private $repository;

    /**
     * @var \MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi
     */
    private $api;

    /**
     * @var \MyParcelNL\Sdk\Crypto\DpopProofFactory
     */
    private $proofs;

    /**
     * Each argument has its own class, and the detail lives there rather than here.
     *
     * @param ConnectConfig               $config      Which service, which environment, and the key
     *                                                 that encrypts the stored secrets. See
     *                                                 {@see ConnectConfig}.
     * @param ConnectStorageInterface     $storage     Your own implementation. Each of its save
     *                                                 methods says where that record belongs and how
     *                                                 long to keep it. See
     *                                                 {@see ConnectStorageInterface}.
     * @param ClientInterface|null        $http        The HTTP client the connect calls go out on.
     *                                                 Pass one to set a proxy, a CA bundle or a
     *                                                 timeout. Leave Guzzle's http_errors on: this
     *                                                 reads a failure off the exception Guzzle
     *                                                 raises, not off a returned response.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When this PHP cannot run Connect. See
     *                                                     {@see ConnectService::isSupported()}.
     */
    public function __construct(
        ConnectConfig $config,
        ConnectStorageInterface $storage,
        ?ClientInterface $http = null
    ) {
        self::assertIsSupported();

        $this->config = $config;

        // No DpopMiddleware on the default client. /connect/token and /connect/refresh bind their
        // proofs to the htm and htu stored at the callback, not to the URL being called, so a
        // middleware that signs per request would sign the wrong thing.
        $http = $http ?? new GuzzleClient([
            'timeout' => self::DEFAULT_HTTP_TIMEOUT,
            'handler' => HandlerStack::create(),
        ]);

        $this->api = new DefaultApi($http, (new Configuration())->setHost($config->getHost()));

        $this->proofs     = new DpopProofFactory();
        $this->repository = new ConnectStateRepository($storage, new AesGcmCipher($config->getEncryptionKey()));
    }

    /**
     * Check whether this PHP installation can run Connect at all.
     *
     * Call this before offering the feature, so a settings screen can say why it is unavailable
     * instead of showing a button that throws. ext-openssl is a composer suggest rather than a
     * require, because only Connect needs it and the SDK ships to hosts without it.
     */
    public static function isSupported(): bool
    {
        return null === self::unsupportedReason();
    }

    /**
     * Begin connecting a shop. Send the browser to the URL this returns.
     *
     * It must be a top level navigation: MyParcel sets a cookie that its own callback checks, and an
     * iframe or a server side fetch loses it.
     *
     * @param  string      $shopName What the merchant sees in MyParcel. 1 to 64 characters.
     * @param  string      $shopUrl  The shop's own https base URL, no query and no fragment.
     *                               MyParcel returns the browser to
     *                               {shopUrl}{self::CALLBACK_PATH}.
     * @param  string|null $version  Your plugin's version, so MyParcel can tell which build a shop
     *                               runs. Leave it out and MyParcel records 0.0.1.
     * @return string The URL to redirect to.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function start(string $shopName, string $shopUrl, ?string $version = null): string
    {
        self::assertShopUrlAndVersion($shopUrl, $version);

        $config = new ConnectStartConfig([
            'platform'  => $this->config->getPlatform(),
            'shop_name' => self::shopNameToSend($shopName),
            'shop_url'  => $shopUrl,
            'version'   => $version,
        ]);

        $json = json_encode($config, JSON_UNESCAPED_SLASHES);

        if (false === $json) {
            // json_encode() answers false for invalid UTF-8, which any latin1 shop title produces.
            // The config parameter would then go out empty, and MyParcel would receive no shop name
            // and no shop URL.
            throw ConnectException::invalidArgument(
                'The shop name or URL is not valid UTF-8: ' . json_last_error_msg()
            );
        }

        $encoded = Str::base64UrlEncode($json);

        if (strlen($encoded) > self::CONFIG_MAX_LENGTH) {
            throw ConnectException::invalidArgument('The shop details are too long together');
        }

        // Everything above can still refuse the call. Nothing is stored until it cannot.
        $state = $this->stateWithKey();
        $nonce = Str::base64UrlEncode(random_bytes(self::START_NONCE_LENGTH));

        $this->repository->saveStartNonce(
            $state->withStartNonce($nonce, time() + self::START_NONCE_LIFETIME)
        );

        // The path, the parameter names and the query encoding all come from the spec. It cannot
        // refuse anything: the bounds it checks are the ones checked above.
        return (string) $this->api->connectStartGetRequest(
            $this->keyPair($state)->getThumbprint(),
            $encoded,
            $nonce,
            $this->config->getScopeString()
        )->getUri();
    }

    /**
     * Finish connecting a shop: check the callback, redeem its code, and store the token.
     *
     * Call this from the route at {shopUrl}{self::CALLBACK_PATH} and pass it the query
     * parameters. It exchanges the code for a token, which is only valid for 60 seconds, so do it in
     * the same request rather than later.
     *
     * @param  array<string, mixed> $query The callback query parameters.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the callback does not belong to a flow
     *                                                   this shop began, or MyParcel refused.
     */
    public function handleCallback(array $query): ConnectState
    {
        $state = $this->repository->loadOrFail();

        $this->assertNonceMatches($state, $query);

        // Burned before anything else can fail, so a replayed callback cannot start a second
        // exchange. The DPoP nonce survives, because the exchange below needs it.
        $this->repository->burnStartNonce();

        // Some frameworks hand over every declared parameter, so an empty error can arrive next to a
        // perfectly good code. That is a success, not a failure.
        if ('' !== self::text($query, 'error')) {
            throw ConnectException::fromCallbackError(self::text($query, 'error'));
        }

        foreach (['code', 'htm', 'htu'] as $required) {
            if ('' === self::text($query, $required)) {
                throw ConnectException::invalidCallback(sprintf('it carried no %s', $required));
            }
        }

        $htm  = self::text($query, 'htm');
        $htu  = self::text($query, 'htu');
        $code = self::text($query, 'code');

        // Checked before it is stored. htu with a query cannot be signed, and an unusable pair kept
        // on the installation would make every later refresh fail with no way back but reconnecting.
        self::assertChallengeIsUsable($htm, $htu);

        $token = $this->withNonceRetry(
            $state,
            $htm,
            $htu,
            function (string $proof) use ($code): ConnectTokenResponse {
                return $this->exchangeCodeForToken($code, $proof);
            },
            self::ENDPOINT_EXCHANGE
        );

        $this->assertTokenIsBoundToOurKey($state, $token->getAccessToken());

        $state = $state
            ->withTokenChallenge($htm, $htu)
            ->withConnectionId($token->getConnectionId() ?? $state->getConnectionId())
            ->withToken($token->getAccessToken(), $this->expiryOf($token), $token->getScope());

        $this->repository->saveInstallation($state);
        $this->repository->saveToken($state);

        return $state;
    }

    /**
     * Get an access token that is valid now. Refreshes the stored one when it is near expiry.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When the shop is not connected.
     */
    public function getAccessToken(): string
    {
        $state = $this->repository->loadOrFail();

        if (null === $state->getAccessToken() || null === $state->getTokenChallenge()) {
            throw ConnectException::notConnected();
        }

        $expiresAt = (int) $state->getAccessTokenExpiresAt();

        if (time() + $this->config->getExpiryLeewaySeconds() < $expiresAt) {
            return $state->getAccessToken();
        }

        return (string) $this->refresh()->getAccessToken();
    }

    /**
     * Swap the key for a new access token. getAccessToken() does this when it needs to, so a caller
     * only needs it to force the issue.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException When MyParcel no longer has the connection.
     */
    public function refresh(): ConnectState
    {
        $state     = $this->repository->loadOrFail();
        $challenge = $state->getTokenChallenge();

        if (null === $challenge) {
            throw ConnectException::notConnected();
        }

        $token = $this->withNonceRetry(
            $state,
            $challenge['htm'],
            $challenge['htu'],
            function (string $proof): ConnectTokenResponse {
                return $this->obtainFreshToken($proof);
            },
            self::ENDPOINT_REFRESH
        );

        // The refresh answer carries no connection id: the one from the exchange stays valid.
        $state = $state->withToken($token->getAccessToken(), $this->expiryOf($token), $token->getScope());

        $this->repository->saveToken($state);

        return $state;
    }

    /**
     * Check whether this shop has a usable connection.
     *
     * Answered from storage alone, because the contract has no endpoint that reports on a
     * connection. So this can say yes for a connection MyParcel has since revoked; that only shows
     * up as a failed refresh, which raises connectionRevoked().
     */
    public function isConnected(): bool
    {
        try {
            $state = $this->repository->load();
        } catch (ConnectException $exception) {
            return false;
        }

        return null !== $state && null !== $state->getAccessToken() && null !== $state->getTokenChallenge();
    }

    /**
     * Forget the access token and the nonces. Keeps the key pair, so the shop can reconnect.
     *
     * The key pair and the connection id stay, so a reconnect restores the same connection instead of
     * making a second one, and inbound MyParcel requests carrying the connection id can still be
     * checked. There is no revoke endpoint in the contract, so this is local only: MyParcel still
     * holds the connection until the merchant reconnects.
     */
    public function disconnect(): void
    {
        $state = $this->repository->load();

        if (null !== $state) {
            // The challenge goes too, so a scheduled refresh cannot quietly reconnect the shop. It
            // is only useful while there is a connection, and a reconnect delivers a fresh pair.
            $this->repository->saveInstallation($state->withTokenChallenge(null, null));
        }

        $this->repository->clearToken();
        $this->repository->clearNonces();
    }

    /**
     * Delete everything this shop stored, including its key pair.
     *
     * For uninstalling the plugin. A shop that comes back after this is a new shop to MyParcel.
     */
    public function uninstall(): void
    {
        $this->repository->clear();
    }

    /**
     * Read back the configuration this service was built with.
     */
    public function getConfig(): ConnectConfig
    {
        return $this->config;
    }

    /**
     * Build a DPoP proof for one outgoing API call.
     *
     * @internal DpopMiddleware calls this, so the key never has to leave this class.
     * @param  string $htm         The method of the request.
     * @param  string $htu         The URL of the request, without query or fragment.
     * @param  string $accessToken The token going out in the same request.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    public function createResourceProof(string $htm, string $htu, string $accessToken): string
    {
        $key = $this->keyPair($this->repository->loadOrFail());

        try {
            return $this->proofs->createForResourceRequest($key, $htm, $htu, $accessToken);
        } catch (ConnectException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            // The proof factory refuses an htu with a query or a fragment, and reports it as a
            // RuntimeException. Callers of this class are documented one exception type.
            throw ConnectException::invalidArgument($exception->getMessage());
        }
    }

    /**
     * Redeem the one-time code handleCallback() received for an access token.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\EcommerceApi\ApiException
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function exchangeCodeForToken(string $code, string $dpopProof): ConnectTokenResponse
    {
        [$model, , $headers] = $this->api
            ->connectTokenPostWithHttpInfo($dpopProof, new ConnectTokenPostRequest(['code' => $code]));

        return ConnectTokenResponse::fromExchange($model, self::nonceIn($headers));
    }

    /**
     * Get a fresh access token for a shop that is already connected. No body and no Authorization
     * header: holding the key is the credential.
     *
     * @throws \MyParcelNL\Sdk\Client\Generated\EcommerceApi\ApiException
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function obtainFreshToken(string $dpopProof): ConnectTokenResponse
    {
        [$model, , $headers] = $this->api->connectRefreshPostWithHttpInfo($dpopProof);

        return ConnectTokenResponse::fromRefresh($model, self::nonceIn($headers));
    }

    /**
     * The nonce MyParcel sent, if it sent one.
     *
     * @param array<string, string[]> $headers
     */
    private static function nonceIn(array $headers): ?string
    {
        // The first value, never getHeaderLine()'s ", " joined list: that string is refused as a
        // nonce every time, which would put the flow into a permanent retry loop.
        $value = $headers[self::NONCE_HEADER][0] ?? null;

        return null !== $value && '' !== $value ? $value : null;
    }

    /**
     * Send one call, and send it again once if MyParcel asks for a nonce.
     *
     * The nonce only reaches us in the answer to a call, so the first one costs a round trip. It is
     * stored afterwards, so later calls carry it and cost nothing. A second demand in a row is a
     * fault rather than something to keep retrying.
     *
     * The retry re-sends the same code: MyParcel claims a code only after the identity provider
     * accepts the call, so a nonce failure leaves it unused.
     *
     * @param  callable $send     Takes a proof, returns a ConnectTokenResponse.
     * @param  string   $endpoint ENDPOINT_EXCHANGE or ENDPOINT_REFRESH, passed on to translate() so
     *                            a failure is read the right way round.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function withNonceRetry(
        ConnectState $state,
        string $htm,
        string $htu,
        callable $send,
        string $endpoint
    ): ConnectTokenResponse {
        $key = $this->keyPair($state);

        try {
            $token = $this->send($send, $this->proofs->createForTokenEndpoint($key, $htm, $htu, $state->getDpopNonce()));
        } catch (ConnectException $exception) {
            $nonce = self::nonceFrom($exception);

            if (null === $nonce) {
                throw $this->translate($exception, $endpoint);
            }

            $this->repository->saveDpopNonce($nonce);

            try {
                $token = $this->send($send, $this->proofs->createForTokenEndpoint($key, $htm, $htu, $nonce));
            } catch (ConnectException $retryFailure) {
                if (null !== self::nonceFrom($retryFailure)) {
                    throw ConnectException::nonceRequired();
                }

                throw $this->translate($retryFailure, $endpoint);
            }
        }

        // MyParcel can rotate the nonce on a successful answer too, to save the next call a trip.
        if (null !== $token->getDpopNonce()) {
            $this->repository->saveDpopNonce($token->getDpopNonce());
        }

        return $token;
    }

    /**
     * Turn a failed call into the case that describes it.
     *
     * invalid_grant is why this needs to know which call failed. At the exchange MyParcel looks the
     * code up before it checks the proof, so it means the code is gone and the merchant starts again,
     * and any token the shop already has is still good. At the refresh it means MyParcel no longer
     * has the connection, so the token is cleared.
     *
     * @param  ConnectException $exception The failure, already off the wire and converted.
     * @param  string           $endpoint  ENDPOINT_EXCHANGE or ENDPOINT_REFRESH.
     */
    private function translate(ConnectException $exception, string $endpoint): ConnectException
    {
        switch ($exception->getErrorCode()) {
            case ConnectErrorCode::INVALID_GRANT:
                if (self::ENDPOINT_REFRESH === $endpoint) {
                    $this->repository->clearToken();

                    return ConnectException::connectionRevoked()->withHttpContextOf($exception);
                }

                return ConnectException::sessionExpired()->withHttpContextOf($exception);
            case ConnectErrorCode::INVALID_SALES_CHANNEL:
                return ConnectException::invalidSalesChannel()->withHttpContextOf($exception);
            case ConnectErrorCode::INVALID_CODE:
                return ConnectException::invalidCallback('MyParcel did not recognise the code')
                    ->withHttpContextOf($exception);
            case ConnectErrorCode::SERVER_ERROR:
                return ConnectException::serverError()->withHttpContextOf($exception);
            default:
                // invalid_dpop_proof lands here: our own proof was refused, and sending the same one
                // again cannot help. So does a problem+json body, which carries no error member.
                return $exception;
        }
    }

    /**
     * The nonce MyParcel wants, when that is what the failure was about.
     */
    private static function nonceFrom(ConnectException $exception): ?string
    {
        if (ConnectErrorCode::USE_DPOP_NONCE !== $exception->getErrorCode()) {
            return null;
        }

        // The nonce travels in a header, never in the body.
        return self::nonceIn($exception->getResponseHeaders());
    }

    /**
     * Make one call, so a generated exception never leaves this class.
     *
     * @param  callable $send Takes a proof, returns a ConnectTokenResponse.
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function send(callable $send, string $proof): ConnectTokenResponse
    {
        // Set per call, not in the constructor: a consumer registers its plugin with
        // setUserAgentForProposition() after the service exists. Left alone, the generated client
        // sends "OpenAPI-Generator/1.0.0/PHP".
        $this->api->getConfig()->setUserAgent($this->getUserAgentHeader());

        try {
            return $send($proof);
        } catch (ApiException $exception) {
            throw self::asConnectException($exception);
        }
    }

    /**
     * Turn the generated client's exception into the one type this SDK throws.
     */
    private static function asConnectException(ApiException $exception): ConnectException
    {
        $headers = $exception->getResponseHeaders();

        // The generated client leaves them null when no response arrived at all.
        if (null === $headers) {
            return ConnectException::networkFailure($exception->getMessage(), $exception);
        }

        $body = json_decode((string) $exception->getResponseBody(), true);

        // The generated exception carries the method, the URL and part of the body in its message,
        // which none of ours do, so it stays on the chain for whoever reads the log.
        return ConnectException::httpError($exception->getCode(), $headers, is_array($body) ? $body : null, $exception);
    }

    /**
     * The shop's state, with a key pair made and stored if this is the first time.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function stateWithKey(): ConnectState
    {
        $state = $this->repository->load();

        if (null !== $state) {
            return $state;
        }

        // One key pair per shop, for as long as the shop exists. A reconnect reuses it, because
        // MyParcel knows the shop by this key and a new one would register a second shop.
        $state = ConnectState::withKey(DpopKeyPair::generate()->getPrivateKeyPem());

        $this->repository->saveInstallation($state);

        return $state;
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function keyPair(ConnectState $state): DpopKeyPair
    {
        try {
            return DpopKeyPair::fromPem($state->getPrivateKeyPem());
        } catch (Throwable $exception) {
            throw ConnectException::invalidArgument('The stored key pair cannot be read');
        }
    }

    private function expiryOf(ConnectTokenResponse $token): int
    {
        return time() + $token->getExpiresIn();
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function assertNonceMatches(ConnectState $state, array $query): void
    {
        $sent   = self::text($query, 'nonce');
        $stored = $state->getStartNonce();

        if ('' === $sent) {
            throw ConnectException::invalidCallback('it carried no nonce');
        }

        if (null === $stored) {
            throw ConnectException::invalidCallback('this shop has no connect flow waiting');
        }

        // hash_equals, because the comparison decides whether a callback is accepted.
        if (!hash_equals($stored, $sent)) {
            throw ConnectException::invalidCallback('it does not match the flow this shop began');
        }

        if (time() >= (int) $state->getStartNonceExpiresAt()) {
            throw ConnectException::invalidCallback('the flow it belongs to has expired');
        }
    }

    /**
     * The token MyParcel issued must be tied to the key we hold, or every later call would fail with
     * a 401 and nothing would say why.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private function assertTokenIsBoundToOurKey(ConnectState $state, string $accessToken): void
    {
        $segments = explode('.', $accessToken);

        if (3 !== count($segments)) {
            throw ConnectException::keyMismatch();
        }

        try {
            // Read only. Verifying the signature is the resource server's job, and it needs keys we
            // do not have.
            $claims = json_decode(Str::base64UrlDecode($segments[1]), true);
        } catch (Throwable $exception) {
            throw ConnectException::keyMismatch();
        }

        $boundTo = is_array($claims) ? ($claims['cnf']['jkt'] ?? null) : null;

        if (!is_string($boundTo) || !hash_equals($this->keyPair($state)->getThumbprint(), $boundTo)) {
            throw ConnectException::keyMismatch();
        }
    }

    /**
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private static function assertIsSupported(): void
    {
        $reason = self::unsupportedReason();

        if (null !== $reason) {
            throw ConnectException::unsupportedRuntime($reason);
        }
    }

    /**
     * What stops this PHP from running Connect, if anything.
     *
     * Three checks, because "has openssl" is not enough: it can be built without the cipher the
     * state envelope uses, or without the curve the key pair needs.
     */
    private static function unsupportedReason(): ?string
    {
        if (!extension_loaded('openssl')) {
            return 'MyParcel Connect needs the openssl PHP extension';
        }

        if (!in_array('aes-256-gcm', openssl_get_cipher_methods(), true)) {
            return 'This openssl build has no aes-256-gcm, which MyParcel Connect needs';
        }

        if (!in_array('prime256v1', openssl_get_curve_names() ?: [], true)) {
            return 'This openssl build has no prime256v1 curve, which MyParcel Connect needs';
        }

        return null;
    }

    /**
     * One query parameter as text. Anything that is not a string reads as absent.
     *
     * A query string can carry an array, as in ?nonce[]=x, and casting one to a string is a PHP
     * warning that becomes an uncaught error wherever a strict error handler is installed.
     *
     * @param array<string, mixed> $query
     */
    private static function text(array $query, string $key): string
    {
        return isset($query[$key]) && is_string($query[$key]) ? $query[$key] : '';
    }

    /**
     * The htm and htu a refresh proof will be bound to, for as long as the shop stays connected.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private static function assertChallengeIsUsable(string $htm, string $htu): void
    {
        if ('' === $htm) {
            throw ConnectException::invalidCallback('it carried no htm');
        }

        $parts = parse_url($htu);

        if (false === $parts || !isset($parts['scheme'], $parts['host']) || 'https' !== $parts['scheme']) {
            throw ConnectException::invalidCallback('its htu is not an https URL');
        }

        // RFC 9449 signs htu without either, so one here could never produce a valid proof.
        if (isset($parts['query']) || isset($parts['fragment'])) {
            throw ConnectException::invalidCallback('its htu carries a query or a fragment');
        }
    }

    /**
     * Cut the shop name down to something /connect/start accepts.
     *
     * The name is the platform's own shop title, which a plugin does not choose, so a long one is
     * trimmed and a newline dropped rather than refused. A merchant should not have to rename their
     * shop to connect it.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private static function shopNameToSend(string $shopName): string
    {
        // Control characters would break the consent page MyParcel renders the name on. The /u is
        // what the generated model's own copy of this pattern is missing, and it is what makes
        // Straße and Ølsalg pass where the generated one refuses them.
        $stripped = preg_replace('/\p{C}/u', '', $shopName);

        // preg_replace answers null for invalid UTF-8, which any latin1 shop title produces. Passed
        // through, json_encode() reports it with the rest of the payload.
        $name = null === $stripped ? $shopName : mb_substr($stripped, 0, self::SHOP_NAME_MAX_LENGTH);

        if ('' === $name) {
            throw ConnectException::invalidArgument('The shop name cannot be empty');
        }

        return $name;
    }

    /**
     * The two bounds a shop cannot be trimmed into.
     *
     * A URL is not ours to alter, and a version this long is a bug in the caller rather than a
     * setting a merchant chose.
     *
     * @throws \MyParcelNL\Sdk\Exception\ConnectException
     */
    private static function assertShopUrlAndVersion(string $shopUrl, ?string $version): void
    {
        if (strlen($shopUrl) > self::SHOP_URL_MAX_LENGTH) {
            throw ConnectException::invalidArgument(
                sprintf('The shop URL cannot be longer than %d characters', self::SHOP_URL_MAX_LENGTH)
            );
        }

        if (null !== $version && ('' === $version || mb_strlen($version) > self::VERSION_MAX_LENGTH)) {
            throw ConnectException::invalidArgument(
                sprintf('The version must be 1 to %d characters', self::VERSION_MAX_LENGTH)
            );
        }

        $parts = parse_url($shopUrl);

        if (false === $parts || !isset($parts['scheme'], $parts['host']) || 'https' !== $parts['scheme']) {
            throw ConnectException::invalidArgument('The shop URL must be an https URL');
        }

        // MyParcel appends its callback path to this, so anything after the path would end up in the
        // middle of the URL it builds.
        if (isset($parts['query']) || isset($parts['fragment'])) {
            throw ConnectException::invalidArgument('The shop URL cannot carry a query or a fragment');
        }
    }
}
