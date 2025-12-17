# Contributing

## 1. Basics

- Fork this repository and clone it to your machine

## 2. Make your changes

- Try to conform to our code style
- You should make only one change in each branch

## 3. Add or update tests

- Coverage % needs to be equal to or greater than that of the previous commit.
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

Enable XDebug:

```bash
docker compose run --rm -it php php -dxdebug.mode=debug vendor/bin/phpunit
```

## 4. Commit

Make as many commits as you'd like. We use [Conventional Commits] and [semantic-release] to simplify the process of releasing updates by automating release notes and changelogs based on the rules of [@commitlint/config-conventional]. Your branch will be squashed into one single valid commit.

## 5. Create a pull request

- Keep your pull requests focused on single subjects
- Please explain what you changed and why
- We will review your code and thoroughly test it before squashing and merging your pull request

## Generated Code (Capabilities API Client)

Some parts of this SDK use **generated code** from the Core API specification to ensure zero-maintenance updates for new carriers, delivery types, and configuration options.

### Generated Files Location

- `src/Services/CoreApi/Generated/Capabilities/` — **DO NOT EDIT MANUALLY**

### Regenerating Code

```bash
# Make sure Docker is available
docker --version

# Regenerate the Capabilities client
composer generate:capabilities
```

This command runs `swaggerapi/swagger-codegen-cli-v3` inside a Docker container, using the specification at:

```
https://api.myparcel.nl/openapi.yaml
```

### Notes for Development

- Do not edit any files under `Generated/Capabilities` manually
- All changes must originate from the Core API specification
- The generated code is produced by Swagger Codegen v3, not OpenAPI Generator
- The generation command automatically refreshes autoloading via Composer

---

[@commitlint/config-conventional]: https://github.com/conventional-changelog/commitlint

[Conventional Commits]: https://www.conventionalcommits.org/en/v1.0.0/#summary

[semantic-release]: https://github.com/semantic-release/semantic-release
