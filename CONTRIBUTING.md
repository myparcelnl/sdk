# Contributing

## 1. Basics

- Fork this repository and clone it to your machine
- Login to `ghcr.io` with your GitHub credentials to be able to build our image:
  ```bash
  docker login ghcr.io
  ```
- Run the following to set up our Docker image for the first time:
  ```bash
  docker compose build
  ```
    - It contains PHP 7.2 (configurable via arg `PHP_VERSION`), Composer 2 and
      XDebug 3.
- Install composer dependencies:
  ```bash
  docker compose run php composer install
  ```

## 2. Make your changes

- Try to conform to our code style
- You should make only one change in each branch

## 3. Add or update tests

- Coverage % needs to be equal to or greater than that of the previous commit.
- When adding tests, keep the same namespace as source files.
    - If you have added a file at `\MyParcelNL\Sdk\Model\MyNamespace`, with a
      method named `myMethod`, you would create
      a test for this function in `\MyParcelNL\Sdk\Test\Model\MyNamespace` and
      call the method `testMyMethod`. If you're using a dataProvider it should
      be called `provideTestMyMethodData`.

### Running PHPUnit

If you haven't done so, prepare an `.env` file:

```bash
cp .env.template .env
```

```dotenv
# .env
API_KEY_NL="<MyParcelNL API key>"
API_KEY_BE="<MyParcelBE API key>"
```

Run tests with the following command:

```bash
docker compose run --rm -it php vendor/bin/phpunit
```

Create a HTML coverage report:

```bash
docker compose run --rm -it php vendor/bin/phpunit --coverage-html coverage
```

> You can use this to make sure your new or updated code has 100% coverage.

Enable XDebug:

```bash
docker compose run --rm -it php php -dxdebug.mode=debug vendor/bin/phpunit
```

## 4. Commit

Make as many commits as you'd like. We use [Conventional Commits]
and [semantic-release] to simplify the process of
releasing updates by automating release notes and changelogs based on the rules
of [@commitlint/config-conventional].
Your branch will be squashed into one single valid commit.

## 5. Create a pull request

- Keep your pull requests focused on single subjects
- Please explain what you changed and why
- We will review your code and thoroughly test it before squashing and merging
  your pull request

[@commitlint/config-conventional]: https://github.com/conventional-changelog/commitlint

[Conventional Commits]: https://www.conventionalcommits.org/en/v1.0.0/#summary

[semantic-release]: https://github.com/semantic-release/semantic-release
