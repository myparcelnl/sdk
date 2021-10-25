<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;

trait HasCarrier
{
    /**
     * @param  string|int $carrierId
     *
     * @return bool
     */
    public function carrierIdExists($carrierId): bool
    {
        return $this->hasCarrierWith(
            static function (string $carrier) use ($carrierId) {
                return $carrier::ID === (int) $carrierId;
            }
        );
    }

    /**
     * @param  string $carrierName
     *
     * @return bool
     */
    public function carrierNameExists(string $carrierName): bool
    {
        return $this->hasCarrierWith(
            static function (string $carrier) use ($carrierName) {
                return $carrier::NAME === $carrierName;
            }
        );
    }

    /**
     * @param  callable $callback
     *
     * @return bool
     */
    private function hasCarrierWith(callable $callback): bool
    {
        return (bool) array_filter(
            CarrierFactory::CARRIER_CLASSES,
            $callback
        );
    }
}
