# MyParcelNL\Sdk\Client\Generated\OrderApi

This API features all functionality around orders.


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
require_once('/path/to/MyParcelNL\Sdk\Client\Generated\OrderApi/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');




$apiInstance = new MyParcelNL\Sdk\Client\Generated\OrderApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$add_note_post_request_inner = array(new \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInner()); // \MyParcelNL\Sdk\Client\Generated\OrderApi\Model\AddNotePostRequestInner[] | Body for adding an order note.

try {
    $result = $apiInstance->addNotePost($add_note_post_request_inner);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->addNotePost: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://order.api.myparcel.nl*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*DefaultApi* | [**addNotePost**](docs/Api/DefaultApi.md#addnotepost) | **POST** /add-note | Add a note to an order.
*DefaultApi* | [**addPackagesPost**](docs/Api/DefaultApi.md#addpackagespost) | **POST** /add-packages | Add packages to orders
*DefaultApi* | [**assignToUserPost**](docs/Api/DefaultApi.md#assigntouserpost) | **POST** /assign-to-user | 
*DefaultApi* | [**cancelPost**](docs/Api/DefaultApi.md#cancelpost) | **POST** /cancel | Cancel orders.
*DefaultApi* | [**createFromShippablePackagesPost**](docs/Api/DefaultApi.md#createfromshippablepackagespost) | **POST** /create-from-shippable-packages | Create an order from shippable packages.
*DefaultApi* | [**editNotePost**](docs/Api/DefaultApi.md#editnotepost) | **POST** /edit-note | Edit a note of an order.
*DefaultApi* | [**importPost**](docs/Api/DefaultApi.md#importpost) | **POST** /import | Import an order after it is discovered from a sales channel.
*DefaultApi* | [**ordersGet**](docs/Api/DefaultApi.md#ordersget) | **GET** /orders | Query and/or filter orders.
*DefaultApi* | [**preparePackagesForShipmentPost**](docs/Api/DefaultApi.md#preparepackagesforshipmentpost) | **POST** /prepare-packages-for-shipment | Prepares packages for shipment
*DefaultApi* | [**removeNotesPost**](docs/Api/DefaultApi.md#removenotespost) | **POST** /remove-notes | Remove notes from an order.
*DefaultApi* | [**unassignFromUserPost**](docs/Api/DefaultApi.md#unassignfromuserpost) | **POST** /unassign-from-user | 
*DefaultApi* | [**unpreparePackagesForShipmentPost**](docs/Api/DefaultApi.md#unpreparepackagesforshipmentpost) | **POST** /unprepare-packages-for-shipment | Unprepares packages for shipment

## Models

- [AddNotePost200ResponseInner](docs/Model/AddNotePost200ResponseInner.md)
- [AddNotePost200ResponseInnerOneOf](docs/Model/AddNotePost200ResponseInnerOneOf.md)
- [AddNotePostRequestInner](docs/Model/AddNotePostRequestInner.md)
- [AddNotePostRequestInnerLocale](docs/Model/AddNotePostRequestInnerLocale.md)
- [AddPackages](docs/Model/AddPackages.md)
- [Address](docs/Model/Address.md)
- [Aggregation](docs/Model/Aggregation.md)
- [AssignToUserPost200ResponseInner](docs/Model/AssignToUserPost200ResponseInner.md)
- [AssignToUserPost200ResponseInnerOneOf](docs/Model/AssignToUserPost200ResponseInnerOneOf.md)
- [AssignToUserPostRequestInner](docs/Model/AssignToUserPostRequestInner.md)
- [BillingDetails](docs/Model/BillingDetails.md)
- [CancelPostRequestInner](docs/Model/CancelPostRequestInner.md)
- [Carrier](docs/Model/Carrier.md)
- [CarrierToCreate](docs/Model/CarrierToCreate.md)
- [Contact](docs/Model/Contact.md)
- [ContactBusiness](docs/Model/ContactBusiness.md)
- [ContactMultiEmail](docs/Model/ContactMultiEmail.md)
- [ContactMultiEmailBusiness](docs/Model/ContactMultiEmailBusiness.md)
- [ContactMultiEmailPrivate](docs/Model/ContactMultiEmailPrivate.md)
- [ContactPrivate](docs/Model/ContactPrivate.md)
- [CreateFromShippablePackagesPost200ResponseInner](docs/Model/CreateFromShippablePackagesPost200ResponseInner.md)
- [CreateFromShippablePackagesPost200ResponseInnerOneOf](docs/Model/CreateFromShippablePackagesPost200ResponseInnerOneOf.md)
- [CreateOrderFromShippablePackages](docs/Model/CreateOrderFromShippablePackages.md)
- [CreateOrderFromShippablePackagesAnyOf](docs/Model/CreateOrderFromShippablePackagesAnyOf.md)
- [CreateOrderFromShippablePackagesAnyOf1](docs/Model/CreateOrderFromShippablePackagesAnyOf1.md)
- [CreateOrderFromShippablePackagesAnyOf1Multicollo](docs/Model/CreateOrderFromShippablePackagesAnyOf1Multicollo.md)
- [CreateOrderFromShippablePackagesAnyOf1MulticolloShipment](docs/Model/CreateOrderFromShippablePackagesAnyOf1MulticolloShipment.md)
- [CustomsDeclaration](docs/Model/CustomsDeclaration.md)
- [CustomsDeclarationGroupsInner](docs/Model/CustomsDeclarationGroupsInner.md)
- [CustomsDeclarationGroupsInnerTotalValue](docs/Model/CustomsDeclarationGroupsInnerTotalValue.md)
- [CustomsDeclarationGroupsInnerTotalWeight](docs/Model/CustomsDeclarationGroupsInnerTotalWeight.md)
- [CustomsDeclarationResponse](docs/Model/CustomsDeclarationResponse.md)
- [CustomsDeclarationResponseGroupsInner](docs/Model/CustomsDeclarationResponseGroupsInner.md)
- [CustomsDeclarationResponseGroupsInnerTotalWeight](docs/Model/CustomsDeclarationResponseGroupsInnerTotalWeight.md)
- [CustomsDeclarationResponseTotalWeight](docs/Model/CustomsDeclarationResponseTotalWeight.md)
- [CustomsDeclarationTotalWeight](docs/Model/CustomsDeclarationTotalWeight.md)
- [Delivery](docs/Model/Delivery.md)
- [DeliveryOptions](docs/Model/DeliveryOptions.md)
- [DeliveryOptionsPreferredDate](docs/Model/DeliveryOptionsPreferredDate.md)
- [DeliveryType](docs/Model/DeliveryType.md)
- [DropOff](docs/Model/DropOff.md)
- [EditNotePostRequestInner](docs/Model/EditNotePostRequestInner.md)
- [ExternalReferenceEcommercePlatform](docs/Model/ExternalReferenceEcommercePlatform.md)
- [ExternalReferenceSalesChannel](docs/Model/ExternalReferenceSalesChannel.md)
- [ExternalReferences](docs/Model/ExternalReferences.md)
- [GeoAreaCode](docs/Model/GeoAreaCode.md)
- [LabelWithBarcode](docs/Model/LabelWithBarcode.md)
- [LabelWithBarcodeCreatedBy](docs/Model/LabelWithBarcodeCreatedBy.md)
- [LabelWithoutBarcode](docs/Model/LabelWithoutBarcode.md)
- [Line](docs/Model/Line.md)
- [LineExternalReference](docs/Model/LineExternalReference.md)
- [LinePrice](docs/Model/LinePrice.md)
- [LineProduct](docs/Model/LineProduct.md)
- [LineProductEan](docs/Model/LineProductEan.md)
- [Money](docs/Model/Money.md)
- [Note](docs/Model/Note.md)
- [NoteFromImported](docs/Model/NoteFromImported.md)
- [NoteFromImportedLocale](docs/Model/NoteFromImportedLocale.md)
- [NoteFromImportedNotedAt](docs/Model/NoteFromImportedNotedAt.md)
- [NoteFromImportedUpdatedAt](docs/Model/NoteFromImportedUpdatedAt.md)
- [NoteFromPrincipal](docs/Model/NoteFromPrincipal.md)
- [Order](docs/Model/Order.md)
- [OrderOrderedAt](docs/Model/OrderOrderedAt.md)
- [OrderStatus](docs/Model/OrderStatus.md)
- [OrdersGet200Response](docs/Model/OrdersGet200Response.md)
- [OrdersGet200ResponseAggregations](docs/Model/OrdersGet200ResponseAggregations.md)
- [OrdersGet200ResponseAggregationsStatusInner](docs/Model/OrdersGet200ResponseAggregationsStatusInner.md)
- [OrdersGet200ResponseTotal](docs/Model/OrdersGet200ResponseTotal.md)
- [OrdersGetFilterParameter](docs/Model/OrdersGetFilterParameter.md)
- [OrdersGetSortParameter](docs/Model/OrdersGetSortParameter.md)
- [PackageCommon](docs/Model/PackageCommon.md)
- [PackageCommonShipment](docs/Model/PackageCommonShipment.md)
- [PackageRequest](docs/Model/PackageRequest.md)
- [PackageRequestLinesInner](docs/Model/PackageRequestLinesInner.md)
- [PackageResponse](docs/Model/PackageResponse.md)
- [PackageResponseLinesInner](docs/Model/PackageResponseLinesInner.md)
- [PackageStatus](docs/Model/PackageStatus.md)
- [PackageStatusAnyOf](docs/Model/PackageStatusAnyOf.md)
- [PackageStatusAnyOf1](docs/Model/PackageStatusAnyOf1.md)
- [PackageStatusAnyOf10](docs/Model/PackageStatusAnyOf10.md)
- [PackageStatusAnyOf11](docs/Model/PackageStatusAnyOf11.md)
- [PackageStatusAnyOf12](docs/Model/PackageStatusAnyOf12.md)
- [PackageStatusAnyOf13](docs/Model/PackageStatusAnyOf13.md)
- [PackageStatusAnyOf2](docs/Model/PackageStatusAnyOf2.md)
- [PackageStatusAnyOf3](docs/Model/PackageStatusAnyOf3.md)
- [PackageStatusAnyOf4](docs/Model/PackageStatusAnyOf4.md)
- [PackageStatusAnyOf5](docs/Model/PackageStatusAnyOf5.md)
- [PackageStatusAnyOf6](docs/Model/PackageStatusAnyOf6.md)
- [PackageStatusAnyOf7](docs/Model/PackageStatusAnyOf7.md)
- [PackageStatusAnyOf8](docs/Model/PackageStatusAnyOf8.md)
- [PackageStatusAnyOf9](docs/Model/PackageStatusAnyOf9.md)
- [PackageType](docs/Model/PackageType.md)
- [PaymentStatus](docs/Model/PaymentStatus.md)
- [PhysicalProperties](docs/Model/PhysicalProperties.md)
- [PhysicalPropertiesHeight](docs/Model/PhysicalPropertiesHeight.md)
- [PhysicalPropertiesLength](docs/Model/PhysicalPropertiesLength.md)
- [PhysicalPropertiesWeight](docs/Model/PhysicalPropertiesWeight.md)
- [PhysicalPropertiesWeightRequired](docs/Model/PhysicalPropertiesWeightRequired.md)
- [PhysicalPropertiesWeightRequiredHeight](docs/Model/PhysicalPropertiesWeightRequiredHeight.md)
- [PhysicalPropertiesWeightRequiredLength](docs/Model/PhysicalPropertiesWeightRequiredLength.md)
- [PhysicalPropertiesWeightRequiredWeight](docs/Model/PhysicalPropertiesWeightRequiredWeight.md)
- [PhysicalPropertiesWeightRequiredWidth](docs/Model/PhysicalPropertiesWeightRequiredWidth.md)
- [PhysicalPropertiesWidth](docs/Model/PhysicalPropertiesWidth.md)
- [Pickup](docs/Model/Pickup.md)
- [PickupAnyOf](docs/Model/PickupAnyOf.md)
- [PickupAnyOf1](docs/Model/PickupAnyOf1.md)
- [PickupAnyOf1Location](docs/Model/PickupAnyOf1Location.md)
- [PickupAnyOf2](docs/Model/PickupAnyOf2.md)
- [PickupAnyOfLocation](docs/Model/PickupAnyOfLocation.md)
- [PreparePackagesForShipment](docs/Model/PreparePackagesForShipment.md)
- [PreparePackagesForShipmentAnyOf](docs/Model/PreparePackagesForShipmentAnyOf.md)
- [PreparePackagesForShipmentAnyOf1](docs/Model/PreparePackagesForShipmentAnyOf1.md)
- [PreparePackagesForShipmentPackage](docs/Model/PreparePackagesForShipmentPackage.md)
- [ProblemDetails](docs/Model/ProblemDetails.md)
- [ProblemDetailsClient](docs/Model/ProblemDetailsClient.md)
- [ProblemDetailsClientAllOfErrors](docs/Model/ProblemDetailsClientAllOfErrors.md)
- [ProblemDetailsInternalServerError](docs/Model/ProblemDetailsInternalServerError.md)
- [ProblemDetailsInvalidRequestSyntax](docs/Model/ProblemDetailsInvalidRequestSyntax.md)
- [RegionCode](docs/Model/RegionCode.md)
- [RemoveNotesPostRequestInner](docs/Model/RemoveNotesPostRequestInner.md)
- [Shipment](docs/Model/Shipment.md)
- [ShipmentCreatedAt](docs/Model/ShipmentCreatedAt.md)
- [ShipmentDirection](docs/Model/ShipmentDirection.md)
- [ShipmentLabel](docs/Model/ShipmentLabel.md)
- [ShipmentMulticollo](docs/Model/ShipmentMulticollo.md)
- [ShipmentOptions](docs/Model/ShipmentOptions.md)
- [ShipmentOptionsCommon](docs/Model/ShipmentOptionsCommon.md)
- [ShipmentOptionsCommonInsurance](docs/Model/ShipmentOptionsCommonInsurance.md)
- [ShipmentOptionsCommonInsuranceAmount](docs/Model/ShipmentOptionsCommonInsuranceAmount.md)
- [ShipmentOptionsCommonReturnContributionFee](docs/Model/ShipmentOptionsCommonReturnContributionFee.md)
- [ShipmentOptionsCommonReturnContributionFeeAmount](docs/Model/ShipmentOptionsCommonReturnContributionFeeAmount.md)
- [ShipmentOptionsPackage](docs/Model/ShipmentOptionsPackage.md)
- [ShipmentOptionsPackageCustomLabelText](docs/Model/ShipmentOptionsPackageCustomLabelText.md)
- [ShipmentRecipient](docs/Model/ShipmentRecipient.md)
- [ShipmentRequest](docs/Model/ShipmentRequest.md)
- [ShipmentRequestRecipient](docs/Model/ShipmentRequestRecipient.md)
- [ShipmentRequestSender](docs/Model/ShipmentRequestSender.md)
- [ShippablePackage](docs/Model/ShippablePackage.md)
- [ShippablePackageShipment](docs/Model/ShippablePackageShipment.md)
- [Shipping](docs/Model/Shipping.md)
- [SortDirection](docs/Model/SortDirection.md)
- [UnpreparePackagesForShipmentPostRequestInner](docs/Model/UnpreparePackagesForShipmentPostRequestInner.md)

## Authorization

Authentication schemes defined for the API:
### apiKey

- **Type**: API key
- **API key parameter name**: Authorization
- **Location**: HTTP header


### jwt

- **Type**: Bearer authentication (JWT)

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
