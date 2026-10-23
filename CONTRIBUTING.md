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
- `src/Services/CoreApi/Generated/Jane` — The new clients/generated source, via `janephp`.
- `src/Services/CoreApi/Generated/` — Legacy v11 generated code, to be replaced with Jane in successive v12 betas.

We currently generate the following APIs, each API gets its own namespace, either in `Generated/Jane` for the new clients or in `Generated` for the legacy v11 clients.

- CoreApi: The main API for interacting with shipments, capabilities, and notifications.
- CoreApiPrivate: Temporary API for private endpoints, not intended for public use or not yet documented in our public open api spec.
- EcommerceApi: Also called "Order v2". Manage e-commerce orders, and connect via oauth MyParcel Connect.
- IamApi: Identify and access management via the iam microservice.
- OrderApi: To be phased out. API is not actually consumed - only some types are used in our plugins, but these should move to the new EcommerceApi.

### Regenerating Code

```bash
# Make sure Docker is available
docker --version

# Regenerate the API client (includes shipments, capabilities, notifications)
composer generate:api
```

This command runs the configured API generators (Jane in v12, the legacy OpenAPI Generator for v11), for all the configured APIs.

```

### Notes for Development

- Do not edit any files under `Generated/Shipments` manually
- All changes must originate from the openapi specifications
- The generated code is produced by OpenAPI Generator (supports OpenAPI 3.1) or Jane (for v12).
- The generation command automatically refreshes autoloading via Composer

---

[@commitlint/config-conventional]: https://github.com/conventional-changelog/commitlint

[Conventional Commits]: https://www.conventionalcommits.org/en/v1.0.0/#summary

[semantic-release]: https://github.com/semantic-release/semantic-release
