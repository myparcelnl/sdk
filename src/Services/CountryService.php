<?php

namespace MyParcelNL\Sdk\src\Services;

class CountryService
{
    /**
     * @return string[]
     */
    public function getAll(): array
    {
        return CountryCodes::ALL;
    }

    /**
     * @param  string $country
     *
     * @return string
     */
    public function getShippingZone(string $country): string
    {
        if (in_array($country, CountryCodes::UNIQUE_COUNTRIES, true)) {
            return $country;
        }

        if (in_array($country, CountryCodes::EU_COUNTRIES, true)) {
            return CountryCodes::ZONE_EU;
        }

        return CountryCodes::ZONE_ROW;
    }

    /**
     * @param  string $country
     *
     * @return bool
     */
    public function isEu(string $country): bool
    {
        return CountryCodes::ZONE_EU === $this->getShippingZone($country);
    }

    /**
     * @param  string $country
     *
     * @return bool
     */
    public function isRow(string $country): bool
    {
        return CountryCodes::ZONE_ROW === $this->getShippingZone($country);
    }
}
