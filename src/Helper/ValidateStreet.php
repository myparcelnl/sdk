<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class ValidateStreet
{
    /**
     * Regular expression used to split street name from house number for the Netherlands.
     *
     * This regex goes from right to left
     * Contains php keys to store the data in an array
     */
    const SPLIT_STREET_REGEX_NL =
        '~(?P<street>.{2,78}?)' .         // The rest belongs to the street
        '\s' .                            // Separator between street and number
        '(?P<number>\d{1,4})' .           // Number can contain a maximum of 4 numbers
        '[/\s\-]{0,2}' .                  // Separators between number and addition
        '(?P<number_suffix>' .
        '[a-z]{1}\d{1,3}|' .              // Numbers suffix starts with a letter followed by numbers or
        '-\d{1,4}|' .                     // starts with - and has up to 4 numbers or
        '\d{2}\w{1,2}|' .                 // starts with 2 numbers followed by letters or
        '[a-z]{1}[a-z\s]{0,3}' .          // has up to 4 letters with a space
        ')?$~i';

    const SPLIT_STREET_REGEX_BE =
        '~(?P<street>.*?)\s(?P<street_suffix>(?P<number>[0-9\-]{1,8})\s?(?P<box_separator>' . SplitStreet::BOX_NL . ')?\s?(?P<box_number>\d{0,8})\s?(?P<number_suffix>[A-z]{0,4}$)?)$~';

    /**
     * @param string      $fullStreet
     * @param string      $localCountry
     * @param string|null $destinationCountry
     *
     * @return bool
     */
    public static function validate(string $fullStreet, string $localCountry, ?string $destinationCountry): bool
    {
        $result = preg_match(ValidateStreet::getStreetRegexByCountry($localCountry, $destinationCountry), $fullStreet, $matches);

        if (! $result || ! is_array($matches)) {
            // Invalid full street supplied
            return false;
        }

        $fullStreet = str_replace('\n', ' ', $fullStreet);
        if ($fullStreet != $matches[0]) {
            // Characters are gone by preg_match
            return false;
        }

        return (bool) $result;
    }

    /**
     * @param string $local
     * @param string $destination
     *
     * @return null|string
     */
    public static function getStreetRegexByCountry(string $local, string $destination): ?string
    {
        if (
            ($local === AbstractConsignment::CC_NL && $destination === AbstractConsignment::CC_NL) ||
            ($local === AbstractConsignment::CC_NL && $destination === AbstractConsignment::CC_BE)
        ) {
            return self::SPLIT_STREET_REGEX_NL;
        }

        if (
            ($local === AbstractConsignment::CC_BE && $destination === AbstractConsignment::CC_BE) ||
            ($local === AbstractConsignment::CC_BE && $destination === AbstractConsignment::CC_NL)
        ) {
            return self::SPLIT_STREET_REGEX_BE;
        }

        return null;
    }
}
