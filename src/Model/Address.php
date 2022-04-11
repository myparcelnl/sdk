<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use InvalidArgumentException;
use MyParcelNL\Sdk\src\Helper\SplitStreet;

/**
 * @property string $boxNumber
 * @property string $country
 * @property string $email
 * @property string $fullStreet
 * @property string $number
 * @property string $numberSuffix
 * @property string $person
 * @property string $phone
 * @property string $postalCode
 * @property string $region
 * @property string $street
 * @property string $streetAdditionalInfo
 */
class Address extends Model
{
    protected $attributes = [
        'boxNumber'            => null,
        'country'              => null,
        'email'                => null,
        'fullStreet'           => null,
        'number'               => null,
        'numberSuffix'         => null,
        'person'               => null,
        'phone'                => null,
        'postalCode'           => null,
        'region'               => null,
        'street'               => null,
        'streetAdditionalInfo' => null,
    ];

    /**
     * @param  string $fullStreet
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\InvalidConsignmentException
     * @noinspection PhpUnused
     */
    public function setFullStreetAttribute(string $fullStreet): self
    {
        $country = $this->getCountry();

        if (! $country) {
            throw new InvalidArgumentException('First set "country" before setting "fullStreet".');
        }

        $splitStreet        = SplitStreet::splitStreet($fullStreet, $country[0]);
        $this->street       = $splitStreet->getStreet();
        $this->number       = (string) $splitStreet->getNumber();
        $this->boxNumber    = $splitStreet->getBoxNumber();
        $this->numberSuffix = $splitStreet->getNumberSuffix();
        $this->fullStreet   = $fullStreet;

        return $this;
    }
}
