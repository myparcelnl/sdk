<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Exception\AddressException;
use MyparcelNL\Sdk\src\Model\FullStreet;
use PHP_CodeSniffer\Reports\Full;

class SplitStreet
{
    const BOX_NL = 'bus';

    /**
     * Regular expression used to split street name from house number for the Netherlands.
     *
     * This regex goes from right to left
     * Contains php keys to store the data in an array
     */
    const SPLIT_STREET_REGEX_NL =
        '~(?P<street>.*?)' .              // The rest belongs to the street
        '\s?' .                           // Separator between street and number
        '(?P<number>\d{1,4})' .           // Number can contain a maximum of 4 numbers
        '[/\s\-]{0,2}' .                  // Separators between number and addition
        '(?P<number_suffix>' .
        '[a-zA-Z]{1}\d{1,3}|' .           // Numbers suffix starts with a letter followed by numbers or
        '-\d{1,4}|' .                     // starts with - and has up to 4 numbers or
        '\d{2}\w{1,2}|' .                 // starts with 2 numbers followed by letters or
        '[a-zA-Z]{1}[a-zA-Z\s]{0,3}' .    // has up to 4 letters with a space
        ')?$~';

    /* @todo: check if this is correct */
    const SPLIT_STREET_REGEX_BE =
        '~(?P<street>.*?)' .
        '\s' .                              // Separator between street and street_suffix
        '(?P<street_suffix>' .
        '(?P<number>[^\s]{1,8})' .          // Number can contain a maximum of 8 numbers
        '.\s' .                             // Separator between number and box_separator
        '?(?P<box_separator>'
        . self::BOX_NL .
        '?)?\s' .
        '?(?P<box_number>\d{0,8}$)' .       // Box_number can contain a maximum of 8 numbers
        ')$~';

    /**
     * Splits street data into separate parts for street name, house number and extension.
     * Only for Dutch addresses
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @param string $local
     * @param string $destination
     *
     * @return \MyparcelNL\Sdk\src\Model\FullStreet
     *
     * @throws \Exception
     */
    public static function splitStreet(string $fullStreet, string $local, string $destination): FullStreet
    {
        $fullStreet = trim(preg_replace('/(\r\n)|\n|\r/', ' ', $fullStreet));
        $regex      = self::getRegexByCountry($local, $destination);
        $result     = preg_match($regex, $fullStreet, $matches);

        self::validate($fullStreet, $result, $matches);
        $fullStreet = new FullStreet(
            $matches['street'],
            $matches['number '] ?? null,
            $matches['number_suffix '] ?? null,
            $matches['boxNumber '] ?? null
        );

        return $fullStreet;
    }

    /**
     * Wraps a street to max street length
     *
     * @param $street
     *
     * @return array
     */
    public static function getStreetParts($street)
    {
        $streetWrap = wordwrap($street, MyParcelConsignment::MAX_STREET_LENGTH, 'BREAK_LINE');
        $parts      = explode("BREAK_LINE", $streetWrap);

        return $parts;
    }

    /**
     * @param $fullStreet
     * @param $result
     * @param $matches
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AddressException
     */
    private static function validate($fullStreet, $result, $matches)
    {
        if (! $result || ! is_array($matches)) {
            // Invalid full street supplied
            throw new AddressException('Invalid full street supplied: ' . $fullStreet);
        }

        if ($fullStreet != $matches[0]) {
            // Characters are gone by preg_match
            throw new AddressException('Something went wrong with splitting up address ' . $fullStreet);
        }
    }

    /**
     * @param string $local
     * @param string $destination
     *
     * @return string
     */
    public static function getRegexByCountry(string $local, string $destination): ?string
    {
        if ($local === AbstractConsignment::CC_NL && $destination === AbstractConsignment::CC_NL) {
            return self::SPLIT_STREET_REGEX_NL;
        }

        if ($local === AbstractConsignment::CC_BE && $destination === AbstractConsignment::CC_BE) {
            return self::SPLIT_STREET_REGEX_BE;
        }

        //
        return null;
    }
}
