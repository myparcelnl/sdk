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
class ShipmentDefsDeliveryOptionsPickupNormalizer implements DenormalizerInterface, NormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use NormalizerAwareTrait;
    use CheckArray;
    use ValidatorTrait;
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickup::class;
    }
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return is_object($data) && get_class($data) === \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickup::class;
    }
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickup();
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
            $this->validate($data, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsDeliveryOptionsPickupConstraint());
        }
        if (\array_key_exists('date', $data) && $data['date'] !== null) {
            $value = $data['date'];
            if (is_string($data['date']) and false !== \DateTime::createFromFormat('Y-m-d', $data['date'])) {
                $date = \DateTime::createFromFormat('Y-m-d', $data['date']);
                if (false === $date) {
                    throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\InvalidDateException($data['date'], 'Y-m-d');
                }
                $value = $date->setTime(0, 0, 0);
            } elseif (is_null($data['date'])) {
                $value = $data['date'];
            } elseif ('' === $data['date']) {
                $value = null;
            } elseif (is_string($data['date'])) {
                throw new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\Normalizer\InvalidDateException($data['date'], 'Y-m-d');
            }
            $object->date = $value;
        }
        elseif (\array_key_exists('date', $data)) {
            $object->date = null;
        }
        if (\array_key_exists('time', $data)) {
            $values = [];
            foreach ($data['time'] as $value_1) {
                $values[] = $this->denormalizer->denormalize($value_1, \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupTime::class, 'json', $context);
            }
            $object->time = $values;
        }
        if (\array_key_exists('location', $data)) {
            $object->location = $data['location'];
        }
        if (\array_key_exists('street', $data)) {
            $object->street = $data['street'];
        }
        if (\array_key_exists('number', $data)) {
            $object->number = $data['number'];
        }
        if (\array_key_exists('number_suffix', $data) && $data['number_suffix'] !== null) {
            $value_2 = $data['number_suffix'];
            if (is_string($data['number_suffix'])) {
                $value_2 = $data['number_suffix'];
            } elseif (is_null($data['number_suffix'])) {
                $value_2 = $data['number_suffix'];
            }
            $object->numberSuffix = $value_2;
        }
        elseif (\array_key_exists('number_suffix', $data)) {
            $object->numberSuffix = null;
        }
        if (\array_key_exists('postal_code', $data)) {
            $object->postalCode = $data['postal_code'];
        }
        if (\array_key_exists('city', $data)) {
            $object->city = $data['city'];
        }
        if (\array_key_exists('cc', $data)) {
            $object->cc = $data['cc'];
        }
        if (\array_key_exists('start_time', $data) && $data['start_time'] !== null) {
            $value_3 = $data['start_time'];
            if (is_string($data['start_time'])) {
                $value_3 = $data['start_time'];
            } elseif (is_null($data['start_time'])) {
                $value_3 = $data['start_time'];
            }
            $object->startTime = $value_3;
        }
        elseif (\array_key_exists('start_time', $data)) {
            $object->startTime = null;
        }
        if (\array_key_exists('price', $data)) {
            $object->price = $data['price'];
        }
        if (\array_key_exists('price_comment', $data)) {
            $object->priceComment = is_string($data['price_comment']) ? \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupPriceComment::tryFrom($data['price_comment']) ?? $data['price_comment'] : $data['price_comment'];
        }
        if (\array_key_exists('comment', $data)) {
            $object->comment = $data['comment'];
        }
        if (\array_key_exists('phone_number', $data)) {
            $object->phoneNumber = $data['phone_number'];
        }
        if (\array_key_exists('opening_hours', $data)) {
            $object->openingHours = $this->denormalizer->denormalize($data['opening_hours'], \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsOpeningHours::class, 'json', $context);
        }
        if (\array_key_exists('distance', $data)) {
            $object->distance = $data['distance'];
        }
        if (\array_key_exists('latitude', $data)) {
            $value_4 = $data['latitude'];
            if (is_float($data['latitude'])) {
                $value_4 = $data['latitude'];
            } elseif (is_string($data['latitude'])) {
                $value_4 = $data['latitude'];
            }
            $object->latitude = $value_4;
        }
        if (\array_key_exists('longitude', $data)) {
            $value_5 = $data['longitude'];
            if (is_float($data['longitude'])) {
                $value_5 = $data['longitude'];
            } elseif (is_string($data['longitude'])) {
                $value_5 = $data['longitude'];
            }
            $object->longitude = $value_5;
        }
        if (\array_key_exists('location_code', $data)) {
            $object->locationCode = $data['location_code'];
        }
        if (\array_key_exists('retail_network_id', $data) && $data['retail_network_id'] !== null) {
            $value_6 = $data['retail_network_id'];
            if (is_string($data['retail_network_id'])) {
                $value_6 = $data['retail_network_id'];
            } elseif (is_null($data['retail_network_id'])) {
                $value_6 = $data['retail_network_id'];
            }
            $object->retailNetworkId = $value_6;
        }
        elseif (\array_key_exists('retail_network_id', $data)) {
            $object->retailNetworkId = null;
        }
        if (\array_key_exists('holiday', $data)) {
            $object->holiday = $data['holiday'];
        }
        return $object;
    }
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $dataArray = [];
        if (array_key_exists('date', get_object_vars($data))) {
            $value = $data->date;
            if (is_object($data->date)) {
                $value = $data->date->format('Y-m-d');
            } elseif (is_null($data->date)) {
                $value = $data->date;
            }
            $dataArray['date'] = $value;
        }
        if (array_key_exists('time', get_object_vars($data))) {
            $values = [];
            foreach ($data->time as $value_1) {
                $normalized = $value_1 === null ? null : $this->normalizer->normalize($value_1, 'json', $context);
                $values[] = \is_iterable($normalized) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized) : $normalized;
            }
            $dataArray['time'] = $values;
        }
        if (array_key_exists('location', get_object_vars($data))) {
            $dataArray['location'] = $data->location;
        }
        if (array_key_exists('street', get_object_vars($data))) {
            $dataArray['street'] = $data->street;
        }
        if (array_key_exists('number', get_object_vars($data))) {
            $dataArray['number'] = $data->number;
        }
        if (array_key_exists('numberSuffix', get_object_vars($data))) {
            $value_2 = $data->numberSuffix;
            if (is_string($data->numberSuffix)) {
                $value_2 = $data->numberSuffix;
            } elseif (is_null($data->numberSuffix)) {
                $value_2 = $data->numberSuffix;
            }
            $dataArray['number_suffix'] = $value_2;
        }
        if (array_key_exists('postalCode', get_object_vars($data))) {
            $dataArray['postal_code'] = $data->postalCode;
        }
        if (array_key_exists('city', get_object_vars($data))) {
            $dataArray['city'] = $data->city;
        }
        if (array_key_exists('cc', get_object_vars($data))) {
            $dataArray['cc'] = $data->cc;
        }
        if (array_key_exists('startTime', get_object_vars($data))) {
            $value_3 = $data->startTime;
            if (is_string($data->startTime)) {
                $value_3 = $data->startTime;
            } elseif (is_null($data->startTime)) {
                $value_3 = $data->startTime;
            }
            $dataArray['start_time'] = $value_3;
        }
        if (array_key_exists('price', get_object_vars($data))) {
            $dataArray['price'] = $data->price;
        }
        if (array_key_exists('priceComment', get_object_vars($data))) {
            $dataArray['price_comment'] = $data->priceComment instanceof \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickupPriceComment ? $data->priceComment->value : $data->priceComment;
        }
        if (array_key_exists('comment', get_object_vars($data))) {
            $dataArray['comment'] = $data->comment;
        }
        if (array_key_exists('phoneNumber', get_object_vars($data))) {
            $dataArray['phone_number'] = $data->phoneNumber;
        }
        if (array_key_exists('openingHours', get_object_vars($data))) {
            $normalized_1 = $data->openingHours === null ? null : $this->normalizer->normalize($data->openingHours, 'json', $context);
            $dataArray['opening_hours'] = \is_iterable($normalized_1) ? new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\JsonObject($normalized_1) : $normalized_1;
        }
        if (array_key_exists('distance', get_object_vars($data))) {
            $dataArray['distance'] = $data->distance;
        }
        if (array_key_exists('latitude', get_object_vars($data))) {
            $value_4 = $data->latitude;
            if (is_float($data->latitude)) {
                $value_4 = $data->latitude;
            } elseif (is_string($data->latitude)) {
                $value_4 = $data->latitude;
            }
            $dataArray['latitude'] = $value_4;
        }
        if (array_key_exists('longitude', get_object_vars($data))) {
            $value_5 = $data->longitude;
            if (is_float($data->longitude)) {
                $value_5 = $data->longitude;
            } elseif (is_string($data->longitude)) {
                $value_5 = $data->longitude;
            }
            $dataArray['longitude'] = $value_5;
        }
        if (array_key_exists('locationCode', get_object_vars($data))) {
            $dataArray['location_code'] = $data->locationCode;
        }
        if (array_key_exists('retailNetworkId', get_object_vars($data))) {
            $value_6 = $data->retailNetworkId;
            if (is_string($data->retailNetworkId)) {
                $value_6 = $data->retailNetworkId;
            } elseif (is_null($data->retailNetworkId)) {
                $value_6 = $data->retailNetworkId;
            }
            $dataArray['retail_network_id'] = $value_6;
        }
        if (array_key_exists('holiday', get_object_vars($data))) {
            $dataArray['holiday'] = $data->holiday;
        }
        if (!($context['skip_validation'] ?? false)) {
            $this->validate($dataArray, new \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Validator\ShipmentDefsDeliveryOptionsPickupConstraint());
        }
        return $dataArray;
    }
    public function getSupportedTypes(?string $format = null): array
    {
        return [\MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsPickup::class => false];
    }
}