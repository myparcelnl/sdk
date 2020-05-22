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

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Exception\InvalidConsignmentException;
use MyParcelNL\Sdk\src\Model\FullStreet;

class SplitStreet
{
    const BOX_NL                 = 'bus';
    const BOX_SEPARATOR          = [' boîte', ' box', ' bte', ' Bus'];
    const BOX_SEPARATOR_BY_REGEX = ['\/'];

    public const NUMBER_SUFFIX_ABBREVIATION = [
        'apartment'  => '',
        'gedempte'   => 'GED',
        'groot'      => 'GRT',
        'grote'      => 'GRT',
        'greate'     => 'GRT',
        'noordzijde' => 'NZ',
        'oostzijde'  => 'OZ',
        'zuidzijde'  => 'ZZ',
        'westzijde'  => 'WZ',
        'noord'      => 'N',
        'oost'       => 'O',
        'zuid'       => 'Z',
        'west'       => 'W',
        'hoog'       => 'HG',
        'hoge'       => 'HG',
        'hege'       => 'HG',
        'kleine'     => 'KL',
        'klein'      => 'KL',
        'korte'      => 'K',
        'kort'       => 'K',
        'koarte'     => 'K',
        'koart'      => 'K',
        'kromme'     => 'KR',
        'krom'       => 'KR',
        'laag'       => 'LG',
        'lage'       => 'LG',
        'lege'       => 'LG',
        'lange'      => 'L',
        'lang'       => 'L',
        'nieuwe'     => 'NW',
        'nieuw'      => 'NW',
        'verlengde'  => 'VERL',
    ];

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
        '~(?P<street>.*?)\s(?P<street_suffix>(?P<number>\d{1,4})\s?(?P<box_separator>' . self::BOX_NL . '?)?[\s-]?(?P<box_number>\w{0,8}$))$~';

    /**
     * Splits street data into separate parts for street name, house number and extension.
     * Only for Dutch and Belgium addresses
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @param string $local
     * @param string $destination
     *
     * @return \MyParcelNL\Sdk\src\Model\FullStreet
     *
     * @throws \Exception
     */
    public static function splitStreet(string $fullStreet, string $local, string $destination): FullStreet
    {
        // Replace house number suffix by an abbreviation, only possible for the Netherlands
        if ($destination === AbstractConsignment::CC_NL) {
            foreach (self::NUMBER_SUFFIX_ABBREVIATION as $from => $to) {
                $fullStreet = preg_replace("/(\d.*-?)[\s]$from/", '$1' . $to, $fullStreet);
            }
        }

        if ($destination === AbstractConsignment::CC_BE) {
            // Replace box variants to bus
            $fullStreet = str_ireplace(self::BOX_SEPARATOR, self::BOX_NL, $fullStreet);
            // When a caracter is present at BOX_SEPARATOR_BY_REGEX and followed by a number, it must replaced by bus
            foreach (self::BOX_SEPARATOR_BY_REGEX as $boxRegex) {
                $fullStreet = preg_replace('#' . $boxRegex . '([0-9])#', self::BOX_NL . '$1', $fullStreet);
            }

            $fullStreet = trim(preg_replace('/(\r\n)|\n|\r/', ' ', $fullStreet));
        }

        $regex = self::getRegexByCountry($local, $destination);

        if (! $regex) {
            return new FullStreet($fullStreet, null, null, null);
        }

        $result = preg_match($regex, $fullStreet, $matches);
        self::validate($fullStreet, $result, $matches);

        return new FullStreet(
            $matches['street'] ?? $fullStreet,
            (int) $matches['number'] ?? null,
            $matches['number_suffix'] ?? null,
            $matches['box_number'] ?? null
        );
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
        $streetWrap = wordwrap($street, AbstractConsignment::MAX_STREET_LENGTH, 'BREAK_LINE');
        $parts      = explode("BREAK_LINE", $streetWrap);

        return $parts;
    }

    /**
     * @param string      $fullStreet
     * @param string      $localCountry
     * @param string|null $destinationCountry
     *
     * @return bool
     */
    public static function isCorrectStreet(string $fullStreet, string $localCountry, ?string $destinationCountry): bool
    {
        $result = preg_match(SplitStreet::getRegexByCountry($localCountry, $destinationCountry), $fullStreet, $matches);

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
     * @return string
     */
    public static function getRegexByCountry(string $local, string $destination): ?string
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


    /**
     * @param string $fullStreet
     * @param int    $result
     * @param array  $matches
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\InvalidConsignmentException
     */
    private static function validate(string $fullStreet, int $result, array $matches): void
    {
        if (! $result || ! is_array($matches)) {
            // Invalid full street supplied
            throw new InvalidConsignmentException('Invalid full street supplied: ' . $fullStreet);
        }

        if ($fullStreet != $matches[0]) {
            // Characters are gone by preg_match
            throw new InvalidConsignmentException('Something went wrong splitting up the following address: ' . $fullStreet);
        }

        return;
    }
}
