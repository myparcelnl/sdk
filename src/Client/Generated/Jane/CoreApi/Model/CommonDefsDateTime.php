<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class CommonDefsDateTime implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Represents a date in ISO 8601 format and a time in ISO 8601 format, separated
     * by a space, so:
     * 
     * ```
     * YYYY-MM-DD hh:mm:ss.u
     * ```
     * 
     * Where:
     *   - `YYYY` represents a four-digit year, `0000` through `9999`
     *   - `MM` represents a zero-padded month of the year, `01` through `12`
     *   - `DD` represents a zero-padded day of that month, `01` through `31`
     * and:
     *   - `hh` represents a zero-padded hour, `00` through `24`
     *   - `mm` represents a zero-padded minute, `00` through `59`
     *   - `ss` (optional) represents a zero-padded second, `00` through `60`
     *     (where `60` is only used to denote an added leap second)
     *   - `.u` (optional) represents a fraction of a second, `.0` through `.999999+`
     * 
     *
     * @var string
     */
    public string $date;
    /**
     * @var string
     */
    public string $timezone;
    /**
     * @var CommonDefsTimeZoneType|int
     */
    public CommonDefsTimeZoneType|int $timezoneType;
    public function definedProperties(): array
    {
        return ['date' => 'date', 'timezone' => 'timezone', 'timezoneType' => 'timezone_type'];
    }
}