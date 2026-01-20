# MyParcel\CoreApi\Generated\Shipments

Allows MyParcel users to query delivery options, pickup & drop off locations with opening hours, register & trace shipments, print labels and more.

For more information, please visit [https://developer.myparcel.nl/contact.html](https://developer.myparcel.nl/contact.html).

## Installation & Usage

### Requirements

PHP 8.1 and later.

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
require_once('/path/to/MyParcel\CoreApi\Generated\Shipments/vendor/autoload.php');
```

## Getting Started

Please follow the [installation procedure](#installation--usage) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



// Configure HTTP basic authorization: apiKey
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()
              ->setUsername('YOUR_USERNAME')
              ->setPassword('YOUR_PASSWORD');

// Configure Bearer authorization: bearer
$config = MyParcel\CoreApi\Generated\Shipments\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');


$apiInstance = new MyParcel\CoreApi\Generated\Shipments\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);

try {
    $result = $apiInstance->getIndex();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getIndex: ', $e->getMessage(), PHP_EOL;
}

```

## API Endpoints

All URIs are relative to *https://api.myparcel.nl*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*DefaultApi* | [**getIndex**](docs/Api/DefaultApi.md#getindex) | **GET** / | 
*NotificationApi* | [**deleteNotificationGroups**](docs/Api/NotificationApi.md#deletenotificationgroups) | **DELETE** /notification_groups/{ids} | Delete notification groups
*NotificationApi* | [**disableAllNotificationTemplatesByGroup**](docs/Api/NotificationApi.md#disableallnotificationtemplatesbygroup) | **PUT** /notification_groups/{notification_group_id}/notification_templates/disable | Disable all notification templates in a notification group
*NotificationApi* | [**disableNotificationTemplate**](docs/Api/NotificationApi.md#disablenotificationtemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/disable | Disable notification template
*NotificationApi* | [**enableAllNotificationTemplatesByGroup**](docs/Api/NotificationApi.md#enableallnotificationtemplatesbygroup) | **PUT** /notification_groups/{notification_group_id}/notification_templates/enable | Enable all notification templates in a notification group
*NotificationApi* | [**enableNotificationTemplate**](docs/Api/NotificationApi.md#enablenotificationtemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/enable | Enable notification template
*NotificationApi* | [**getNotificationGroups**](docs/Api/NotificationApi.md#getnotificationgroups) | **GET** /notification_groups | Get notification groups
*NotificationApi* | [**getNotificationTemplates**](docs/Api/NotificationApi.md#getnotificationtemplates) | **GET** /notification_groups/{notification_group_id}/notification_templates | Get notification templates
*NotificationApi* | [**postNotificationGroups**](docs/Api/NotificationApi.md#postnotificationgroups) | **POST** /notification_groups | Create notification groups
*NotificationApi* | [**putNotificationTemplate**](docs/Api/NotificationApi.md#putnotificationtemplate) | **PUT** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id} | Update notification template
*NotificationApi* | [**sendTestNotification**](docs/Api/NotificationApi.md#sendtestnotification) | **POST** /notification_groups/{notification_group_id}/notification_templates/{notification_template_id}/test | Send test notification
*ShipmentApi* | [**deleteShipments**](docs/Api/ShipmentApi.md#deleteshipments) | **DELETE** /shipments/{ids} | Delete Shipment
*ShipmentApi* | [**getShipments**](docs/Api/ShipmentApi.md#getshipments) | **GET** /shipments | Gets a list of Shipments, optionally filtered using parameters.
*ShipmentApi* | [**getShipmentsById**](docs/Api/ShipmentApi.md#getshipmentsbyid) | **GET** /shipments/{ids} | Get shipments by id.
*ShipmentApi* | [**postCapabilities**](docs/Api/ShipmentApi.md#postcapabilities) | **POST** /shipments/capabilities | List shipment capabilities
*ShipmentApi* | [**postCapabilitiesContractDefinitions**](docs/Api/ShipmentApi.md#postcapabilitiescontractdefinitions) | **POST** /shipments/capabilities/contract-definitions | List a superset of available capabilities for the carriers and contracts associated with the logged-in user.
*ShipmentApi* | [**postRates**](docs/Api/ShipmentApi.md#postrates) | **POST** /shipments/rates | List shipment rates
*ShipmentApi* | [**postShipments**](docs/Api/ShipmentApi.md#postshipments) | **POST** /shipments | Add Shipment
*ShipmentApi* | [**postUnrelatedReturnShipments**](docs/Api/ShipmentApi.md#postunrelatedreturnshipments) | **POST** /return_shipments | Generate unrelated return shipment URL
*ShipmentApi* | [**putShipment**](docs/Api/ShipmentApi.md#putshipment) | **PUT** /shipments | Update Shipment

## Models

- [AccountDefsContact](docs/Model/AccountDefsContact.md)
- [AccountDefsContactPropertiesEmail](docs/Model/AccountDefsContactPropertiesEmail.md)
- [AccountDefsLocationPropertiesNumber](docs/Model/AccountDefsLocationPropertiesNumber.md)
- [AccountDefsPlatformPropertiesId](docs/Model/AccountDefsPlatformPropertiesId.md)
- [BillingDefsCurrency](docs/Model/BillingDefsCurrency.md)
- [CapabilitiesOptions](docs/Model/CapabilitiesOptions.md)
- [CapabilitiesPhysicalProperties](docs/Model/CapabilitiesPhysicalProperties.md)
- [CapabilitiesPostCapabilitiesRequestV1](docs/Model/CapabilitiesPostCapabilitiesRequestV1.md)
- [CapabilitiesPostCapabilitiesRequestV1Data](docs/Model/CapabilitiesPostCapabilitiesRequestV1Data.md)
- [CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInner](docs/Model/CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInner.md)
- [CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions](docs/Model/CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions.md)
- [CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerRecipient](docs/Model/CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerRecipient.md)
- [CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerSender](docs/Model/CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerSender.md)
- [CapabilitiesPostCapabilitiesRequestV2](docs/Model/CapabilitiesPostCapabilitiesRequestV2.md)
- [CapabilitiesPostContractDefinitionsRequestV1](docs/Model/CapabilitiesPostContractDefinitionsRequestV1.md)
- [CapabilitiesPostContractDefinitionsRequestV1Data](docs/Model/CapabilitiesPostContractDefinitionsRequestV1Data.md)
- [CapabilitiesPostContractDefinitionsRequestV2](docs/Model/CapabilitiesPostContractDefinitionsRequestV2.md)
- [CapabilitiesRecipient](docs/Model/CapabilitiesRecipient.md)
- [CapabilitiesResponsesCapabilitiesV1](docs/Model/CapabilitiesResponsesCapabilitiesV1.md)
- [CapabilitiesResponsesCapabilitiesV1Data](docs/Model/CapabilitiesResponsesCapabilitiesV1Data.md)
- [CapabilitiesResponsesCapabilitiesV2](docs/Model/CapabilitiesResponsesCapabilitiesV2.md)
- [CapabilitiesResponsesContractDefinitionsV1](docs/Model/CapabilitiesResponsesContractDefinitionsV1.md)
- [CapabilitiesResponsesContractDefinitionsV1Data](docs/Model/CapabilitiesResponsesContractDefinitionsV1Data.md)
- [CapabilitiesResponsesContractDefinitionsV2](docs/Model/CapabilitiesResponsesContractDefinitionsV2.md)
- [CapabilitiesSender](docs/Model/CapabilitiesSender.md)
- [CommonDefsDownloadUrl](docs/Model/CommonDefsDownloadUrl.md)
- [CommonErrorSystem](docs/Model/CommonErrorSystem.md)
- [CommonErrorUser](docs/Model/CommonErrorUser.md)
- [CommonErrorUserCode](docs/Model/CommonErrorUserCode.md)
- [CommonErrorUserCodeAuth](docs/Model/CommonErrorUserCodeAuth.md)
- [CommonHttpStatusCode4xxClientError404NotFound](docs/Model/CommonHttpStatusCode4xxClientError404NotFound.md)
- [CommonHttpStatusCode4xxClientError415UnsupportedMediaType](docs/Model/CommonHttpStatusCode4xxClientError415UnsupportedMediaType.md)
- [CommonParametersBigids](docs/Model/CommonParametersBigids.md)
- [CommonParametersBoolean](docs/Model/CommonParametersBoolean.md)
- [CommonParametersFilterValidateBool](docs/Model/CommonParametersFilterValidateBool.md)
- [CommonParametersIds](docs/Model/CommonParametersIds.md)
- [CommonParametersOrder](docs/Model/CommonParametersOrder.md)
- [CommonResponsesDownloadUrl](docs/Model/CommonResponsesDownloadUrl.md)
- [CommonResponsesDownloadUrlData](docs/Model/CommonResponsesDownloadUrlData.md)
- [CommonResponsesSystemError](docs/Model/CommonResponsesSystemError.md)
- [CommonResponsesUserError](docs/Model/CommonResponsesUserError.md)
- [CommonResponsesUserErrorInvalidContentType](docs/Model/CommonResponsesUserErrorInvalidContentType.md)
- [CommonResponsesUserErrorInvalidContentTypeAllOfErrors](docs/Model/CommonResponsesUserErrorInvalidContentTypeAllOfErrors.md)
- [CommonResponsesUserErrorNotFound](docs/Model/CommonResponsesUserErrorNotFound.md)
- [CommonResponsesUserErrorNotFoundAllOfErrors](docs/Model/CommonResponsesUserErrorNotFoundAllOfErrors.md)
- [GetIndex200Response](docs/Model/GetIndex200Response.md)
- [InlineObject](docs/Model/InlineObject.md)
- [NotificationPostNotificationGroupRequest](docs/Model/NotificationPostNotificationGroupRequest.md)
- [NotificationPostNotificationGroupRequestOneOf](docs/Model/NotificationPostNotificationGroupRequestOneOf.md)
- [NotificationPostNotificationGroupRequestOneOf1](docs/Model/NotificationPostNotificationGroupRequestOneOf1.md)
- [NotificationPutNotificationTemplateRequest](docs/Model/NotificationPutNotificationTemplateRequest.md)
- [NotificationResponsesNotificationGroups](docs/Model/NotificationResponsesNotificationGroups.md)
- [NotificationResponsesNotificationTemplates](docs/Model/NotificationResponsesNotificationTemplates.md)
- [PhysicalPropertiesHeight](docs/Model/PhysicalPropertiesHeight.md)
- [PhysicalPropertiesLength](docs/Model/PhysicalPropertiesLength.md)
- [PhysicalPropertiesWeight](docs/Model/PhysicalPropertiesWeight.md)
- [PhysicalPropertiesWidth](docs/Model/PhysicalPropertiesWidth.md)
- [RatesPostRatesRequestV1](docs/Model/RatesPostRatesRequestV1.md)
- [RatesPostRatesRequestV1Data](docs/Model/RatesPostRatesRequestV1Data.md)
- [RatesPostRatesRequestV1DataRatesInner](docs/Model/RatesPostRatesRequestV1DataRatesInner.md)
- [RatesPostRatesRequestV1DataRatesInnerOptions](docs/Model/RatesPostRatesRequestV1DataRatesInnerOptions.md)
- [RatesPostRatesRequestV1DataRatesInnerOptionsCashOnDelivery](docs/Model/RatesPostRatesRequestV1DataRatesInnerOptionsCashOnDelivery.md)
- [RatesPostRatesRequestV1DataRatesInnerOptionsInsurance](docs/Model/RatesPostRatesRequestV1DataRatesInnerOptionsInsurance.md)
- [RatesPostRatesRequestV2](docs/Model/RatesPostRatesRequestV2.md)
- [RatesPostRatesRequestV2Options](docs/Model/RatesPostRatesRequestV2Options.md)
- [RatesPostRatesRequestV2OptionsInsurance](docs/Model/RatesPostRatesRequestV2OptionsInsurance.md)
- [RatesPostRatesRequestV2OptionsRequiresCashOnDelivery](docs/Model/RatesPostRatesRequestV2OptionsRequiresCashOnDelivery.md)
- [RatesResponsesRatesV1](docs/Model/RatesResponsesRatesV1.md)
- [RatesResponsesRatesV1Data](docs/Model/RatesResponsesRatesV1Data.md)
- [RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1](docs/Model/RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1.md)
- [RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2](docs/Model/RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsOptionV1](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsOptionV1.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsOptionV2](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsOptionV2.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1.md)
- [RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2](docs/Model/RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2.md)
- [RefCapabilitiesResponseCapabilityV1](docs/Model/RefCapabilitiesResponseCapabilityV1.md)
- [RefCapabilitiesResponseCapabilityV2](docs/Model/RefCapabilitiesResponseCapabilityV2.md)
- [RefCapabilitiesResponseCollo](docs/Model/RefCapabilitiesResponseCollo.md)
- [RefCapabilitiesResponseOptionsInsuranceOptionV1](docs/Model/RefCapabilitiesResponseOptionsInsuranceOptionV1.md)
- [RefCapabilitiesResponseOptionsInsuranceOptionV2](docs/Model/RefCapabilitiesResponseOptionsInsuranceOptionV2.md)
- [RefCapabilitiesResponseOptionsOptionV1](docs/Model/RefCapabilitiesResponseOptionsOptionV1.md)
- [RefCapabilitiesResponseOptionsOptionV2](docs/Model/RefCapabilitiesResponseOptionsOptionV2.md)
- [RefCapabilitiesResponseOptionsOptionsV1](docs/Model/RefCapabilitiesResponseOptionsOptionsV1.md)
- [RefCapabilitiesResponseOptionsOptionsV2](docs/Model/RefCapabilitiesResponseOptionsOptionsV2.md)
- [RefCapabilitiesResponsePhysicalPropertiesPhysicalProperties](docs/Model/RefCapabilitiesResponsePhysicalPropertiesPhysicalProperties.md)
- [RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty](docs/Model/RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty.md)
- [RefCapabilitiesSharedCarrier](docs/Model/RefCapabilitiesSharedCarrier.md)
- [RefCapabilitiesSharedCarrierV2](docs/Model/RefCapabilitiesSharedCarrierV2.md)
- [RefCapabilitiesSharedOptionsBaseOptionV1](docs/Model/RefCapabilitiesSharedOptionsBaseOptionV1.md)
- [RefCapabilitiesSharedOptionsBaseOptionV2](docs/Model/RefCapabilitiesSharedOptionsBaseOptionV2.md)
- [RefCapabilitiesSharedOptionsBaseOptionsV1](docs/Model/RefCapabilitiesSharedOptionsBaseOptionsV1.md)
- [RefCapabilitiesSharedOptionsBaseOptionsV2](docs/Model/RefCapabilitiesSharedOptionsBaseOptionsV2.md)
- [RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1](docs/Model/RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1.md)
- [RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2](docs/Model/RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2.md)
- [RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount](docs/Model/RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount.md)
- [RefNotificationNotificationTemplateType](docs/Model/RefNotificationNotificationTemplateType.md)
- [RefNotificationResponseNotificationGroup](docs/Model/RefNotificationResponseNotificationGroup.md)
- [RefNotificationResponseNotificationTemplate](docs/Model/RefNotificationResponseNotificationTemplate.md)
- [RefRatesResponseContract](docs/Model/RefRatesResponseContract.md)
- [RefRatesResponsePriceCompositionPriceCompositionV1](docs/Model/RefRatesResponsePriceCompositionPriceCompositionV1.md)
- [RefRatesResponsePriceCompositionPriceCompositionV2](docs/Model/RefRatesResponsePriceCompositionPriceCompositionV2.md)
- [RefRatesResponsePriceCompositionPriceV1](docs/Model/RefRatesResponsePriceCompositionPriceV1.md)
- [RefRatesResponsePriceCompositionPriceV2](docs/Model/RefRatesResponsePriceCompositionPriceV2.md)
- [RefRatesResponseRateV1](docs/Model/RefRatesResponseRateV1.md)
- [RefRatesResponseRateV2](docs/Model/RefRatesResponseRateV2.md)
- [RefShipmentAddressV2](docs/Model/RefShipmentAddressV2.md)
- [RefShipmentBpostBpostPugoType](docs/Model/RefShipmentBpostBpostPugoType.md)
- [RefShipmentCustomsDeclaration](docs/Model/RefShipmentCustomsDeclaration.md)
- [RefShipmentCustomsDeclarationContents](docs/Model/RefShipmentCustomsDeclarationContents.md)
- [RefShipmentCustomsDeclarationItem](docs/Model/RefShipmentCustomsDeclarationItem.md)
- [RefShipmentGeneralSettings](docs/Model/RefShipmentGeneralSettings.md)
- [RefShipmentGeneralSettingsTracktrace](docs/Model/RefShipmentGeneralSettingsTracktrace.md)
- [RefShipmentLabelPrintingPosition](docs/Model/RefShipmentLabelPrintingPosition.md)
- [RefShipmentLocationV2](docs/Model/RefShipmentLocationV2.md)
- [RefShipmentOptionsDeliveryTypeAll](docs/Model/RefShipmentOptionsDeliveryTypeAll.md)
- [RefShipmentOptionsDeliveryTypeDeliveryDate](docs/Model/RefShipmentOptionsDeliveryTypeDeliveryDate.md)
- [RefShipmentOptionsInsurance](docs/Model/RefShipmentOptionsInsurance.md)
- [RefShipmentOptionsInsuranceMax](docs/Model/RefShipmentOptionsInsuranceMax.md)
- [RefShipmentOptionsInsuranceMaxAllOfInsurance](docs/Model/RefShipmentOptionsInsuranceMaxAllOfInsurance.md)
- [RefShipmentOptionsLabelDescription](docs/Model/RefShipmentOptionsLabelDescription.md)
- [RefShipmentOptionsOptions](docs/Model/RefShipmentOptionsOptions.md)
- [RefShipmentOptionsOptionsReturns](docs/Model/RefShipmentOptionsOptionsReturns.md)
- [RefShipmentOptionsPackageTypeAll](docs/Model/RefShipmentOptionsPackageTypeAll.md)
- [RefShipmentOptionsPackageTypeReturns](docs/Model/RefShipmentOptionsPackageTypeReturns.md)
- [RefShipmentOptionsPackageTypeReturnsPackageType](docs/Model/RefShipmentOptionsPackageTypeReturnsPackageType.md)
- [RefShipmentPackageType](docs/Model/RefShipmentPackageType.md)
- [RefShipmentPackageType1Package](docs/Model/RefShipmentPackageType1Package.md)
- [RefShipmentPackageType2Mailbox](docs/Model/RefShipmentPackageType2Mailbox.md)
- [RefShipmentPackageType6SmallPackage](docs/Model/RefShipmentPackageType6SmallPackage.md)
- [RefShipmentPackageTypeV2](docs/Model/RefShipmentPackageTypeV2.md)
- [RefShipmentPickup](docs/Model/RefShipmentPickup.md)
- [RefShipmentPickupV2](docs/Model/RefShipmentPickupV2.md)
- [RefShipmentReferenceIdentifier](docs/Model/RefShipmentReferenceIdentifier.md)
- [RefShipmentSender](docs/Model/RefShipmentSender.md)
- [RefShipmentSenderEmail](docs/Model/RefShipmentSenderEmail.md)
- [RefShipmentShipmentOptions](docs/Model/RefShipmentShipmentOptions.md)
- [RefShipmentShipmentOptionsReturns](docs/Model/RefShipmentShipmentOptionsReturns.md)
- [RefShipmentShipmentOptionsReturnsOptions](docs/Model/RefShipmentShipmentOptionsReturnsOptions.md)
- [RefShipmentShipmentOptionsReturnsOptionsAllOfContribution](docs/Model/RefShipmentShipmentOptionsReturnsOptionsAllOfContribution.md)
- [RefShipmentTransactionStatus](docs/Model/RefShipmentTransactionStatus.md)
- [RefShipmentType](docs/Model/RefShipmentType.md)
- [RefTypesCarrier](docs/Model/RefTypesCarrier.md)
- [RefTypesCarrierReturns](docs/Model/RefTypesCarrierReturns.md)
- [RefTypesCarrierV2](docs/Model/RefTypesCarrierV2.md)
- [RefTypesDeliveryType](docs/Model/RefTypesDeliveryType.md)
- [RefTypesDeliveryTypeV2](docs/Model/RefTypesDeliveryTypeV2.md)
- [RefTypesIntBoolean](docs/Model/RefTypesIntBoolean.md)
- [RefTypesMoney](docs/Model/RefTypesMoney.md)
- [RefTypesMoneyAmount](docs/Model/RefTypesMoneyAmount.md)
- [RefTypesPriceEuro](docs/Model/RefTypesPriceEuro.md)
- [RefTypesTransactionTypes](docs/Model/RefTypesTransactionTypes.md)
- [RefTypesValueWithUnit](docs/Model/RefTypesValueWithUnit.md)
- [SecondaryShipmentResource](docs/Model/SecondaryShipmentResource.md)
- [ShipmentDefsExternalProviderPropertiesDisplayName](docs/Model/ShipmentDefsExternalProviderPropertiesDisplayName.md)
- [ShipmentDefsShipment](docs/Model/ShipmentDefsShipment.md)
- [ShipmentDefsShipmentPartnerTracktracesInner](docs/Model/ShipmentDefsShipmentPartnerTracktracesInner.md)
- [ShipmentDefsShipmentPaymentStatus](docs/Model/ShipmentDefsShipmentPaymentStatus.md)
- [ShipmentDefsShipmentPropertiesCollectionContact](docs/Model/ShipmentDefsShipmentPropertiesCollectionContact.md)
- [ShipmentDefsShipmentPropertiesCurrency](docs/Model/ShipmentDefsShipmentPropertiesCurrency.md)
- [ShipmentDefsShipmentPropertiesCustomsDeclaration](docs/Model/ShipmentDefsShipmentPropertiesCustomsDeclaration.md)
- [ShipmentDefsShipmentPropertiesExternalProvider](docs/Model/ShipmentDefsShipmentPropertiesExternalProvider.md)
- [ShipmentDefsShipmentPropertiesLinkConsumerPortal](docs/Model/ShipmentDefsShipmentPropertiesLinkConsumerPortal.md)
- [ShipmentDefsShipmentPropertiesOptions](docs/Model/ShipmentDefsShipmentPropertiesOptions.md)
- [ShipmentDefsShipmentPropertiesRecipient](docs/Model/ShipmentDefsShipmentPropertiesRecipient.md)
- [ShipmentDefsShipmentPropertiesRegion](docs/Model/ShipmentDefsShipmentPropertiesRegion.md)
- [ShipmentDefsShipmentRecipient](docs/Model/ShipmentDefsShipmentRecipient.md)
- [ShipmentDefsShipmentSender](docs/Model/ShipmentDefsShipmentSender.md)
- [ShipmentDefsShipmentSenderPropertiesEmail](docs/Model/ShipmentDefsShipmentSenderPropertiesEmail.md)
- [ShipmentDefsShipmentStatus](docs/Model/ShipmentDefsShipmentStatus.md)
- [ShipmentDefsShipmentStatusBasic](docs/Model/ShipmentDefsShipmentStatusBasic.md)
- [ShipmentParametersLabelPosition](docs/Model/ShipmentParametersLabelPosition.md)
- [ShipmentParametersPackageType](docs/Model/ShipmentParametersPackageType.md)
- [ShipmentParametersPaperSize](docs/Model/ShipmentParametersPaperSize.md)
- [ShipmentParametersShipmentType](docs/Model/ShipmentParametersShipmentType.md)
- [ShipmentParametersSortShipment](docs/Model/ShipmentParametersSortShipment.md)
- [ShipmentParametersStatus](docs/Model/ShipmentParametersStatus.md)
- [ShipmentPostReturnShipmentsRequest](docs/Model/ShipmentPostReturnShipmentsRequest.md)
- [ShipmentPostReturnShipmentsRequestData](docs/Model/ShipmentPostReturnShipmentsRequestData.md)
- [ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner](docs/Model/ShipmentPostReturnShipmentsRequestDataReturnShipmentsInner.md)
- [ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfGeneralSettings](docs/Model/ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfGeneralSettings.md)
- [ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfPhysicalProperties](docs/Model/ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfPhysicalProperties.md)
- [ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfSender](docs/Model/ShipmentPostReturnShipmentsRequestDataReturnShipmentsInnerAllOfSender.md)
- [ShipmentPostShipmentsRequest](docs/Model/ShipmentPostShipmentsRequest.md)
- [ShipmentPostShipmentsRequestData](docs/Model/ShipmentPostShipmentsRequestData.md)
- [ShipmentPostShipmentsRequestDataShipmentsInner](docs/Model/ShipmentPostShipmentsRequestDataShipmentsInner.md)
- [ShipmentPostShipmentsRequestDataShipmentsInnerMultiColli](docs/Model/ShipmentPostShipmentsRequestDataShipmentsInnerMultiColli.md)
- [ShipmentPostShipmentsRequestDataShipmentsInnerRecipient](docs/Model/ShipmentPostShipmentsRequestDataShipmentsInnerRecipient.md)
- [ShipmentPostShipmentsRequestV11](docs/Model/ShipmentPostShipmentsRequestV11.md)
- [ShipmentPostShipmentsRequestV11Data](docs/Model/ShipmentPostShipmentsRequestV11Data.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContact.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContactNumber](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerCollectionContactNumber.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerDropOffPoint.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerGeneralSettings.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerPhysicalProperties.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipient.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipientEmail](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerRecipientEmail.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerReferenceIdentifier](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerReferenceIdentifier.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerGeneralSettings](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerGeneralSettings.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerRecipient](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerRecipient.md)
- [ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner](docs/Model/ShipmentPostShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerShippedItemsInner.md)
- [ShipmentPostUnrelatedReturnShipmentsRequest](docs/Model/ShipmentPostUnrelatedReturnShipmentsRequest.md)
- [ShipmentPostUnrelatedReturnShipmentsRequestData](docs/Model/ShipmentPostUnrelatedReturnShipmentsRequestData.md)
- [ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner](docs/Model/ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsInner.md)
- [ShipmentPutShipmentsRequest](docs/Model/ShipmentPutShipmentsRequest.md)
- [ShipmentPutShipmentsRequestData](docs/Model/ShipmentPutShipmentsRequestData.md)
- [ShipmentPutShipmentsRequestDataShipmentsInner](docs/Model/ShipmentPutShipmentsRequestDataShipmentsInner.md)
- [ShipmentPutShipmentsRequestDataShipmentsInnerRecipient](docs/Model/ShipmentPutShipmentsRequestDataShipmentsInnerRecipient.md)
- [ShipmentPutShipmentsRequestV11](docs/Model/ShipmentPutShipmentsRequestV11.md)
- [ShipmentPutShipmentsRequestV11Data](docs/Model/ShipmentPutShipmentsRequestV11Data.md)
- [ShipmentPutShipmentsRequestV11DataShipmentsInner](docs/Model/ShipmentPutShipmentsRequestV11DataShipmentsInner.md)
- [ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact](docs/Model/ShipmentPutShipmentsRequestV11DataShipmentsInnerCollectionContact.md)
- [ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient](docs/Model/ShipmentPutShipmentsRequestV11DataShipmentsInnerRecipient.md)
- [ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner](docs/Model/ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInner.md)
- [ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerRecipient](docs/Model/ShipmentPutShipmentsRequestV11DataShipmentsInnerSecondaryShipmentsInnerRecipient.md)
- [ShipmentRequest](docs/Model/ShipmentRequest.md)
- [ShipmentResponsesShipmentIds](docs/Model/ShipmentResponsesShipmentIds.md)
- [ShipmentResponsesShipmentIdsData](docs/Model/ShipmentResponsesShipmentIdsData.md)
- [ShipmentResponsesShipmentIdsDataIdsInner](docs/Model/ShipmentResponsesShipmentIdsDataIdsInner.md)
- [ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsInner](docs/Model/ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsInner.md)
- [ShipmentResponsesShipmentLabels](docs/Model/ShipmentResponsesShipmentLabels.md)
- [ShipmentResponsesShipmentLabelsData](docs/Model/ShipmentResponsesShipmentLabelsData.md)
- [ShipmentResponsesShipmentLabelsDataOneOf](docs/Model/ShipmentResponsesShipmentLabelsDataOneOf.md)
- [ShipmentResponsesShipmentLabelsDataOneOf1](docs/Model/ShipmentResponsesShipmentLabelsDataOneOf1.md)
- [ShipmentResponsesShipmentLabelsDataOneOf1Zpl](docs/Model/ShipmentResponsesShipmentLabelsDataOneOf1Zpl.md)
- [ShipmentResponsesShipmentLabelsDataOneOfPdf](docs/Model/ShipmentResponsesShipmentLabelsDataOneOfPdf.md)
- [ShipmentResponsesShipments](docs/Model/ShipmentResponsesShipments.md)
- [ShipmentResponsesShipmentsData](docs/Model/ShipmentResponsesShipmentsData.md)

## Authorization

Authentication schemes defined for the API:
### bearer

- **Type**: Bearer authentication

### apiKey

- **Type**: HTTP basic authentication

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

- API version: `2025-02-13`
    - Generator version: `7.20.0-SNAPSHOT`
- Build package: `org.openapitools.codegen.languages.PhpClientCodegen`
