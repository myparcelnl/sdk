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
        '~(?P<street>.{1,78}?)' .           // The rest belongs to the street
        '\s' .                              // Separator between street and number
        '(?P<number>\d{1,5})' .             // Number can contain a maximum of 5 numbers
        '[/\s\-]{0,2}' .                    // Separators between number and addition
        '(?P<number_suffix>' .
        '[a-z]{1}[/\-]?\d{1,3}|' .          // Numbers suffix starts with a letter with optional - or / followed by numbers or
        '-\d{1,4}|' .                       // starts with - and has up to 4 numbers or
        '(?=.{2,6}$)\d{1,6}[/\-a-z]{1,5}|'. // starts with numbers followed by letters with a maximum of 6 chars, or
        '[a-z][a-z\s]{0,5}'.                // has up to 6 letters with a space
        ')?$~i';

    const SPLIT_STREET_REGEX_BE =
        '~(?P<box_separator>)(?P<box_number>)(?P<street>.*?)\s(?P<street_suffix>(?P<number>[0-9\-]{0,7}[0-9])(?P<number_suffix>[A-z]{0,4})\s?(?P<box_separator>' . self::REGEX_BE_BOX_SEPARATORS . '|\,\s+)*\s?(?P<box_number>[0-9A-z]{0,7}[0-9])?\s?(?:(?P<number_suffix>[A-z]{1,4}$)|))?$~J';
    const REGEX_BE_BOX_SEPARATORS = SplitStreet::BOX_BTE . '|' . SplitStreet::BOX_EN . '|' . SplitStreet::BOX_FR . '|' . SplitStreet::BOX_NL . '|' . SplitStreet::BOX_DE . '|' . SplitStreet::BOX_SLASH . '|' . SplitStreet::BOX_DASH . '|' . SplitStreet::BOX_B . '.+';
    /**
     * @param string      $fullStreet
     * @param string      $localCountry
     * @param string|null $destinationCountry
     *
     * @return bool
     */
    public static function validate(string $fullStreet, string $localCountry, ?string $destinationCountry): bool
    {
        if (null === $destinationCountry) {
            return true;
        }

        $regex = ValidateStreet::getStreetRegexByCountry($localCountry, $destinationCountry);

        if (! $regex) {
            return true;
        }

        $result = preg_match($regex, $fullStreet, $matches);

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
        $localIsBe       = $local === AbstractConsignment::CC_BE;
        $localIsNlOrBe   = in_array($local, [AbstractConsignment::CC_BE, AbstractConsignment::CC_NL]);
        $destinationIsNl = $destination === AbstractConsignment::CC_NL;
        $destinationIsBe = $destination === AbstractConsignment::CC_BE;

        if ($localIsNlOrBe && $destinationIsNl) {
            return self::SPLIT_STREET_REGEX_NL;
        }

        if ($localIsBe && $destinationIsBe) {
            return self::SPLIT_STREET_REGEX_BE;
        }

        return null;
    }
}
