<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ValidatorTrait;
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
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeAll::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsDeliveryTypeDeliveryDate::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsInsuranceMax::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsInsuranceMaxInsurance::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsLabelDescription::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentOptionsPackageTypeAll::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShipmentShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesCriteria::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesCriteriaNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplications::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesImplicationsBase::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsBaseNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShipmentOptions::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShipmentOptionsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefShippingRulesShippingRule::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShippingRuleNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesMoney::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesMoneyNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\RefTypesPriceEuro::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesPriceEuroNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonErrorSystem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorSystemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonErrorUser::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorUserNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesSystemError::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesSystemErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\CommonResponsesUserError::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesUserErrorNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRules::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemCriteria::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemCriteriaNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplications::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRules::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRulesData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRulesDataShippingRulesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRulesDataShippingRulesItemCriteria::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemCriteriaNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRulesDataShippingRulesItemImplications::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRules::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRulesData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRulesDataShippingRulesItem::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRulesDataShippingRulesItemCriteria::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemCriteriaNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRulesDataShippingRulesItemImplications::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsPhysicalProperties::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesImplications::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesImplicationsData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRules::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesDataNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginated::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedNormalizer::class,
        
        \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Model\ShippingRulesResponsesShippingRulesPaginatedData::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedDataNormalizer::class,
        
        \Jane\Component\JsonSchemaRuntime\Reference::class => \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ReferenceNormalizer::class,
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
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeAllNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsDeliveryTypeDeliveryDateNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsInsuranceMaxInsuranceNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsLabelDescriptionNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentOptionsPackageTypeAllNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShipmentShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesCriteriaNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesCriteriaNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsBaseNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesImplicationsBaseNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShipmentOptionsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShipmentOptionsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShippingRuleNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefShippingRulesShippingRuleNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesMoneyNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesMoneyNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesPriceEuroNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\RefTypesPriceEuroNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorSystemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorSystemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorUserNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonErrorUserNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesSystemErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesSystemErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesUserErrorNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\CommonResponsesUserErrorNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemCriteriaNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemCriteriaNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPatchShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemCriteriaNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemCriteriaNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPostShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemCriteriaNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemCriteriaNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesPutShippingRulesDataShippingRulesItemImplicationsPhysicalPropertiesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesImplicationsDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedDataNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Normalizer\ShippingRulesResponsesShippingRulesPaginatedDataNormalizer(),
            \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ReferenceNormalizer::class => new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApiPrivate\Runtime\Normalizer\ReferenceNormalizer(),
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