<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

use Exception;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

trait HasCountry
{
    /**
     * @var string|null
     */
    public $country;

    /**
     * @throws \Exception
     */
    public function ensureHasCountry(): bool
    {
        $country = $this->getCountry();

        if (! $country) {
            throw new Exception('Country is missing. Please use setCountry(string) first.');
        }

        return (bool) $country;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Check if the address is inside the EU.
     *
     * @return bool
     */
    public function isToEuCountry(): bool
    {
        return in_array(
            $this->getCountry(),
            AbstractConsignment::EURO_COUNTRIES
        );
    }

    /**
     * Check if the address is outside the EU.
     *
     * @return bool
     */
    public function isToRowCountry(): bool
    {
        return false === $this->isToEuCountry();
    }

    /**
     * The address country code
     * ISO3166-1 alpha2 country code<br>
     * <br>
     * Pattern: [A-Z]{2}<br>
     * Example: NL, BE, CW<br>
     * Required: Yes.
     *
     * @param  string $country
     *
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
