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

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Exception\AddressException;

class SplitStreet
{
    /**
     * Regular expression used to split street name from house number.
     *
     * This regex goes from right to left
     * Contains php keys to store the data in an array
     */
    const SPLIT_STREET_REGEX =
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

    /**
     * Splits street data into separate parts for street name, house number and extension.
     * Only for Dutch addresses
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @return array
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AddressException
     */
    public static function splitStreet($fullStreet)
    {
        $fullStreet = trim(preg_replace('/(\r\n)|\n|\r/', ' ', $fullStreet));
        $result     = preg_match(self::SPLIT_STREET_REGEX, $fullStreet, $matches);

        self::validate($fullStreet, $result, $matches);

        return self::getStreetData($matches);
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
     * @param $matches
     *
     * @return array
     */
    private static function getStreetData($matches)
    {
        $street        = '';
        $number        = '';
        $number_suffix = '';

        if (isset($matches['street'])) {
            $street = $matches['street'];
        }

        if (isset($matches['number'])) {
            $number = $matches['number'];
        }

        if (isset($matches['number_suffix'])) {
            $number_suffix = trim($matches['number_suffix'], '-');
        }

        $streetData = array(
            'street'        => $street,
            'number'        => $number,
            'number_suffix' => $number_suffix,
        );

        return $streetData;
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
}
