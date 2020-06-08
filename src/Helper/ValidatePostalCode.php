<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class ValidatePostalCode
{
    const VALIDATE_POSTAL_CODE_REGEX_NL = '/^[1-9]\d{3}\s?[a-z]{2}$/i';
    const VALIDATE_POSTAL_CODE_REGEX_BE = '/[1-9]\d{3}$/';

    public static function validate(string $postalCode, ?string $destinationCountry): bool
    {
        $validatePostalCode = ValidatePostalCode::getPostalCodeRegexByCountry($destinationCountry);

        if ($validatePostalCode) {
            return (bool) preg_match($validatePostalCode, $postalCode);
        }

        return true;
    }

    /**
     * @param string $local
     * @param string $destination
     *
     * @return string
     */
    public static function getPostalCodeRegexByCountry(string $destination): ?string
    {
        switch ($destination) {
            case AbstractConsignment::CC_NL:
                return self::VALIDATE_POSTAL_CODE_REGEX_NL;
            case AbstractConsignment::CC_BE:
                return self::VALIDATE_POSTAL_CODE_REGEX_BE;
            default:
                return null;
        }
    }
}
