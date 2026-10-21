# Connect proof of concept

Connect a shop to MyParcel Connect in your browser, push a demo order, and watch the commands
MyParcel sends back. Use this to try the flow by hand, to reproduce a problem outside a plugin, or
to see what an incoming command looks like and why the SDK accepted or refused it.

This directory is not part of the SDK. `.gitattributes` export-ignores `/dev`, so it never reaches
a plugin's `vendor/myparcelnl/sdk/`.

> [!WARNING]
> Never run this on a host that is reachable from the internet. Every button acts on whoever
> presses it, with no login.

## Prerequisites

- Docker.
- The traefik from `myparcel-core`, which serves the page at an https URL with a real certificate.
  MyParcel redirects the merchant browser to `<shopUrl>/myparcel-connect/callback`, and
  `/connect/start` refuses a shop URL that is not https. For connecting and pushing orders the URL
  only has to work in your own browser, so you do not need a tunnel. To receive commands, MyParcel
  has to reach the command endpoint: see [Receiving commands](#receiving-commands).
- Access to the `mp-dev` AWS profile, which traefik uses to fetch the certificate.

## Run it

1. Install the dependencies, once:

   ```console
   docker compose run --rm php
   ```

2. Start traefik, if it is not already running:

   ```console
   cd <your-path>/myparcel-core/traefik
   docker compose up -d
   ```

   - Verify it at <http://localhost:8080>.

3. Start the page from the repository root:

   ```console
   docker compose --profile poc up -d connect-poc
   ```

4. Open <https://connect-poc.dev.myparcel.nl>.

5. Select **Connect**. MyParcel asks you to log in and sends you back to
   `https://connect-poc.dev.myparcel.nl/myparcel-connect/callback`, which reports the connection or
   the error.

Stop the page with `docker compose --profile poc down connect-poc`. The page reads its state from
`dev/connect-poc/state.json` on every visit, so you can stop the container, start it again, and
continue where you stopped.

### Without traefik

Run the page directly and give it any https URL that reaches your machine, such as one from ngrok
or Cloudflare Tunnel:

```console
export MYPARCEL_CONNECT_KEY=<any string, it encrypts state.json>
export MYPARCEL_CONNECT_SHOP_URL=<your https URL>
php -S 127.0.0.1:8080 -t dev/connect-poc dev/connect-poc/index.php
```

## What you can do on the page

- **Connect** starts the flow. Once connected, the same button reads **Reconnect**.
- **Refresh token** gets a new access token now, instead of waiting for the stored one to near
  expiry.
- **Disconnect** drops the token and keeps the key pair, so **Reconnect** restores the same
  connection.
- **Uninstall** removes everything, including the key pair. After this, MyParcel sees a new shop.
- **Push order** sends one order with the product title, quantity, price, and customer reference
  you filled in. Each push uses a new order id, so you can push repeatedly.

MyParcel answers an order push per item, with either an acceptance or a problem describing what was
wrong. That answer carries no order id, so the page cannot link to the order it created. Look for
the order in the backoffice.

- **Received commands** lists every request that reached the command endpoint, newest first, with
  the status the page replied, why, the headers without `Signature`, and the body. The page reloads
  itself when a new one arrives. **Clear** empties the list.

## Receiving commands

MyParcel posts a command, such as `ORDER_SHIPMENTS`, to the command endpoint the page passed to
`start()`: the shop URL plus `/myparcel/commands`, or `MYPARCEL_CONNECT_COMMAND_ENDPOINT`. The page
runs `CommandRequestVerifier` and `CommandReader` on every request to that path, keeps the outcome
in `dev/connect-poc/commands.json`, and replies with the status the SDK's exception names. A
command with a `commandId` that is among the last 20 accepted requests is listed as a duplicate and
gets a `202`; a plugin keeps those pairs for 14 days instead. `receiveCommand()` in `index.php` is
the whole route.

MyParcel has to reach that URL, which the traefik setup does not provide. Give the page a public
https URL with a tunnel such as ngrok or Cloudflare Tunnel, set it as `MYPARCEL_CONNECT_SHOP_URL`
(see [Without traefik](#without-traefik)), and connect again so MyParcel learns the command
endpoint. Then ship an order of the connected shop in the backoffice and reload the page.

> [!WARNING]
> With a tunnel, every button on the page is reachable from the internet too. Keep the tunnel open
> only while you test, and uninstall when you are done.

To see the checks fail without a tunnel, post anything to the endpoint yourself:

```console
curl -i -X POST http://127.0.0.1:8080/myparcel/commands -H 'Content-Type: application/json' -d '{}'
```

The page replies `401` and lists the request with the check that refused it. A request from
MyParcel to a shop that is not connected is refused the same way: there is no stored connection
to check it against.

## Settings

`dev/connect-poc/.env.example` lists every setting with its default and what it does. Copy it and
change what you need:

```console
cp dev/connect-poc/.env.example dev/connect-poc/.env
```

A real environment variable wins over that file, so the compose service works without one. The
settings block at the top of `index.php` is the only place the page reads them.

One setting is read by docker compose rather than the page: `CONNECT_POC_HOST`, the host traefik
serves on, which also becomes the shop URL. Compose reads the `.env` next to `docker-compose.yml`,
so that one belongs in the repository root.

## If something fails

The page prints what MyParcel answered: the error code, the HTTP status, and the response body.
These failures come from the setup rather than the API:

- **"Could not decrypt the stored connect state"** means `MYPARCEL_CONNECT_KEY` is not the key that
  wrote `state.json`. Restore that key, or delete `dev/connect-poc/state.json` and connect again.
- **A command refused with "This shop has no command endpoint"** means the shop connected before
  the endpoint was configured. Reconnect; the Connection section says what MyParcel has.
- **Commands keep arriving on the old path** after you changed `MYPARCEL_CONNECT_COMMAND_ENDPOINT`:
  MyParcel posts to the endpoint it got at connect time, and the SDK checks against that stored one,
  so they still verify. The page receives both paths. Reconnect to make the change take effect.
- **A callback that is refused** means the query MyParcel sent does not match the flow this shop
  began. Select **Connect** and complete the flow in one go, without reusing an old callback URL.
- **A traefik 404** means traefik has not registered the route yet, or it is not running. Check
  `connect-poc` in the router list at <http://localhost:8080>.
