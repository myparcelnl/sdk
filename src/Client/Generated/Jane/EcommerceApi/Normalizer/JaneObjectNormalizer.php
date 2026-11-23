<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ValidatorTrait;
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
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectStartConfig::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectStartConfigNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Money::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Address::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\BillingDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLineProduct::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLineProductDescriptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLine::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsAdditionalInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsAdditionalInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsDeliverAtPostalPoint::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsDeliverAtPostalPointNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsFreshFood::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFreshFoodNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsFrozen::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFrozenNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsHideSender::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsHideSenderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsNoTracking::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNoTrackingNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsOversizedPackage::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsOversizedPackageNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsPrintReturnLabelAtDropOff::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPrintReturnLabelAtDropOffNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsPriorityDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPriorityDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsRecipientOnlyDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRecipientOnlyDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsRequiresAgeVerification::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresAgeVerificationNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsRequiresCashOnDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresCashOnDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsRequiresReceiptCode::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresReceiptCodeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsRequiresSignature::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresSignatureNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsReturnContributionFee::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsReturnOnFirstFailedDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnOnFirstFailedDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsSameDayDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSameDayDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsSaturdayDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSaturdayDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsScheduledCollection::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsScheduledCollectionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsCustomLabelText::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipping::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDeliveryOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDeliveryOptionsNotifyOnDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNotifyOnDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDeliveryOptionsPreferredDate::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Dimension::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\SalesPrice::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProductChild::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Product::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProductDescriptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Command::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsCommand::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsOrder::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsShipment::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsOrderLine::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsClient::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsClientErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntax::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntaxErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsNotFound::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsUnsupportedMediaType::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsUnsupportedMediaTypeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\UnauthorizedResponse::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\UnauthorizedResponseNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostBody::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostJsonResponse500::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostJsonResponse500Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse401::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostJsonResponse500::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostJsonResponse500Normalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ReferenceNormalizer::class,
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
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectStartConfigNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectStartConfigNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsAdditionalInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsAdditionalInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsDeliverAtPostalPointNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsDeliverAtPostalPointNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFreshFoodNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFreshFoodNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFrozenNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsFrozenNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsHideSenderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsHideSenderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNoTrackingNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNoTrackingNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsOversizedPackageNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsOversizedPackageNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPrintReturnLabelAtDropOffNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPrintReturnLabelAtDropOffNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPriorityDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsPriorityDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRecipientOnlyDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRecipientOnlyDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresAgeVerificationNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresAgeVerificationNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresCashOnDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresCashOnDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresReceiptCodeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresReceiptCodeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresSignatureNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsRequiresSignatureNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnOnFirstFailedDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnOnFirstFailedDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSameDayDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSameDayDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSaturdayDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsSaturdayDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsScheduledCollectionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsScheduledCollectionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNotifyOnDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNotifyOnDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsUnsupportedMediaTypeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsUnsupportedMediaTypeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\UnauthorizedResponseNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\UnauthorizedResponseNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostJsonResponse500Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostJsonResponse500Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostJsonResponse500Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostJsonResponse500Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\Normalizer\ReferenceNormalizer(),
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