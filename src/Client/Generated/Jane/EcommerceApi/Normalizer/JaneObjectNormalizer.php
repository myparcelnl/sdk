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
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Address::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\BillingDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Dimension::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalPropertiesHeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesHeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalPropertiesLength::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesLengthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalPropertiesWeight::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\PhysicalPropertiesWidth::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWidthNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Money::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\SalesPrice::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLineProduct::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLineProductDescriptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderLine::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsInsuranceAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsReturnContributionFee::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsReturnContributionFeeAmount::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeAmountNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ShipmentOptionsCustomLabelText::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Order::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderBillingDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderBillingDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipping::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDelivery::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDeliveryOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingDeliveryOptionsPreferredDate::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShippingRecipient::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProductChild::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\Product::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProductDescriptionsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsOrder::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsOrderLine::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsShipment::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\CommandEnvelope::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandEnvelopeNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\OrderShipmentsCommand::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetails::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntax::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInvalidRequestSyntaxErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsClient::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsClientErrorsItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsInternalServerError::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ProblemDetailsNotFound::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostBody::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse401::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectTokenPostResponse500::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse500Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse200::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse401::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model\ConnectRefreshPostResponse500::class => \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse500Normalizer::class,
        
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
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\AddressNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\BillingDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\DimensionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesHeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesHeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesLengthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesLengthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWeightNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWidthNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\PhysicalPropertiesWidthNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\MoneyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\SalesPriceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineProductDescriptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderLineNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsInsuranceAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeAmountNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsReturnContributionFeeAmountNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ShipmentOptionsCustomLabelTextNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderBillingDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderBillingDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingDeliveryOptionsPreferredDateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShippingRecipientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductChildNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProductDescriptionsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsOrderLineNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsShipmentNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandEnvelopeNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\CommandEnvelopeNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\OrderShipmentsCommandNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInvalidRequestSyntaxErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsClientErrorsItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsInternalServerErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ProblemDetailsNotFoundNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostBodyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse401Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse500Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectTokenPostResponse500Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse200Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse401Normalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse500Normalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Normalizer\ConnectRefreshPostResponse500Normalizer(),
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