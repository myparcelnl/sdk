# Contributing

## Basics

- Fork this repository and clone it to your machine

## Prerequisites
You need to have the following installed on your machine:
- Docker
- Node, version 24.21 or higher
- PNPM, version 12 or higher

## Make your changes

- You must conform to our code style
- You should make only one change in each branch

## Add or update tests

- Coverage % needs to be equal to or greater than that of the previous commit, and always aim for 100%.
- When adding tests, keep the same namespace as source files.
    - If you have added a file at `\MyParcelNL\Sdk\Model\MyNamespace`, with a method named `myMethod`, you would create a test for this function in `\MyParcelNL\Sdk\Test\Model\MyNamespace` and call the method `testMyMethod`. If you're using a dataProvider it should be called `provideTestMyMethodData`.

### Running PHPUnit

Run tests with the following command:

```bash
docker compose run --rm php composer test
```

Create an HTML coverage report:

```bash
docker compose run --rm test
```

> You can use this to make sure your new or updated code has 100% coverage.

The tests run on PHP 8.3 by default. Set `PHP_VERSION` to run them on another supported version:

```bash
PHP_VERSION=8.5 docker compose run --rm php composer test
```

Enable XDebug:

```bash
docker compose run --rm -it php php -dxdebug.mode=debug vendor/bin/phpunit
```

### Live smoke tests

The tests in the `live` group send requests to a real API, in the account of the API key. They
create concept shipments, multi-collo shipments and return shipments, request labels, subscribe to
webhooks and save a fulfilment order. So they run only when a developer asks for them, on a local
machine and on a test host:

```bash
MYPARCEL_LIVE_TESTS=true \
MYPARCEL_API_BASE_URL=https://api.acceptance.myparcel.nl \
API_KEY=your-acceptance-api-key \
./vendor/bin/phpunit --group live
```

- This command runs on your machine, not in the `php` container, so it needs a local PHP 8.3 or
  higher. The `php` service does not pass these variables into the container.
- `phpunit.xml` leaves the `live` group out of a run without `--group live`.
- Only `MYPARCEL_LIVE_TESTS=true` or `MYPARCEL_LIVE_TESTS=1` turns the tests on. Without it, or
  with another value such as `false`, the tests are skipped, also when an API key is set.
- `MYPARCEL_API_BASE_URL` is required. The tests run only for an `https` URL on a host that ends
  with `.acceptance.myparcel.nl` or `.testing.myparcel.nl`. Every other value skips them, such as
  the production host or a host without a scheme.
- The tests are skipped when `CI` is set, so they never run in a pipeline.

## 4. Commit

We use [Conventional Commits] and [semantic-release] to automate release notes and changelogs, based on the rules of [@commitlint/config-conventional]. We squash a pull request into one commit by default. The PR title becomes the commit subject and the PR description the commit body, so write the title as a conventional commit. When each commit of a pull request needs its own changelog entry, we rebase it instead, and then every commit needs a valid conventional commit message.

## 5. Create a pull request

- Keep your pull requests focused on single subjects
- Please explain what you changed and why
- We will review your code and thoroughly test it before we merge your pull request

## Generated Code (API Client)

Some parts of this SDK use **generated code** from the Core API specification to ensure zero-maintenance updates for new carriers, delivery types, and configuration options.

When calling methods from these generated clients, always use named arguments. Positional arguments could move on updates to the specification, breaking your code.

### Generated Files Location
 **DO NOT EDIT MANUALLY**
- `src/Client/Generated/Jane/`: the clients that [Jane](https://github.com/janephp/janephp) generates. Each API gets its own namespace, such as `MyParcelNL\Sdk\Client\Generated\Jane\CoreApi`.
- `src/Client/Generated/EcommerceApi/`: the legacy e-commerce client that OpenAPI Generator generates. INT-2034 replaces it with the Jane `EcommerceApi`.

Jane generates the following APIs:

- CoreApi: The main API for interacting with shipments, capabilities, and notifications.
- CoreApiPrivate: Temporary API for private endpoints, not intended for public use or not yet documented in our public open api spec.
- EcommerceApi: Also called "Order v2". Manage e-commerce orders, and connect via oauth MyParcel Connect.
- IamApi: Identify and access management via the iam microservice.
- OrderApi: To be phased out. API is not actually consumed - only some types are used in our plugins, but these should move to the new EcommerceApi.

The configuration is in these files:

- `.jane-openapi`: the Jane `EcommerceApi`.
- `.jane-openapi-core`: the Jane `CoreApi`, `CoreApiPrivate`, `IamApi` and `OrderApi`.
- `openapi/ecommerce.yaml`, `openapi/common.yaml`, `openapi/commonProperties.yaml` and `openapi/templates/`: the legacy e-commerce client.

### Regenerating Code

```bash
# Make sure Docker is available
docker --version

# Regenerate the API clients
composer generate:api
```

This command runs Jane for the Jane clients and OpenAPI Generator for the legacy e-commerce client. Then it refreshes the Composer autoloader. The `Regenerate API Client` workflow runs the same generators every Monday and opens a pull request with the changes.

### Notes for Development

- Do not edit any files under `src/Client/Generated/` manually
- All changes must originate from the openapi specifications
- Put code that the generated clients need, such as a fix for a generator bug, in `src/Client/Jane/`. Give each workaround a `@todo` that says when it can go.

---

[@commitlint/config-conventional]: https://github.com/conventional-changelog/commitlint

[Conventional Commits]: https://www.conventionalcommits.org/en/v1.0.0/#summary

[semantic-release]: https://github.com/semantic-release/semantic-release
