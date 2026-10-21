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

We use [Conventional Commits] and [semantic-release] to automate release notes and changelogs, based on the rules of [@commitlint/config-conventional]. We squash a pull request into one commit by default. The PR title becomes the commit subject and the PR description the commit body, so write the title as a conventional commit. When each commit of a pull request needs its own changelog entry, we rebase it instead, and then every commit needs a valid conventional commit message.

## 5. Create a pull request

- Keep your pull requests focused on single subjects
- Please explain what you changed and why
- We will review your code and thoroughly test it before we merge your pull request

## Generated Code (API Client)

Some parts of this SDK use **generated code** from the MyParcel API specifications. A new field, carrier or option in a specification gets into the SDK by regenerating the client.

### Generated Files Location

- `src/Client/Generated/<Client>/` — **DO NOT EDIT MANUALLY**

Each client has a configuration file in `openapi/`. Its `inputSpec` key gives the specification, and its `outputDir` key gives the output directory.

### Regenerating Code

```bash
# Make sure Docker is available
docker --version

# Regenerate all API clients
composer generate:api
```

This command runs `openapitools/openapi-generator-cli` inside a Docker container with the configurations in `openapi/`, and then refreshes the Composer autoloader.

### Notes for Development

- Do not edit generated files manually. A change must come from the specification.
- To change a client with a local specification, edit the file in `openapi/specs/` and regenerate.
- Put workarounds for generator problems in the `typeMappings` of the client configuration.
- Every Monday, the workflow `.github/workflows/regenerate-api-client.yml` regenerates the clients and opens a `feat(api): update API clients` pull request.

---

[@commitlint/config-conventional]: https://github.com/conventional-changelog/commitlint

[Conventional Commits]: https://www.conventionalcommits.org/en/v1.0.0/#summary

[semantic-release]: https://github.com/semantic-release/semantic-release
