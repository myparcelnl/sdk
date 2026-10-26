<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentResponsesDeliveryOptionsDeliveryTime
{
    /**
     * Represents a time in ISO 8601 format:
     * 
     * ```
     * hh:mm:ss.u
     * ```
     * 
     * Where:
     *   - `hh` represents a zero-padded hour, `00` through `24`
     *   - `mm` represents a zero-padded minute, `00` through `59`
     *   - `ss` (optional) represents a zero-padded second, `00` through `60`
     *     (where `60` is only used to denote an added leap second)
     *   - `.u` (optional) represents a fraction of a second, `.0` through `.999999+`
     * 
     *
     * @var string
     */
    public string $start;
    /**
     * Represents a time in ISO 8601 format:
     * 
     * ```
     * hh:mm:ss.u
     * ```
     * 
     * Where:
     *   - `hh` represents a zero-padded hour, `00` through `24`
     *   - `mm` represents a zero-padded minute, `00` through `59`
     *   - `ss` (optional) represents a zero-padded second, `00` through `60`
     *     (where `60` is only used to denote an added leap second)
     *   - `.u` (optional) represents a fraction of a second, `.0` through `.999999+`
     * 
     *
     * @var string
     */
    public string $end;
    /**
     * Price amount in a specific currency
     *
     * @var CommonDefsPrice
     */
    public CommonDefsPrice $price;
    /**
     * @var string
     */
    public string $priceComment;
    /**
     * @var string
     */
    public string $comment;
    /**
     * @var int
     */
    public int $type;
    /**
     * @var CommonDefsDateTime
     */
    public CommonDefsDateTime $collectDate;
    /**
     * @var string
     */
    public string $identifier;
    /**
     * @var string
     */
    public string $transporter;
    /**
     * @var string
     */
    public string $labelType;
}