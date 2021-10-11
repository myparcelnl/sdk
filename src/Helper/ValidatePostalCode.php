<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class ValidatePostalCode
{
    private const VALIDATE_POSTAL_CODE_REGEX_NL = '/^[1-9]\d{3}\s?[a-z]{2}$/i';
    private const VALIDATE_POSTAL_CODE_REGEX_BE = '/[1-9]\d{3}$/';

    public static function validate(string $postalCode, string $destinationCountry): bool
    {
        $regex = self::getPostalCodeRegexByCountry($destinationCountry);

        if ($regex) {
            return (bool) preg_match($regex, $postalCode);
        }

        return true;
    }

    /**
     * @param  string|null $destination
     *
     * @return string
     */
    private static function getPostalCodeRegexByCountry(string $destination): ?string
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
