# Connect proof of concept

Connect a shop to MyParcel Connect in your browser, then push a demo order. Use this to try the
flow by hand, or to reproduce a problem outside a plugin.

This directory is not part of the SDK. `.gitattributes` export-ignores `/dev`, so it never reaches
a plugin's `vendor/myparcelnl/sdk/`.

> [!WARNING]
> Never run this on a host that is reachable from the internet. Every button acts on whoever
> presses it, with no login.

## Prerequisites

- Docker.
- The traefik from `myparcel-core`, which serves the page at an https URL with a real certificate.
  MyParcel redirects the merchant browser to `<shopUrl>/myparcel-connect/callback`, and
  `/connect/start` refuses a shop URL that is not https. Nothing at MyParcel calls your machine, so
  the URL only has to work in your own browser. You do not need a tunnel.
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
- **A callback that is refused** means the query MyParcel sent does not match the flow this shop
  began. Select **Connect** and complete the flow in one go, without reusing an old callback URL.
- **A traefik 404** means traefik has not registered the route yet, or it is not running. Check
  `connect-poc` in the router list at <http://localhost:8080>.
