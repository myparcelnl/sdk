# MyParcelNL\Sdk\Client\Generated\EcommerceApi

Plugin authentication through OAuth 2.0 with DPoP, and the webhook plugins push orders to.


## Installation & Usage

### Requirements

PHP 7.4 and later.
Should also work with PHP 8.0.

### Composer

To install the bindings via [Composer](https://getcomposer.org/), add the following to `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/GIT_USER_ID/GIT_REPO_ID.git"
    }
  ],
  "require": {
    "GIT_USER_ID/GIT_REPO_ID": "*@dev"
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
<?php
require_once('/path/to/MyParcelNL\Sdk\Client\Generated\EcommerceApi/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new MyParcelNL\Sdk\Client\Generated\EcommerceApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$dpop = 'dpop_example'; // string

try {
    $result = $apiInstance->connectRefreshPost($dpop);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->connectRefreshPost: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *http://localhost*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*DefaultApi* | [**connectRefreshPost**](docs/Api/DefaultApi.md#connectrefreshpost) | **POST** /connect/refresh | Obtain a fresh access token for an established connection
*DefaultApi* | [**connectStartGet**](docs/Api/DefaultApi.md#connectstartget) | **GET** /connect/start | Start the connect flow in the merchant browser
*DefaultApi* | [**connectTokenPost**](docs/Api/DefaultApi.md#connecttokenpost) | **POST** /connect/token | Exchange a one-time code for an access token
*DefaultApi* | [**webhookOrdersPost**](docs/Api/DefaultApi.md#webhookorderspost) | **POST** /webhook/orders | Push orders from a sales channel plugin

## Models

- [Address](docs/Model/Address.md)
- [BillingDetails](docs/Model/BillingDetails.md)
- [Carrier](docs/Model/Carrier.md)
- [ConnectRefreshPost200Response](docs/Model/ConnectRefreshPost200Response.md)
- [ConnectRefreshPost400Response](docs/Model/ConnectRefreshPost400Response.md)
- [ConnectStartConfig](docs/Model/ConnectStartConfig.md)
- [ConnectTokenPost200Response](docs/Model/ConnectTokenPost200Response.md)
- [ConnectTokenPost400Response](docs/Model/ConnectTokenPost400Response.md)
- [ConnectTokenPost400ResponseAnyOf](docs/Model/ConnectTokenPost400ResponseAnyOf.md)
- [ConnectTokenPost400ResponseAnyOf1](docs/Model/ConnectTokenPost400ResponseAnyOf1.md)
- [ConnectTokenPost400ResponseAnyOf2](docs/Model/ConnectTokenPost400ResponseAnyOf2.md)
- [ConnectTokenPost400ResponseAnyOf3](docs/Model/ConnectTokenPost400ResponseAnyOf3.md)
- [ConnectTokenPost401Response](docs/Model/ConnectTokenPost401Response.md)
- [ConnectTokenPost500Response](docs/Model/ConnectTokenPost500Response.md)
- [ConnectTokenPostRequest](docs/Model/ConnectTokenPostRequest.md)
- [Contact](docs/Model/Contact.md)
- [ContactOneOf](docs/Model/ContactOneOf.md)
- [ContactOneOf1](docs/Model/ContactOneOf1.md)
- [ContactOneOfLocale](docs/Model/ContactOneOfLocale.md)
- [LengthUnit](docs/Model/LengthUnit.md)
- [Money](docs/Model/Money.md)
- [MultiEmailContact](docs/Model/MultiEmailContact.md)
- [MultiEmailContactOneOf](docs/Model/MultiEmailContactOneOf.md)
- [MultiEmailContactOneOf1](docs/Model/MultiEmailContactOneOf1.md)
- [Order](docs/Model/Order.md)
- [OrderBillingDetails](docs/Model/OrderBillingDetails.md)
- [OrderLine](docs/Model/OrderLine.md)
- [OrderShipping](docs/Model/OrderShipping.md)
- [OrderShippingDelivery](docs/Model/OrderShippingDelivery.md)
- [OrderShippingDeliveryOptions](docs/Model/OrderShippingDeliveryOptions.md)
- [OrderShippingDeliveryOptionsPreferredDate](docs/Model/OrderShippingDeliveryOptionsPreferredDate.md)
- [OrderShippingPickup](docs/Model/OrderShippingPickup.md)
- [OrderShippingPickupAnyOf](docs/Model/OrderShippingPickupAnyOf.md)
- [OrderShippingPickupAnyOf1](docs/Model/OrderShippingPickupAnyOf1.md)
- [OrderShippingPickupAnyOf1Location](docs/Model/OrderShippingPickupAnyOf1Location.md)
- [OrderShippingPickupAnyOf2](docs/Model/OrderShippingPickupAnyOf2.md)
- [OrderShippingPickupAnyOfAddress](docs/Model/OrderShippingPickupAnyOfAddress.md)
- [OrderShippingPickupAnyOfLocation](docs/Model/OrderShippingPickupAnyOfLocation.md)
- [OrderShippingRecipient](docs/Model/OrderShippingRecipient.md)
- [PhysicalProperties](docs/Model/PhysicalProperties.md)
- [PhysicalPropertiesHeight](docs/Model/PhysicalPropertiesHeight.md)
- [PhysicalPropertiesLength](docs/Model/PhysicalPropertiesLength.md)
- [PhysicalPropertiesWeight](docs/Model/PhysicalPropertiesWeight.md)
- [PhysicalPropertiesWidth](docs/Model/PhysicalPropertiesWidth.md)
- [ProblemDetails](docs/Model/ProblemDetails.md)
- [ProblemDetailsClient](docs/Model/ProblemDetailsClient.md)
- [ProblemDetailsInternalServerError](docs/Model/ProblemDetailsInternalServerError.md)
- [ProblemDetailsInvalidRequestSyntax](docs/Model/ProblemDetailsInvalidRequestSyntax.md)
- [ProblemDetailsInvalidRequestSyntaxAllOfErrors](docs/Model/ProblemDetailsInvalidRequestSyntaxAllOfErrors.md)
- [ProblemDetailsNotFound](docs/Model/ProblemDetailsNotFound.md)
- [Product](docs/Model/Product.md)
- [ProductDescriptionsInner](docs/Model/ProductDescriptionsInner.md)
- [SalesPrice](docs/Model/SalesPrice.md)
- [ShipmentOptions](docs/Model/ShipmentOptions.md)
- [ShipmentOptionsCustomLabelText](docs/Model/ShipmentOptionsCustomLabelText.md)
- [ShipmentOptionsInsurance](docs/Model/ShipmentOptionsInsurance.md)
- [ShipmentOptionsInsuranceAmount](docs/Model/ShipmentOptionsInsuranceAmount.md)
- [ShipmentOptionsReturnContributionFee](docs/Model/ShipmentOptionsReturnContributionFee.md)
- [ShipmentOptionsReturnContributionFeeAmount](docs/Model/ShipmentOptionsReturnContributionFeeAmount.md)
- [WebhookOrdersPost202ResponseInner](docs/Model/WebhookOrdersPost202ResponseInner.md)
- [WebhookOrdersPost202ResponseInnerOneOf](docs/Model/WebhookOrdersPost202ResponseInnerOneOf.md)

## Authorization

Authentication schemes defined for the API:
### apiKey

- **Type**: API key
- **API key parameter name**: Authorization
- **Location**: HTTP header


### jwt

- **Type**: Bearer authentication (JWT)

### dpop

## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author



## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `1.0.0`
    - Generator version: `7.12.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
