# MyParcelNL\Sdk\Client\Generated\CoreApiPrivate

Allows MyParcel users to query delivery options, pickup & drop off locations with opening hours, register & trace shipments, print labels and more.

For more information, please visit [https://developer.myparcel.nl/contact.html](https://developer.myparcel.nl/contact.html).

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
require_once('/path/to/MyParcelNL\Sdk\Client\Generated\CoreApiPrivate/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure API key authorization: apiKey
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKey('Authorization', 'YOUR_API_KEY');
// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
// $config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Bearer');

// Configure Bearer authorization: bearer
$config = MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcelNL\Sdk\Client\Generated\CoreApiPrivate\Api\ShippingRuleApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$shipping_rule_id = 56; // int | A single shipping rule ID.
$user_agent = User-Agent: MyFirstCMS/3.0.0 PHP/9.5.0; // string | To give us insight into where requests come from and API documentation usage, you should send a `User-Agent` header with all your requests. This header should include information about your integration, the CMS/platform and the backend you are using.

try {
    $apiInstance->deleteShippingRule($shipping_rule_id, $user_agent);
} catch (Exception $e) {
    echo 'Exception when calling ShippingRuleApi->deleteShippingRule: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.myparcel.nl*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*ShippingRuleApi* | [**deleteShippingRule**](docs/Api/ShippingRuleApi.md#deleteshippingrule) | **DELETE** /shipping_rules/{shipping_rule_id} | Delete a shipping rule
*ShippingRuleApi* | [**getShippingRuleImplications**](docs/Api/ShippingRuleApi.md#getshippingruleimplications) | **GET** /shops/{shop_id}/shipping_rules/implications | Get shipping rule implications for a shop
*ShippingRuleApi* | [**getShippingRules**](docs/Api/ShippingRuleApi.md#getshippingrules) | **GET** /shipping_rules | Get shipping rules
*ShippingRuleApi* | [**getShippingRulesByIds**](docs/Api/ShippingRuleApi.md#getshippingrulesbyids) | **GET** /shipping_rules/{shipping_rule_ids} | Get shipping rules by id
*ShippingRuleApi* | [**getShippingRulesByShop**](docs/Api/ShippingRuleApi.md#getshippingrulesbyshop) | **GET** /shops/{shop_id}/shipping_rules | Get shipping rules for a shop
*ShippingRuleApi* | [**patchShippingRules**](docs/Api/ShippingRuleApi.md#patchshippingrules) | **PATCH** /shipping_rules | Partially update shipping rules
*ShippingRuleApi* | [**postShippingRules**](docs/Api/ShippingRuleApi.md#postshippingrules) | **POST** /shipping_rules | Create shipping rules
*ShippingRuleApi* | [**putShippingRules**](docs/Api/ShippingRuleApi.md#putshippingrules) | **PUT** /shipping_rules | Replace shipping rules

## Models

- [CommonErrorSystem](docs/Model/CommonErrorSystem.md)
- [CommonErrorUser](docs/Model/CommonErrorUser.md)
- [CommonErrorUserCode](docs/Model/CommonErrorUserCode.md)
- [CommonErrorUserCodeAuth](docs/Model/CommonErrorUserCodeAuth.md)
- [CommonParametersBigids](docs/Model/CommonParametersBigids.md)
- [CommonResponsesSystemError](docs/Model/CommonResponsesSystemError.md)
- [CommonResponsesUserError](docs/Model/CommonResponsesUserError.md)
- [RefShipmentOptionsDeliveryTypeAll](docs/Model/RefShipmentOptionsDeliveryTypeAll.md)
- [RefShipmentOptionsDeliveryTypeDeliveryDate](docs/Model/RefShipmentOptionsDeliveryTypeDeliveryDate.md)
- [RefShipmentOptionsInsurance](docs/Model/RefShipmentOptionsInsurance.md)
- [RefShipmentOptionsInsuranceMax](docs/Model/RefShipmentOptionsInsuranceMax.md)
- [RefShipmentOptionsLabelDescription](docs/Model/RefShipmentOptionsLabelDescription.md)
- [RefShipmentOptionsOptions](docs/Model/RefShipmentOptionsOptions.md)
- [RefShipmentOptionsPackageTypeAll](docs/Model/RefShipmentOptionsPackageTypeAll.md)
- [RefShipmentPackageType](docs/Model/RefShipmentPackageType.md)
- [RefShipmentShipmentOptions](docs/Model/RefShipmentShipmentOptions.md)
- [RefShippingRulesCriteria](docs/Model/RefShippingRulesCriteria.md)
- [RefShippingRulesCurrencyAmount](docs/Model/RefShippingRulesCurrencyAmount.md)
- [RefShippingRulesImplications](docs/Model/RefShippingRulesImplications.md)
- [RefShippingRulesImplicationsBase](docs/Model/RefShippingRulesImplicationsBase.md)
- [RefShippingRulesPhysicalProperties](docs/Model/RefShippingRulesPhysicalProperties.md)
- [RefShippingRulesRegion](docs/Model/RefShippingRulesRegion.md)
- [RefShippingRulesShipmentOptions](docs/Model/RefShippingRulesShipmentOptions.md)
- [RefShippingRulesShippingRule](docs/Model/RefShippingRulesShippingRule.md)
- [RefShippingRulesType](docs/Model/RefShippingRulesType.md)
- [RefTypesCarrier](docs/Model/RefTypesCarrier.md)
- [RefTypesIntBoolean](docs/Model/RefTypesIntBoolean.md)
- [RefTypesMoney](docs/Model/RefTypesMoney.md)
- [RefTypesMoneyAmount](docs/Model/RefTypesMoneyAmount.md)
- [RefTypesPriceEuro](docs/Model/RefTypesPriceEuro.md)
- [ShippingRulesPatchShippingRules](docs/Model/ShippingRulesPatchShippingRules.md)
- [ShippingRulesPatchShippingRulesData](docs/Model/ShippingRulesPatchShippingRulesData.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInner](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInner.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteria](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteria.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteriaCountry](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteriaCountry.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteriaRegion](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerCriteriaRegion.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerImplications](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerImplications.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerImplicationsPhysicalProperties](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerImplicationsPhysicalProperties.md)
- [ShippingRulesPatchShippingRulesDataShippingRulesInnerImplicationsShipmentOptions](docs/Model/ShippingRulesPatchShippingRulesDataShippingRulesInnerImplicationsShipmentOptions.md)
- [ShippingRulesPostShippingRules](docs/Model/ShippingRulesPostShippingRules.md)
- [ShippingRulesPostShippingRulesData](docs/Model/ShippingRulesPostShippingRulesData.md)
- [ShippingRulesPostShippingRulesDataShippingRulesInner](docs/Model/ShippingRulesPostShippingRulesDataShippingRulesInner.md)
- [ShippingRulesPostShippingRulesDataShippingRulesInnerImplications](docs/Model/ShippingRulesPostShippingRulesDataShippingRulesInnerImplications.md)
- [ShippingRulesPutShippingRules](docs/Model/ShippingRulesPutShippingRules.md)
- [ShippingRulesPutShippingRulesData](docs/Model/ShippingRulesPutShippingRulesData.md)
- [ShippingRulesPutShippingRulesDataShippingRulesInner](docs/Model/ShippingRulesPutShippingRulesDataShippingRulesInner.md)
- [ShippingRulesResponsesImplications](docs/Model/ShippingRulesResponsesImplications.md)
- [ShippingRulesResponsesImplicationsData](docs/Model/ShippingRulesResponsesImplicationsData.md)
- [ShippingRulesResponsesShippingRules](docs/Model/ShippingRulesResponsesShippingRules.md)
- [ShippingRulesResponsesShippingRulesData](docs/Model/ShippingRulesResponsesShippingRulesData.md)
- [ShippingRulesResponsesShippingRulesPaginated](docs/Model/ShippingRulesResponsesShippingRulesPaginated.md)
- [ShippingRulesResponsesShippingRulesPaginatedData](docs/Model/ShippingRulesResponsesShippingRulesPaginatedData.md)

## Authorization

Authentication schemes defined for the API:
### bearer

- **Type**: Bearer authentication

### apiKey

- **Type**: API key
- **API key parameter name**: Authorization
- **Location**: HTTP header


## Tests

To run the tests, use:

```bash
composer install
vendor/bin/phpunit
```

## Author

info@myparcel.nl

## About this package

This PHP package is automatically generated by the [OpenAPI Generator](https://openapi-generator.tech) project:

- API version: `2026-04-16`
    - Generator version: `7.12.0`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
