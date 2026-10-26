<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Normalizer;

use Jane\Component\JsonSchemaRuntime\Reference;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\CheckArray;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\ValidatorTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
class ShipmentDefsShipmentShippedItemNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem();
        if (null === $data || false === \is_array($data)) {
            return $object;
        }
        if (isset($data['$ref']) && !isset($data['type']) && !isset($data['properties']) && !isset($data['allOf'])) {
            return new Reference($data['$ref'], $context['document-origin']);
        }
        if (isset($data['$recursiveRef'])) {
            return new Reference($data['$recursiveRef'], $context['document-origin']);
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentShippedItemConstraint());
        }
        if (\array_key_exists('quantity', $data) && $data['quantity'] !== null) {
            $value = $data['quantity'];
            if (is_null($data['quantity'])) {
                $value = $data['quantity'];
            } elseif (is_int($data['quantity'])) {
                $value = $data['quantity'];
            }
            $object->quantity = $value;
        }
        elseif (\array_key_exists('quantity', $data)) {
            $object->quantity = null;
        }
        if (\array_key_exists('order_line_identifier', $data) && $data['order_line_identifier'] !== null) {
            $value_1 = $data['order_line_identifier'];
            if (is_null($data['order_line_identifier'])) {
                $value_1 = $data['order_line_identifier'];
            } elseif (is_string($data['order_line_identifier'])) {
                $value_1 = $data['order_line_identifier'];
            }
            $object->orderLineIdentifier = $value_1;
        }
        elseif (\array_key_exists('order_line_identifier', $data)) {
            $object->orderLineIdentifier = null;
        }
        if (\array_key_exists('order_line', $data) && $data['order_line'] !== null) {
            $value_2 = $data['order_line'];
            if (is_null($data['order_line'])) {
                $value_2 = $data['order_line'];
            } else {
                $value_2 = $data['order_line'];
            }
            $object->orderLine = $value_2;
        }
        elseif (\array_key_exists('order_line', $data)) {
            $object->orderLine = null;
        }
        if (\array_key_exists('picklist_identifier', $data) && $data['picklist_identifier'] !== null) {
            $value_3 = $data['picklist_identifier'];
            if (is_null($data['picklist_identifier'])) {
                $value_3 = $data['picklist_identifier'];
            } elseif (is_string($data['picklist_identifier'])) {
                $value_3 = $data['picklist_identifier'];
            }
            $object->picklistIdentifier = $value_3;
        }
        elseif (\array_key_exists('picklist_identifier', $data)) {
            $object->picklistIdentifier = null;
        }
        if (\array_key_exists('order_identifier', $data) && $data['order_identifier'] !== null) {
            $value_4 = $data['order_identifier'];
            if (is_null($data['order_identifier'])) {
                $value_4 = $data['order_identifier'];
            } elseif (is_string($data['order_identifier'])) {
                $value_4 = $data['order_identifier'];
            }
            $object->orderIdentifier = $value_4;
        }
        elseif (\array_key_exists('order_identifier', $data)) {
            $object->orderIdentifier = null;
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('quantity', get_object_vars($data))) {
            $value = $data->quantity;
            if (is_null($data->quantity)) {
                $value = $data->quantity;
            } elseif (is_int($data->quantity)) {
                $value = $data->quantity;
            }
            $dataArray['quantity'] = $value;
        }
        if (array_key_exists('orderLineIdentifier', get_object_vars($data))) {
            $value_1 = $data->orderLineIdentifier;
            if (is_null($data->orderLineIdentifier)) {
                $value_1 = $data->orderLineIdentifier;
            } elseif (is_string($data->orderLineIdentifier)) {
                $value_1 = $data->orderLineIdentifier;
            }
            $dataArray['order_line_identifier'] = $value_1;
        }
        if (array_key_exists('orderLine', get_object_vars($data))) {
            $value_2 = $data->orderLine;
            if (is_null($data->orderLine)) {
                $value_2 = $data->orderLine;
            } elseif (!is_null($data->orderLine)) {
                $value_2 = $data->orderLine;
            }
            $dataArray['order_line'] = $value_2;
        }
        if (array_key_exists('picklistIdentifier', get_object_vars($data))) {
            $value_3 = $data->picklistIdentifier;
            if (is_null($data->picklistIdentifier)) {
                $value_3 = $data->picklistIdentifier;
            } elseif (is_string($data->picklistIdentifier)) {
                $value_3 = $data->picklistIdentifier;
            }
            $dataArray['picklist_identifier'] = $value_3;
        }
        if (array_key_exists('orderIdentifier', get_object_vars($data))) {
            $value_4 = $data->orderIdentifier;
            if (is_null($data->orderIdentifier)) {
                $value_4 = $data->orderIdentifier;
            } elseif (is_string($data->orderIdentifier)) {
                $value_4 = $data->orderIdentifier;
            }
            $dataArray['order_identifier'] = $value_4;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsShipmentShippedItemConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsShipmentShippedItem::class => false];
    }
}