<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class JaneObjectNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    protected $normalizers = [
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Address::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclaration::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItemTotalValue::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalValueNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationGroupsItemTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponse::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseGroupsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseGroupsItemTotalValue::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalValueNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseGroupsItemTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CustomsDeclarationResponseTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\DeliveryOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\DeliveryOptionsPreferredDate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsPreferredDateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Delivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\DropOff::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DropOffNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Money::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\MoneyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeightRequired::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeightRequiredHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeightRequiredLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeightRequiredWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PhysicalPropertiesWeightRequiredWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommon::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonInsuranceAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonReturnContributionFee::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsCommonReturnContributionFeeAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsPackage::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptionsPackageCustomLabelText::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageCustomLabelTextNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactBusiness::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactBusinessNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactPrivate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactPrivateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailBusiness::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailBusinessNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ContactMultiEmailPrivate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailPrivateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\BillingDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\BillingDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferenceEcommercePlatform::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceEcommercePlatformNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferenceSalesChannel::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceSalesChannelNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesSalesChannel::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesSalesChannelNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesMyParcelShop::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesMyParcelShopNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ExternalReferencesImport::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesImportNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LineExternalReference::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineExternalReferenceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithBarcode::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithBarcodeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LabelWithoutBarcode::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithoutBarcodeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromImported::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromImportedNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\NoteFromPrincipal::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromPrincipalNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequestRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRequestSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipment::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentMulticollo::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentMulticolloNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShipmentSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Line::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LineProduct::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineProductNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\LinePrice::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LinePriceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageCommon::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageCommonNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageRequestLinesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestLinesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageResponse::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PackageResponseLinesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseLinesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippablePackage::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippablePackageShipment::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippablePackageShipmentRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippablePackageShipmentSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingCustomsDeclarationResponse::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingCustomsDeclarationResponseGroupsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingCustomsDeclarationResponseGroupsItemTotalValue::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalValueNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingCustomsDeclarationResponseGroupsItemTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingCustomsDeclarationResponseTotalWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseTotalWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Shipping::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ShippingRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AddPackages::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddPackagesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\PreparePackagesForShipmentPackage::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PreparePackagesForShipmentPackageNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\Order::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRules::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderRulesSetShippingPreferences::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesSetShippingPreferencesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateDescriptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateDescriptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateFulfilment::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateFulfilmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreatePhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProductToCreateSalesPrice::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateSalesPriceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderLineToCreate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderLineToCreateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreate::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateBillingDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateBillingDetailsAddress::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsAddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShipping::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptionsInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptionsInsuranceAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptionsReturnContributionFee::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptionsReturnContributionFeeAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingOptionsCustomLabelText::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsCustomLabelTextNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrderToCreateShippingRecipientAddress::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientAddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsClient::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsClientErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInternalServerError::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntax::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ProblemDetailsInvalidRequestSyntaxErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AddNotePostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddNotePostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\AssignToUserPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AssignToUserPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CancelPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CancelPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\CreatePostResponse202::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CreatePostResponse202Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\UnpreparePackagesForShipmentPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnpreparePackagesForShipmentPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EditNotePostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EditNotePostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\ImportPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ImportPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200Total::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200TotalNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200Aggregations::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\OrdersGetResponse200AggregationsStatusItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsStatusItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\RemoveNotesPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\RemoveNotesPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\UnassignFromUserPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnassignFromUserPostBodyItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model\EvaluateRulesPostBodyItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EvaluateRulesPostBodyItemNormalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ReferenceNormalizer::class,
    ], $normalizersCache = [];
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return array_key_exists($type, $this->normalizers);
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && array_key_exists(get_class($data), $this->normalizers);
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $normalizerClass = $this->normalizers[get_class($data)];
        $normalizer = $this->getNormalizer($normalizerClass);
        return $normalizer->normalize($data, $format, $context);
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $denormalizerClass = $this->normalizers[$type];
        $denormalizer = $this->getNormalizer($denormalizerClass);
        return $denormalizer->denormalize($data, $type, $format, $context);
    }
    private function getNormalizer(string $normalizerClass)
    {
        return $this->normalizersCache[$normalizerClass] ?? $this->initNormalizer($normalizerClass);
    }
    private function initNormalizer(string $normalizerClass)
    {
        $normalizer = match ($normalizerClass) {
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalValueNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalValueNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationGroupsItemTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalValueNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalValueNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseGroupsItemTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CustomsDeclarationResponseTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsPreferredDateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryOptionsPreferredDateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DropOffNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\DropOffNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\MoneyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\MoneyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PhysicalPropertiesWeightRequiredWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonInsuranceAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsCommonReturnContributionFeeAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageCustomLabelTextNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsPackageCustomLabelTextNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactBusinessNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactBusinessNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactPrivateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactPrivateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailBusinessNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailBusinessNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailPrivateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ContactMultiEmailPrivateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\BillingDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\BillingDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceEcommercePlatformNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceEcommercePlatformNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceSalesChannelNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferenceSalesChannelNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesSalesChannelNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesSalesChannelNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesMyParcelShopNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesMyParcelShopNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesImportNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ExternalReferencesImportNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineExternalReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineExternalReferenceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithBarcodeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithBarcodeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithoutBarcodeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LabelWithoutBarcodeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromImportedNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromImportedNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromPrincipalNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\NoteFromPrincipalNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRequestSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentMulticolloNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentMulticolloNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShipmentSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineProductNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LineProductNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LinePriceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\LinePriceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageCommonNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageCommonNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestLinesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageRequestLinesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseLinesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PackageResponseLinesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippablePackageShipmentSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalValueNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalValueNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseGroupsItemTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseTotalWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingCustomsDeclarationResponseTotalWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ShippingRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddPackagesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddPackagesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PreparePackagesForShipmentPackageNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\PreparePackagesForShipmentPackageNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesSetShippingPreferencesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderRulesSetShippingPreferencesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateDescriptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateDescriptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateFulfilmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateFulfilmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreatePhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateSalesPriceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProductToCreateSalesPriceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderLineToCreateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderLineToCreateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsAddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateBillingDetailsAddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsInsuranceAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsReturnContributionFeeAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsCustomLabelTextNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingOptionsCustomLabelTextNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientAddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrderToCreateShippingRecipientAddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddNotePostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AddNotePostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AssignToUserPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\AssignToUserPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CancelPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CancelPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CreatePostResponse202Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\CreatePostResponse202Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnpreparePackagesForShipmentPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnpreparePackagesForShipmentPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EditNotePostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EditNotePostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ImportPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\ImportPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200TotalNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200TotalNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsStatusItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\OrdersGetResponse200AggregationsStatusItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\RemoveNotesPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\RemoveNotesPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnassignFromUserPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\UnassignFromUserPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EvaluateRulesPostBodyItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Normalizer\EvaluateRulesPostBodyItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\Normalizer\ReferenceNormalizer(),
            default => throw new \InvalidArgumentException('Unknown normalizer class: ' . $normalizerClass),
        };
        if ($normalizer instanceof \Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface) {
            $normalizer->setNormalizer($this->normalizer);
        }
        if ($normalizer instanceof \Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface) {
            $normalizer->setDenormalizer($this->denormalizer);
        }
        $this->normalizersCache[$normalizerClass] = $normalizer;
        return $normalizer;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return array_combine(array_keys($this->normalizers), array_fill(0, count($this->normalizers), false));
    }
}