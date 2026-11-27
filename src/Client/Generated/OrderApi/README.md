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
*DefaultApi* | [**createPost**](docs/Api/DefaultApi.md#createpost) | **POST** /create | Create orders.
*DefaultApi* | [**editNotePost**](docs/Api/DefaultApi.md#editnotepost) | **POST** /edit-note | Edit a note of an order.
*DefaultApi* | [**evaluateRulesPost**](docs/Api/DefaultApi.md#evaluaterulespost) | **POST** /evaluate-rules | Evaluate order rules.
*DefaultApi* | [**importPost**](docs/Api/DefaultApi.md#importpost) | **POST** /import | Import an order after it is discovered from a sales channel.
*DefaultApi* | [**ordersGet**](docs/Api/DefaultApi.md#ordersget) | **GET** /orders | Query and/or filter orders.
*DefaultApi* | [**packageGroupsPut**](docs/Api/DefaultApi.md#packagegroupsput) | **PUT** /package-groups | Put package groups to orders
*DefaultApi* | [**packagesPut**](docs/Api/DefaultApi.md#packagesput) | **PUT** /packages | Put packages to orders
*DefaultApi* | [**packingSlipsGet**](docs/Api/DefaultApi.md#packingslipsget) | **GET** /packing-slips | Generate packing slips for 1 or more orders.
*DefaultApi* | [**picklistGet**](docs/Api/DefaultApi.md#picklistget) | **GET** /picklist | Generate a picklist for 1 or more orders.
*DefaultApi* | [**preparePackagesForShipmentPost**](docs/Api/DefaultApi.md#preparepackagesforshipmentpost) | **POST** /prepare-packages-for-shipment | Prepares packages for shipment
*DefaultApi* | [**removeNotesPost**](docs/Api/DefaultApi.md#removenotespost) | **POST** /remove-notes | Remove notes from an order.
*DefaultApi* | [**shipPackagesPost**](docs/Api/DefaultApi.md#shippackagespost) | **POST** /ship-packages | Post ship packages
*DefaultApi* | [**unassignFromUserPost**](docs/Api/DefaultApi.md#unassignfromuserpost) | **POST** /unassign-from-user | 
*DefaultApi* | [**unpreparePackagesForShipmentPost**](docs/Api/DefaultApi.md#unpreparepackagesforshipmentpost) | **POST** /unprepare-packages-for-shipment | Unprepares packages for shipment

## Models

- [AddNotePostRequestInner](docs/Model/AddNotePostRequestInner.md)
- [AddNotePostRequestInnerLocale](docs/Model/AddNotePostRequestInnerLocale.md)
- [AddPackages](docs/Model/AddPackages.md)
- [Address](docs/Model/Address.md)
- [AssignToUserPostRequestInner](docs/Model/AssignToUserPostRequestInner.md)
- [BillingDetails](docs/Model/BillingDetails.md)
- [CancelPostRequestInner](docs/Model/CancelPostRequestInner.md)
- [Carrier](docs/Model/Carrier.md)
- [CarrierToCreate](docs/Model/CarrierToCreate.md)
- [CommandResponseAdditiveCollectionBodyInner](docs/Model/CommandResponseAdditiveCollectionBodyInner.md)
- [CommandResponseAdditiveCollectionBodyInnerAnyOf](docs/Model/CommandResponseAdditiveCollectionBodyInnerAnyOf.md)
- [CommandResponseCreationalBodyInner](docs/Model/CommandResponseCreationalBodyInner.md)
- [CommandResponseCreationalBodyInnerAnyOf](docs/Model/CommandResponseCreationalBodyInnerAnyOf.md)
- [CommandResponseMutatingBodyInner](docs/Model/CommandResponseMutatingBodyInner.md)
- [CommandResponseMutatingBodyInnerAnyOf](docs/Model/CommandResponseMutatingBodyInnerAnyOf.md)
- [Contact](docs/Model/Contact.md)
- [ContactBusiness](docs/Model/ContactBusiness.md)
- [ContactMultiEmail](docs/Model/ContactMultiEmail.md)
- [ContactMultiEmailBusiness](docs/Model/ContactMultiEmailBusiness.md)
- [ContactMultiEmailPrivate](docs/Model/ContactMultiEmailPrivate.md)
- [ContactPrivate](docs/Model/ContactPrivate.md)
- [CreateOrderFromShippablePackages](docs/Model/CreateOrderFromShippablePackages.md)
- [CreateOrderFromShippablePackagesAnyOf](docs/Model/CreateOrderFromShippablePackagesAnyOf.md)
- [CreateOrderFromShippablePackagesAnyOf1](docs/Model/CreateOrderFromShippablePackagesAnyOf1.md)
- [CreateOrderFromShippablePackagesAnyOf1Multicollo](docs/Model/CreateOrderFromShippablePackagesAnyOf1Multicollo.md)
- [CreatePost202Response](docs/Model/CreatePost202Response.md)
- [CreatePost202ResponseResultsInner](docs/Model/CreatePost202ResponseResultsInner.md)
- [CreatePost202ResponseResultsInnerAnyOf](docs/Model/CreatePost202ResponseResultsInnerAnyOf.md)
- [CreatePost202ResponseResultsInnerAnyOf1](docs/Model/CreatePost202ResponseResultsInnerAnyOf1.md)
- [CreatePost202ResponseResultsInnerAnyOf1ErrorsInner](docs/Model/CreatePost202ResponseResultsInnerAnyOf1ErrorsInner.md)
- [CustomsDeclaration](docs/Model/CustomsDeclaration.md)
- [CustomsDeclarationGroupsInner](docs/Model/CustomsDeclarationGroupsInner.md)
- [CustomsDeclarationGroupsInnerTotalWeight](docs/Model/CustomsDeclarationGroupsInnerTotalWeight.md)
- [CustomsDeclarationResponse](docs/Model/CustomsDeclarationResponse.md)
- [CustomsDeclarationResponseGroupsInner](docs/Model/CustomsDeclarationResponseGroupsInner.md)
- [CustomsDeclarationTotalWeight](docs/Model/CustomsDeclarationTotalWeight.md)
- [DeliveryInput](docs/Model/DeliveryInput.md)
- [DeliveryOptionsInput](docs/Model/DeliveryOptionsInput.md)
- [DeliveryOptionsInputPreferredDate](docs/Model/DeliveryOptionsInputPreferredDate.md)
- [DeliveryOptionsOutput](docs/Model/DeliveryOptionsOutput.md)
- [DeliveryOutput](docs/Model/DeliveryOutput.md)
- [DeliveryType](docs/Model/DeliveryType.md)
- [DropOff](docs/Model/DropOff.md)
- [EditNotePostRequestInner](docs/Model/EditNotePostRequestInner.md)
- [ExternalReferenceEcommercePlatform](docs/Model/ExternalReferenceEcommercePlatform.md)
- [ExternalReferenceSalesChannel](docs/Model/ExternalReferenceSalesChannel.md)
- [ExternalReferences](docs/Model/ExternalReferences.md)
- [ExternalReferencesImport](docs/Model/ExternalReferencesImport.md)
- [ExternalReferencesMyParcelShop](docs/Model/ExternalReferencesMyParcelShop.md)
- [ExternalReferencesSalesChannel](docs/Model/ExternalReferencesSalesChannel.md)
- [GeoAreaCode](docs/Model/GeoAreaCode.md)
- [LabelWithBarcode](docs/Model/LabelWithBarcode.md)
- [LabelWithBarcodeCreatedBy](docs/Model/LabelWithBarcodeCreatedBy.md)
- [LabelWithoutBarcode](docs/Model/LabelWithoutBarcode.md)
- [Line](docs/Model/Line.md)
- [LineExternalReference](docs/Model/LineExternalReference.md)
- [LineProduct](docs/Model/LineProduct.md)
- [Money](docs/Model/Money.md)
- [Note](docs/Model/Note.md)
- [NoteFromImported](docs/Model/NoteFromImported.md)
- [NoteFromImportedLocale](docs/Model/NoteFromImportedLocale.md)
- [NoteFromPrincipal](docs/Model/NoteFromPrincipal.md)
- [Order](docs/Model/Order.md)
- [OrderLineToCreate](docs/Model/OrderLineToCreate.md)
- [OrderRules](docs/Model/OrderRules.md)
- [OrderRulesSetShippingPreferences](docs/Model/OrderRulesSetShippingPreferences.md)
- [OrderRulesSetShippingPreferencesPathInner](docs/Model/OrderRulesSetShippingPreferencesPathInner.md)
- [OrderRulesSetShippingPreferencesPathInnerAnyOf](docs/Model/OrderRulesSetShippingPreferencesPathInnerAnyOf.md)
- [OrderRulesSetShippingPreferencesPathInnerAnyOf1](docs/Model/OrderRulesSetShippingPreferencesPathInnerAnyOf1.md)
- [OrderRulesSetShippingPreferencesPathInnerAnyOf1Condition](docs/Model/OrderRulesSetShippingPreferencesPathInnerAnyOf1Condition.md)
- [OrderRulesSetShippingPreferencesPathInnerAnyOf1ConditionAnyOf](docs/Model/OrderRulesSetShippingPreferencesPathInnerAnyOf1ConditionAnyOf.md)
- [OrderRulesSetShippingPreferencesPathInnerAnyOf1ConditionAnyOf1](docs/Model/OrderRulesSetShippingPreferencesPathInnerAnyOf1ConditionAnyOf1.md)
- [OrderStatus](docs/Model/OrderStatus.md)
- [OrderToCreate](docs/Model/OrderToCreate.md)
- [OrderToCreateBillingDetails](docs/Model/OrderToCreateBillingDetails.md)
- [OrderToCreateShipping](docs/Model/OrderToCreateShipping.md)
- [OrdersGet200Response](docs/Model/OrdersGet200Response.md)
- [OrdersGet200ResponseAggregations](docs/Model/OrdersGet200ResponseAggregations.md)
- [OrdersGet200ResponseAggregationsStatusInner](docs/Model/OrdersGet200ResponseAggregationsStatusInner.md)
- [OrdersGet200ResponseTotal](docs/Model/OrdersGet200ResponseTotal.md)
- [OrdersGetFilterParameter](docs/Model/OrdersGetFilterParameter.md)
- [OrdersGetSortParameter](docs/Model/OrdersGetSortParameter.md)
- [PackageCommon](docs/Model/PackageCommon.md)
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
- [PackingSlipsGet400Response](docs/Model/PackingSlipsGet400Response.md)
- [PaymentStatus](docs/Model/PaymentStatus.md)
- [PhysicalProperties](docs/Model/PhysicalProperties.md)
- [PhysicalPropertiesHeight](docs/Model/PhysicalPropertiesHeight.md)
- [PhysicalPropertiesLength](docs/Model/PhysicalPropertiesLength.md)
- [PhysicalPropertiesWeight](docs/Model/PhysicalPropertiesWeight.md)
- [PhysicalPropertiesWeightRequired](docs/Model/PhysicalPropertiesWeightRequired.md)
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
- [ProblemDetailsClient](docs/Model/ProblemDetailsClient.md)
- [ProblemDetailsInternalServerError](docs/Model/ProblemDetailsInternalServerError.md)
- [ProblemDetailsInvalidRequestSyntax](docs/Model/ProblemDetailsInvalidRequestSyntax.md)
- [ProblemDetailsNotFound](docs/Model/ProblemDetailsNotFound.md)
- [ProblemDetailsUnsupportedMediaType](docs/Model/ProblemDetailsUnsupportedMediaType.md)
- [ProductToCreate](docs/Model/ProductToCreate.md)
- [ProductToCreateDescriptionsInner](docs/Model/ProductToCreateDescriptionsInner.md)
- [ProductToCreateFulfilment](docs/Model/ProductToCreateFulfilment.md)
- [ProductToCreateSalesPrice](docs/Model/ProductToCreateSalesPrice.md)
- [PutPackageGroups](docs/Model/PutPackageGroups.md)
- [PutPackageGroupsPackageGroupsInner](docs/Model/PutPackageGroupsPackageGroupsInner.md)
- [PutPackages](docs/Model/PutPackages.md)
- [PutPackagesPackagesInner](docs/Model/PutPackagesPackagesInner.md)
- [PutPackagesPackagesInnerShipment](docs/Model/PutPackagesPackagesInnerShipment.md)
- [RegionCode](docs/Model/RegionCode.md)
- [RemoveNotesPostRequestInner](docs/Model/RemoveNotesPostRequestInner.md)
- [ShipPackages](docs/Model/ShipPackages.md)
- [Shipment](docs/Model/Shipment.md)
- [ShipmentDirection](docs/Model/ShipmentDirection.md)
- [ShipmentIntentRequest](docs/Model/ShipmentIntentRequest.md)
- [ShipmentLabel](docs/Model/ShipmentLabel.md)
- [ShipmentMulticollo](docs/Model/ShipmentMulticollo.md)
- [ShipmentOptionsCommon](docs/Model/ShipmentOptionsCommon.md)
- [ShipmentOptionsCommonInsurance](docs/Model/ShipmentOptionsCommonInsurance.md)
- [ShipmentOptionsCommonReturnContributionFee](docs/Model/ShipmentOptionsCommonReturnContributionFee.md)
- [ShipmentOptionsInput](docs/Model/ShipmentOptionsInput.md)
- [ShipmentOptionsOutput](docs/Model/ShipmentOptionsOutput.md)
- [ShipmentOptionsPackage](docs/Model/ShipmentOptionsPackage.md)
- [ShipmentOptionsPackageCustomLabelText](docs/Model/ShipmentOptionsPackageCustomLabelText.md)
- [ShipmentRecipient](docs/Model/ShipmentRecipient.md)
- [ShipmentRequest](docs/Model/ShipmentRequest.md)
- [ShipmentRequestRecipient](docs/Model/ShipmentRequestRecipient.md)
- [ShipmentRequestRecipientContact](docs/Model/ShipmentRequestRecipientContact.md)
- [ShipmentRequestRecipientContactOneOf](docs/Model/ShipmentRequestRecipientContactOneOf.md)
- [ShipmentRequestSender](docs/Model/ShipmentRequestSender.md)
- [ShippablePackage](docs/Model/ShippablePackage.md)
- [ShippablePackageShipment](docs/Model/ShippablePackageShipment.md)
- [Shipping](docs/Model/Shipping.md)
- [ShippingCustomsDeclarationResponse](docs/Model/ShippingCustomsDeclarationResponse.md)
- [ShippingCustomsDeclarationResponseGroupsInner](docs/Model/ShippingCustomsDeclarationResponseGroupsInner.md)
- [ShippingRecipient](docs/Model/ShippingRecipient.md)
- [SortDirection](docs/Model/SortDirection.md)
- [UnauthorizedResponse](docs/Model/UnauthorizedResponse.md)
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
