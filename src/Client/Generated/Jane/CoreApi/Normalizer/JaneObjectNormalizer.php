<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidatorTrait;
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
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFound::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotFoundErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserError::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonErrorUser::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorUserNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPutShipmentsV12Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipment::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSecondaryShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSecondaryShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsInsuranceMax::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsInsuranceMaxInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesPriceEuro::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesPriceEuroNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoney::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsPackageTypeAll::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsDeliveryTypeAll::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsDeliveryTypeDeliveryDate::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsLabelDescription::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentGeneralSettingsTracktrace::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsTracktraceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPickup::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPickupNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclaration::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentCustomsDeclarationItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesMoneyNullableAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNullableAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPropertiesRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPropertiesOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\AccountDefsContact::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\AccountDefsContactNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentPartnerTracktracesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPartnerTracktracesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentShippedItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLink::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadLinkDefinitionsUrl::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkDefinitionsUrlNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequired::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequiredData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataShipments::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataShipmentsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsShipmentDataItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsShipmentDataItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPaymentInstructions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPaymentInstructionsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\BillingDefsPaymentDataInvoices::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataInvoicesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsPrice::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsPriceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorBadRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorBadRequestErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesProblemErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV2Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDateTime::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDateTimeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibility::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemSchema::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemSchemaNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryTimeFrame::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsDeliveryTimeFrameNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsV1ErrorsItemAnyOf::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1ErrorsItemAnyOfNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsDeliveryTime::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryTimeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickup::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupTime::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupTimeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsOpeningHoursNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\Error::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotAcceptable::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorNotAcceptableErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadUrl::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesDownloadUrlData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonDefsDownloadUrl::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDownloadUrlNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoints::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPointsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsOccupancy::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsOccupancyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPointDefinitionsOpeningHours::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsOpeningHoursNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDropOffPointDefinitionsTimeframe::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsTimeframeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorInvalidContentType::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorInvalidContentTypeErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorPermissionDenied::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesUserErrorPermissionDeniedErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocations::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsPickupLocation::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPickupLocationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsAddress::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsAddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemMoment::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemMomentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsLocation::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsLocationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsOpeningHours::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsOpeningHoursNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPickupLocationsDefinitionsTimeframe::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsTimeframeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12DataPdf::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataPdfNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesPostShipmentsV12DataZpl::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataZplNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIds::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIdsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIdsDataIdsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataIdsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentLabels::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentLabelsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonResponsesSystemError::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesSystemErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CommonErrorSystem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorSystemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipments::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktraces::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesTracktracesData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTrace::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackTraceStatus::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceStatusNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsExternalTrackTraceLink::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsExternalTrackTraceLinkNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsTrackingLocation::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackingLocationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsCarrierTrackTraceEvent::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsCarrierTrackTraceEventNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryMomentPropertiesTimeFrame::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryMomentPropertiesTimeFrameNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedContractV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalProperty::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefTypesValueWithUnit::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesValueWithUnitNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCollo::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseColloNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedContractV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsBaseOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseOptionsInsuranceOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesContractDefinitionsV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseContractV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesResponsesRatesV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesResponsesRatesV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseRateV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponseContract::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceCompositionV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefRatesResponsePriceCompositionPriceV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV10::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV10Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookV10::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV10Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptionsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptionsDataIdsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataIdsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContact::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickup::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItemMultiColli::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemMultiColliNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptionsReturns::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptionsReturnsOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentShipmentOptionsReturnsOptionsContribution::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsContributionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsPackageTypeReturns::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeReturnsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentOptionsOptionsReturns::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsReturnsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContact::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestDataShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestDataShipmentsItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestDataShipmentsItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestDataShipmentsItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettings::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequest::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPatchShipmentsRequestDataShipmentsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataShipmentsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Recipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2RecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Sender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2SenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2OptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Pickup::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2PickupLocation::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupLocationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickup::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickupNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostContractDefinitionsRequestV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2Recipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2RecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2Sender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2SenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2PhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2PhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2PhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2PhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2PhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2Options::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV2OptionsRequiresCashOnDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsRequiresCashOnDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentPickupV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentAddressV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentAddressV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefShipmentLocationV2::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentLocationV2Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RatesPostRatesRequestV1DataRatesItemOptionsCashOnDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsCashOnDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefWebhookWebhookV11::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV11Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV10::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV10Data::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10DataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefWebhookWebhookV10::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV10Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\GetResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\GetResponse200Normalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ReferenceNormalizer::class,
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
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotFoundErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorUserNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorUserNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPutShipmentsV12DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSecondaryShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSecondaryShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesPriceEuroNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesPriceEuroNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsTracktraceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentGeneralSettingsTracktraceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPickupNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPickupNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentCustomsDeclarationItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNullableAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesMoneyNullableAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPropertiesOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\AccountDefsContactNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\AccountDefsContactNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPartnerTracktracesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentPartnerTracktracesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentShippedItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsShipmentShippedItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkDefinitionsUrlNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadLinkDefinitionsUrlNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataShipmentsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesBulkShipmentLabelsPaymentRequiredDataShipmentsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsShipmentDataItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsShipmentDataItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPaymentInstructionsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataInvoicesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\BillingDefsPaymentDataInvoicesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsPriceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsPriceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorBadRequestErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesProblemErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV2DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDateTimeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDateTimeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemSchemaNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryPossibilityShipmentOptionsItemSchemaNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsDeliveryTimeFrameNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsDeliveryTimeFrameNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1ErrorsItemAnyOfNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsV1ErrorsItemAnyOfNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryTimeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDeliveryOptionsDeliveryTimeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupTimeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsPickupTimeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsOpeningHoursNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryOptionsOpeningHoursNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorNotAcceptableErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesDownloadUrlDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDownloadUrlNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonDefsDownloadUrlNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsOccupancyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsOccupancyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsOpeningHoursNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsOpeningHoursNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsTimeframeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesDropOffPointDefinitionsTimeframeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorInvalidContentTypeErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesUserErrorPermissionDeniedErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPickupLocationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPickupLocationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsAddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsAddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemMomentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsPossibilitiesItemMomentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsLocationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsLocationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsOpeningHoursNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsOpeningHoursNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsTimeframeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPickupLocationsDefinitionsTimeframeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataPdfNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataPdfNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataZplNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesPostShipmentsV12DataZplNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataIdsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsDataIdsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentLabelsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentLabelsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentIdsPropertiesDataPropertiesIdsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesSystemErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonResponsesSystemErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorSystemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CommonErrorSystemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesShipmentsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentResponsesTracktracesDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceStatusNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackTraceStatusNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsExternalTrackTraceLinkNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsExternalTrackTraceLinkNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackingLocationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsTrackingLocationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsCarrierTrackTraceEventNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsCarrierTrackTraceEventNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryMomentPropertiesTimeFrameNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentDefsDeliveryMomentPropertiesTimeFrameNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV2InsuredAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesValueWithUnitNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefTypesValueWithUnitNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseColloNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseColloNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesCapabilitiesV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseCapabilityV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedContractV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsBaseOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponseOptionsInsuranceOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesSharedOptionsInsuranceBaseInsuranceV1InsuredAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesResponsePhysicalPropertiesPhysicalPropertiesV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesResponsesContractDefinitionsV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseContractDefinitionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionsV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefCapabilitiesContractDefinitionsResponseOptionsInsuranceOptionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesResponsesRatesV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseRateV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponseContractNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceCompositionV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefRatesResponsePriceCompositionPriceV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV11DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookSubscriptionsV10DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV10Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesWebhookV10Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataIdsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksResponsesPostWebhookSubscriptionsDataIdsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemMultiColliNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemMultiColliNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsContributionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentShipmentOptionsReturnsOptionsContributionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeReturnsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsPackageTypeReturnsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsReturnsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentOptionsOptionsReturnsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPostUnrelatedReturnShipmentsRequestDataReturnShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemDropOffPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemSecondaryShipmentsItemShippedItemsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemCollectionContactNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestV11DataShipmentsItemShippedItemsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPutShipmentsRequestDataShipmentsItemGeneralSettingsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataShipmentsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\ShipmentPatchShipmentsRequestDataShipmentsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2RecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2RecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2SenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2SenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2OptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2OptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupLocationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV2PickupLocationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickupNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesItemPickupNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\CapabilitiesPostContractDefinitionsRequestV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2RecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2RecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2SenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2SenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2PhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsRequiresCashOnDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV2OptionsRequiresCashOnDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentPickupV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentAddressV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentAddressV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentLocationV2Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefShipmentLocationV2Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemPhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsCashOnDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RatesPostRatesRequestV1DataRatesItemOptionsCashOnDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV11DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV11Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV11Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10DataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\WebhooksPostWebhookSubscriptionsRequestV10DataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV10Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\RefWebhookWebhookV10Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\GetResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer\GetResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ReferenceNormalizer(),
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