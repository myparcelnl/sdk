<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Shipment;

use InvalidArgumentException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentShipmentOptions;

/**
 * SDK wrapper around generated shipment options.
 * Keeps package_type normalized to integer ids and accepts v2 enum names
 * for SDK convenience.
 */
class ShipmentOptions extends RefShipmentShipmentOptions
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);
    }

    /**
     * Lazily normalize package_type after generated model hydration.
     *
     * @return int|string|null
     */
    public function getPackageType()
    {
        $packageType = parent::getPackageType();

        if (is_string($packageType)) {
            $this->setPackageType($packageType);

            return parent::getPackageType();
        }

        return $packageType;
    }

    /**
     * Ensure package_type is serialized as integer.
     *
     * @return array<string, string>
     */
    public static function openAPITypes()
    {
        $types = parent::openAPITypes();
        $types['package_type'] = 'int';

        return $types;
    }

    /**
     * @param int|string|null $packageType
     * @return self
     */
    public function setPackageType($packageType)
    {
        if (is_string($packageType)) {
            if (ctype_digit($packageType)) {
                $packageType = (int) $packageType;
            } elseif (PackageType::isValid($packageType)) {
                $packageType = PackageType::toId($packageType);
            } else {
                throw new InvalidArgumentException("Unknown package type '{$packageType}'");
            }
        }

        return parent::setPackageType($packageType);
    }
}
