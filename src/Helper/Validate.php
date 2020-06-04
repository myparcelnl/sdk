<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class Validate
{
    const VALIDATE_POSTAL_CODE_REGEX_NL = '/^[1-9][0-9]{3} ?[a-z]{2}$/i';
    const VALIDATE_POSTAL_CODE_REGEX_BE = '/[1-9]\d{3}$/';

    public static function isCorrectPostalCode(string $PostalCode, string $localCountry, ?string $destinationCountry): bool
    {
        $validatePostalCode = Validate::getPostalCodeRegexByCountry($localCountry, $destinationCountry);

        if ($validatePostalCode) {
            return preg_match($validatePostalCode, $PostalCode, $matches) ? true : false;
        }

        return true;
    }

    /**
     * @param string $local
     * @param string $destination
     *
     * @return string
     */
    public static function getPostalCodeRegexByCountry(string $local, string $destination): ?string
    {
        if (
            ($local === AbstractConsignment::CC_NL && $destination === AbstractConsignment::CC_NL) ||
            ($local === AbstractConsignment::CC_NL && $destination === AbstractConsignment::CC_BE)
        ) {
            return self::VALIDATE_POSTAL_CODE_REGEX_NL;
        }

        if (
            ($local === AbstractConsignment::CC_BE && $destination === AbstractConsignment::CC_BE) ||
            ($local === AbstractConsignment::CC_BE && $destination === AbstractConsignment::CC_NL)
        ) {
            return self::VALIDATE_POSTAL_CODE_REGEX_BE;
        }

        return null;
    }
}
