<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Factory\Account;

use Exception;
use MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Services\Web\DropOffPointWebService;

class CarrierConfigurationFactory
{
    /**
     * @param  array       $data
     * @param  bool        $fetchMissingDropOffPoint
     * @param  null|string $apiKey
     *
     * @return \MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public static function create(
        array  $data,
        bool   $fetchMissingDropOffPoint = false,
        string $apiKey = null
    ): CarrierConfiguration {
        $data = self::normalizeCarrier($data);

        if (array_key_exists('configuration', $data)) {
            return self::createFromApi($data, $fetchMissingDropOffPoint, $apiKey);
        }

        if (array_key_exists('default_drop_off_point', $data) && is_array($data['default_drop_off_point'])) {
            return self::createWithExistingDropOffPoint($data);
        }

        if (array_key_exists('default_drop_off_point_identifier', $data)) {
            return self::createWithDropOffPointIdentifier($data, $fetchMissingDropOffPoint, $apiKey);
        }

        throw new Exception('Given data is not a valid carrier configuration.');
    }

    /**
     * @param  array       $data
     * @param  bool        $fetchMissingDropOffPoint
     * @param  null|string $apiKey
     *
     * @return \MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration
     * @throws \Exception
     */
    private static function createFromApi(
        array  $data,
        bool   $fetchMissingDropOffPoint = false,
        string $apiKey = null
    ): CarrierConfiguration {
        return self::createWithDropOffPointIdentifier(
            array_merge(
                $data['configuration'],
                [
                    'carrier'                           => $data['carrier'],
                    'default_drop_off_point_identifier' => $data['configuration']['default_drop_off_point'] ?? null,
                    'default_drop_off_point'            => null,
                ]
            ),
            $fetchMissingDropOffPoint,
            $apiKey
        );
    }

    /**
     * @param  array       $data
     * @param  bool        $fetchDropOffPoint
     * @param  null|string $apiKey
     *
     * @return \MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    private static function createWithDropOffPointIdentifier(
        array $data,
        bool $fetchDropOffPoint = false,
        string $apiKey = null
    ): CarrierConfiguration {
        $missingDropOffPoint = empty($data['default_drop_off_point']);
        $hasIdentifier       = array_key_exists('default_drop_off_point_identifier', $data) && ! empty($data['default_drop_off_point_identifier']);

        if ($fetchDropOffPoint && $missingDropOffPoint && $hasIdentifier) {
            $data['default_drop_off_point'] = (new DropOffPointWebService($data['carrier']))
                ->setApiKey($apiKey)
                ->getDropOffPoint($data['default_drop_off_point_identifier']);
        }

        return new CarrierConfiguration($data);
    }

    /**
     * @param  array $data
     *
     * @return \MyParcelNL\Sdk\src\Model\Account\CarrierConfiguration
     * @throws \Exception
     */
    private static function createWithExistingDropOffPoint(array $data): CarrierConfiguration
    {
        return new CarrierConfiguration(
            array_merge(
                $data,
                [
                    'default_drop_off_point' => new DropOffPoint($data['default_drop_off_point']),
                ]
            )
        );
    }

    /**
     * @param  array $data
     *
     * @return array
     */
    private static function normalizeCarrier(array $data): array
    {
        if (array_key_exists('carrier_id', $data)) {
            $data['carrier'] = $data['carrier_id'];
            unset($data['carrier_id']);
        }

        return $data;
    }
}
