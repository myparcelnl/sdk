<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\Helper;

use MyParcelNL\Sdk\Exception\InvalidConsignmentException;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\FullStreet;

class SplitStreet
{
    const BOX_NL                 = 'bus';
    const BOX_BTE                = 'bte';
    const BOX_FR                 = 'boîte';
    const BOX_EN                 = 'box';
    const BOX_DE                 = 'Bus';
    const BOX_SEPARATOR          = [self::BOX_FR, self::BOX_EN, self::BOX_BTE, self::BOX_DE];
    const BOX_SLASH              = '\/';
    const BOX_DASH               = '-';
    const BOX_B                  = 'B';
    const BOX_SEPARATOR_BY_REGEX = [self::BOX_SLASH, self::BOX_DASH, self::BOX_B];

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
     * Splits street data into separate parts for street name, house number and extension.
     * Only for Dutch and Belgium addresses
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @param string $local
     * @param string $destination
     *
     * @return \MyParcelNL\Sdk\Model\FullStreet
     *
     * @throws \Exception
     */
    public static function splitStreet(string $fullStreet, string $local, string $destination): FullStreet
    {
        $fullStreet = trim(preg_replace('/(\r\n)|\n|\r/', ' ', $fullStreet));

        // Replace house number suffix by an abbreviation, only possible for the Netherlands
        if ($destination === AbstractConsignment::CC_NL) {
            foreach (self::NUMBER_SUFFIX_ABBREVIATION as $from => $to) {
                $fullStreet = preg_replace("/(\d.*-?)[\s]$from/", '$1' . $to, $fullStreet);
            }
        }

        $regex = ValidateStreet::getStreetRegexByCountry($local, $destination);

        if ($destination === AbstractConsignment::CC_BE) {
            preg_match(ValidateStreet::SPLIT_STREET_REGEX_BE, $fullStreet, $matches);

            if (in_array($matches['box_separator'], self::BOX_SEPARATOR)) {
                $matches['box_separator'] = self::BOX_NL;
            }

            $fullStreet = implode(
                ' ',
                array_filter([
                    $matches['street'] ?? $fullStreet,
                    $matches['number'],
                    $matches['box_separator'],
                    $matches['box_number'],
                    $matches['number_suffix'],
                ])
            );

            // When a character is present at BOX_SEPARATOR_BY_REGEX and followed by a number, it must be replaced by bus
            foreach (self::BOX_SEPARATOR_BY_REGEX as $boxRegex) {
                $fullStreet = preg_replace('#' . $boxRegex . '([0-9])#', self::BOX_NL . ' ' . ltrim('$1'), $fullStreet);
            }
        }

        if (! $regex) {
            return new FullStreet($fullStreet, null, null, null);
        }

        $result = preg_match($regex, $fullStreet, $matches);
        self::validate($fullStreet, $result, $matches);

        return new FullStreet(
            $matches['street'] ?? $fullStreet,
                (int) $matches['number'] ?: null,
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
     * @param string $fullStreet
     * @param int    $result
     * @param array  $matches
     *
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\InvalidConsignmentException
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
