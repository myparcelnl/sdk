<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentResponsesDeliveryOptionsDeliveryOptionV1
{
    /**
     * Represents a date in ISO 8601 format:
     * 
     * ```
     * YYYY-MM-DD
     * ```
     * 
     * Where:
     *   - `YYYY` represents a four-digit year, `0000` through `9999`
     *   - `MM` represents a zero-padded month of the year, `01` through `12`
     *   - `DD` represents a zero-padded day of that month, `01` through `31`
     * 
     *
     * @var \DateTimeInterface
     */
    public \DateTimeInterface $date;
    /**
     * @var list<ShipmentResponsesDeliveryOptionsDeliveryTime>
     */
    public array $time;
}