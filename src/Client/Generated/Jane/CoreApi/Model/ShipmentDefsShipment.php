<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

class ShipmentDefsShipment
{
    /**
     * Unique ID of a Shipment.
     *
     * @var int
     */
    public int $id;
    /**
     * @var null|int
     */
    public ?int $parentId;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var int
     */
    public int $shopId;
    /**
     * @var RefShipmentType|int
     */
    public RefShipmentType|int $shipmentType;
    /**
     * @var ShipmentDefsShipmentRecipient
     */
    public ShipmentDefsShipmentRecipient $recipient;
    /**
     * @var ShipmentDefsShipmentSender
     */
    public ShipmentDefsShipmentSender $sender;
    /**
     * @var ShipmentDefsShipmentStatus|int
     */
    public ShipmentDefsShipmentStatus|int $status;
    /**
     * @var ShipmentDefsShipmentOptions
     */
    public ShipmentDefsShipmentOptions $options;
    /**
     * @var RefShipmentGeneralSettings|null
     */
    public ?RefShipmentGeneralSettings $generalSettings;
    /**
     * @var ShipmentDefsShipmentPickup|null
     */
    public ?ShipmentDefsShipmentPickup $pickup;
    /**
     * @var null|RefShipmentCustomsDeclaration
     */
    public ?RefShipmentCustomsDeclaration $customsDeclaration;
    /**
     * @var ShipmentDefsShipmentPhysicalProperties|null
     */
    public ?ShipmentDefsShipmentPhysicalProperties $physicalProperties;
    /**
     * Arbitrary reference identifier that can be used to identify a shipment.
     *
     * @var null|int|string
     */
    public $referenceIdentifier;
    /**
     * @var RefShipmentTransactionStatus|string
     */
    public RefShipmentTransactionStatus|string $transactionStatus;
    /**
     * @var ShipmentDefsShipmentDropOffPoint
     */
    public ShipmentDefsShipmentDropOffPoint $dropOffPoint;
    /**
     * @var float
     */
    public float $hidden;
    /**
     * Money amount with currency, where the amount may be null. The amount is null when the requester lacks permission to read price amounts (Permissions::BILLING_READ_PRICE_AMOUNTS).
     *
     * @var RefTypesMoneyNullableAmount
     */
    public RefTypesMoneyNullableAmount $price;
    /**
     * @var string
     */
    public string $barcode;
    /**
     * @var mixed
     */
    public $region;
    /**
     * @var ShipmentDefsExternalProviderPropertiesDisplayName|string|null
     */
    public ShipmentDefsExternalProviderPropertiesDisplayName|string|null $externalProvider;
    /**
     * @var null|int
     */
    public ?int $externalProviderId;
    /**
     * @var ShipmentDefsShipmentPaymentStatus|string
     */
    public ShipmentDefsShipmentPaymentStatus|string $paymentStatus;
    /**
     * This enumeration represents the Carriers. The value is the Carrier id.
     *
     * @var RefTypesCarrier|int
     */
    public RefTypesCarrier|int $carrierId;
    /**
     * @var int
     */
    public int $platformId;
    /**
     * @var null|string
     */
    public ?string $origin;
    /**
     * @var null|string
     */
    public ?string $userAgent;
    /**
     * @var list<ShipmentDefsShipmentSecondaryShipmentsItem>
     */
    public array $secondaryShipments;
    /**
     * @var null|AccountDefsContact
     */
    public ?AccountDefsContact $collectionContact;
    /**
     * @var null|int
     */
    public ?int $multiColloMainShipmentId;
    /**
     * @var null|string
     */
    public ?string $externalIdentifier;
    /**
     * @var bool
     */
    public bool $delayed = false;
    /**
     * @var bool
     */
    public bool $delivered = false;
    /**
     * @var int|null
     */
    public ?int $amount;
    /**
     * @var BillingDefsCurrency|string|null
     */
    public BillingDefsCurrency|string|null $currency;
    /**
     * @var int|null
     */
    public ?int $contractId;
    /**
     * @var mixed|null
     */
    public $linkConsumerPortal;
    /**
     * @var list<ShipmentDefsShipmentPartnerTracktracesItem>
     */
    public array $partnerTracktraces;
    /**
     * @var null|string
     */
    public ?string $pickupRequestNumber;
    /**
     * @var null|string
     */
    public ?string $lastMileCarrierName;
    /**
     * @var null|string
     */
    public ?string $orderShipmentIdentifier;
    /**
     * @var list<ShipmentDefsShipmentShippedItem>|null
     */
    public ?array $shippedItems;
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
    public string $created;
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
    public string $modified;
    /**
     * @var mixed|null
     */
    public $createdBy;
    /**
     * @var mixed|null
     */
    public $modifiedBy;
}