MyParcel PHP SDK
===

<a href="https://github.com/myparcelnl/sdk/releases" target="_blank"><img src="https://img.shields.io/packagist/v/myparcelnl/sdk?label=Latest%20version" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/myparcelnl/sdk" target="_blank"><img src="https://img.shields.io/packagist/dm/myparcelnl/sdk" alt="Total Downloads"></a>
<a href="https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM" target="_blank"><img src="https://img.shields.io/badge/Slack-Chat%20with%20us-white?logo=slack&labelColor=4a154b" alt="Slack support"></a>
[![Coverage Status](https://img.shields.io/codecov/c/github/myparcelnl/sdk?logo=codecov)](https://codecov.io/gh/myparcelnl/sdk)


## Requirements

- PHP >=7.4
- Composer

See [installation] for further instructions.

## Documentation

For examples, guides and in-depth information, visit the [PHP SDK documentation] on the [MyParcel Developer Portal].

## Capabilities

The SDK supports capability checks based on a generic Shipment model.

- Available:
  - `CapabilitiesRequest::fromShipment(Shipment)`
  - `CapabilitiesService::fromShipment(Shipment)`
- Minimal projection (country + physical properties)
- Capabilities carrier must be set explicitly by the caller (no automatic mapping from shipment carrier). Use the generated Capabilities domain enums if needed.

## Support

For questions and support please contact us via [support@myparcel.nl](mailto:support@myparcel.nl) or chat with our
developers directly on [Slack].

## Testing

### Test Framework
This SDK uses **PHPUnit** with **Mockery** for mocking HTTP requests. All tests are completely isolated and do not make real API calls.


#### Running Tests
```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite Unit

# Run with test descriptions
./vendor/bin/phpunit --testdox
```

#### Writing Tests
All HTTP requests are automatically mocked using Mockery. When writing tests:

1. Use `$this->mockCurl()` to get a Mockery mock instance
2. Set up expectations for API calls with `shouldReceive()` 
3. Define mock responses that match real API responses
4. All existing tests serve as examples of proper mocking patterns

For detailed examples, see `test/Mock/EXAMPLE_UPDATE_TEST.md`.

## Contribute

1. Check for open issues or open a new issue to start a discussion around a bug
   or feature
2. Fork the repository and branch off from the `main` branch
3. Write one or more tests for the new feature or that expose the bug
   - **Important**: Use Mockery for mocking HTTP requests (see Testing section above)
   - Follow existing test patterns in the codebase
4. Make code changes to implement the feature or fix the bug
5. Ensure all tests pass: `./vendor/bin/phpunit`
6. Submit a pull request to `main` to get your changes merged and published

[Slack]: https://join.slack.com/t/myparcel-dev/shared_invite/enQtNDkyNTg3NzA1MjM4LTM0Y2IzNmZlY2NkOWFlNTIyODY5YjFmNGQyYzZjYmQzMzliNDBjYzBkOGMwYzA0ZDYzNmM1NzAzNDY1ZjEzOTM
[Installation]:https://developer.myparcel.nl/documentation/50.php-sdk.html#installation
[contribution guidelines]: https://developer.myparcel.nl/documentation/50.php-sdk.html#contributing
[PHP SDK documentation]: https://developer.myparcel.nl/documentation/50.php-sdk.html
[MyParcel Developer Portal]: https://developer.myparcel.nl
