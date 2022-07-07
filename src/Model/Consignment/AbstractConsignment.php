<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use BadMethodCallException;
use Exception;
use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Concerns\HasCheckoutFields;
use MyParcelNL\Sdk\src\Concerns\HasCountry;
use MyParcelNL\Sdk\src\Exception\InvalidConsignmentException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Exception\ValidationException;
use MyParcelNL\Sdk\src\Helper\SplitStreet;
use MyParcelNL\Sdk\src\Helper\TrackTraceUrl;
use MyParcelNL\Sdk\src\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Str;
use MyParcelNL\Sdk\src\Validator\ValidatorFactory;

abstract class AbstractConsignment
{
    use HasCheckoutFields;
    use HasCountry;
    use HasPickupLocation;

    /*
     * Allows setting an API key for each shipment, so you can create multiple
     *  shipments for different shops at the same time. This way you won't have to provide a shop ID.
     */
    use HasApiKey;

    public const SHIPMENT_OPTION_AGE_CHECK         = 'age_check';
    public const SHIPMENT_OPTION_INSURANCE         = 'insurance';
    public const SHIPMENT_OPTION_LARGE_FORMAT      = 'large_format';
    public const SHIPMENT_OPTION_ONLY_RECIPIENT    = 'only_recipient';
    public const SHIPMENT_OPTION_RETURN            = 'return';
    public const SHIPMENT_OPTION_SIGNATURE         = 'signature';
    public const SHIPMENT_OPTION_SAME_DAY_DELIVERY = 'same_day_delivery';

    public const EXTRA_OPTION_DELIVERY_DATE     = 'delivery_date';
    public const EXTRA_OPTION_DELIVERY_MONDAY   = 'delivery_monday';
    public const EXTRA_OPTION_DELIVERY_SATURDAY = 'delivery_saturday';
    public const EXTRA_OPTION_MULTI_COLLO       = 'multi_collo';

    /**
     * Consignment types.
     */
    public const DELIVERY_TYPE_MORNING  = 1;
    public const DELIVERY_TYPE_STANDARD = 2;
    public const DELIVERY_TYPE_EVENING  = 3;
    public const DELIVERY_TYPE_PICKUP   = 4;

    /**
     * @deprecated Since November 2019 is it no longer possible to use pickup express.
     */
    public const DELIVERY_TYPE_PICKUP_EXPRESS = 5;

    public const DELIVERY_TYPE_MORNING_NAME  = 'morning';
    public const DELIVERY_TYPE_STANDARD_NAME = 'standard';
    public const DELIVERY_TYPE_EVENING_NAME  = 'evening';
    public const DELIVERY_TYPE_PICKUP_NAME   = 'pickup';

    /**
     * @deprecated Since November 2019 is it no longer possible to use pickup express.
     */
    public const DELIVERY_TYPE_PICKUP_EXPRESS_NAME = 'pickup_express';

    public const DELIVERY_TYPES_IDS = [
        self::DELIVERY_TYPE_MORNING,
        self::DELIVERY_TYPE_STANDARD,
        self::DELIVERY_TYPE_EVENING,
        self::DELIVERY_TYPE_PICKUP,
        self::DELIVERY_TYPE_PICKUP_EXPRESS,
    ];

    public const DELIVERY_TYPES_NAMES = [
        self::DELIVERY_TYPE_MORNING_NAME,
        self::DELIVERY_TYPE_STANDARD_NAME,
        self::DELIVERY_TYPE_EVENING_NAME,
        self::DELIVERY_TYPE_PICKUP_NAME,
        self::DELIVERY_TYPE_PICKUP_EXPRESS_NAME,
    ];

    public const DELIVERY_TYPES_NAMES_IDS_MAP = [
        self::DELIVERY_TYPE_MORNING_NAME        => self::DELIVERY_TYPE_MORNING,
        self::DELIVERY_TYPE_STANDARD_NAME       => self::DELIVERY_TYPE_STANDARD,
        self::DELIVERY_TYPE_EVENING_NAME        => self::DELIVERY_TYPE_EVENING,
        self::DELIVERY_TYPE_PICKUP_NAME         => self::DELIVERY_TYPE_PICKUP,
        self::DELIVERY_TYPE_PICKUP_EXPRESS_NAME => self::DELIVERY_TYPE_PICKUP_EXPRESS,
    ];

    public const DEFAULT_DELIVERY_TYPE      = self::DELIVERY_TYPE_STANDARD;
    public const DEFAULT_DELIVERY_TYPE_NAME = self::DELIVERY_TYPE_STANDARD_NAME;

    /**
     * Customs declaration types.
     */
    public const PACKAGE_CONTENTS_COMMERCIAL_GOODS   = 1;
    public const PACKAGE_CONTENTS_COMMERCIAL_SAMPLES = 2;
    public const PACKAGE_CONTENTS_DOCUMENTS          = 3;
    public const PACKAGE_CONTENTS_GIFTS              = 4;
    public const PACKAGE_CONTENTS_RETRUN_SHIPMENT    = 5;

    /**
     * Package types.
     */
    public const PACKAGE_TYPE_PACKAGE       = 1;
    public const PACKAGE_TYPE_MAILBOX       = 2;
    public const PACKAGE_TYPE_LETTER        = 3;
    public const PACKAGE_TYPE_DIGITAL_STAMP = 4;

    public const PACKAGE_TYPE_PACKAGE_NAME       = 'package';
    public const PACKAGE_TYPE_MAILBOX_NAME       = 'mailbox';
    public const PACKAGE_TYPE_LETTER_NAME        = 'letter';
    public const PACKAGE_TYPE_DIGITAL_STAMP_NAME = 'digital_stamp';

    public const PACKAGE_TYPES_IDS = [
        self::PACKAGE_TYPE_PACKAGE,
        self::PACKAGE_TYPE_MAILBOX,
        self::PACKAGE_TYPE_LETTER,
        self::PACKAGE_TYPE_DIGITAL_STAMP,
    ];

    public const PACKAGE_TYPES_NAMES = [
        self::PACKAGE_TYPE_PACKAGE_NAME,
        self::PACKAGE_TYPE_MAILBOX_NAME,
        self::PACKAGE_TYPE_LETTER_NAME,
        self::PACKAGE_TYPE_DIGITAL_STAMP_NAME,
    ];

    public const PACKAGE_TYPES_NAMES_IDS_MAP = [
        self::PACKAGE_TYPE_PACKAGE_NAME       => self::PACKAGE_TYPE_PACKAGE,
        self::PACKAGE_TYPE_MAILBOX_NAME       => self::PACKAGE_TYPE_MAILBOX,
        self::PACKAGE_TYPE_LETTER_NAME        => self::PACKAGE_TYPE_LETTER,
        self::PACKAGE_TYPE_DIGITAL_STAMP_NAME => self::PACKAGE_TYPE_DIGITAL_STAMP,
    ];

    public const DEFAULT_PACKAGE_TYPE      = self::PACKAGE_TYPE_PACKAGE;
    public const DEFAULT_PACKAGE_TYPE_NAME = self::PACKAGE_TYPE_PACKAGE_NAME;

    /**
     * Regular expression used to make sure the date is correct.
     */
    public const DATE_REGEX        = '~(\d{4}-\d{2}-\d{2})$~';
    public const DATE_TIME_REGEX   = '~(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})$~';
    public const STATUS_CONCEPT    = 1;
    public const MAX_STREET_LENGTH = 40;

    public const CC_NL = 'NL';
    public const CC_BE = 'BE';

    public const EURO_COUNTRIES = [
        'NL',
        'BE',
        'AT',
        'BG',
        'CZ',
        'CY',
        'DK',
        'EE',
        'FI',
        'FR',
        'DE',
        'GR',
        'HU',
        'IE',
        'IT',
        'LV',
        'LT',
        'LU',
        'PL',
        'PT',
        'RO',
        'SK',
        'SI',
        'ES',
        'SE',
        'XK',
    ];

    /**
     * @var array
     * @deprecated use getLocalInsurancePossibilities()
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [];

    /**
     * @var int
     * @deprecated use self::CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH
     */
    public const DESCRIPTION_MAX_LENGTH = self::CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH;

    /**
     * @var int
     */
    public const CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH = 50;

    /**
     * @var int
     */
    public const LABEL_DESCRIPTION_MAX_LENGTH = 45;

    /**
     * @internal
     * @var null|string
     */
    public $reference_identifier;

    /**
     * @internal
     * @var int
     */
    public $consignment_id;

    /**
     * @internal
     * @var string|null
     */
    public $barcode;

    /**
     * @internal
     * @var string|null
     */
    public $externalIdentifier;

    /**
     * @internal
     * @var int
     */
    public $status = self::STATUS_CONCEPT;

    /**
     * @internal
     * @var int
     */
    public $shop_id;

    /**
     * @internal
     * @var string
     */
    public $cc;

    /**
     * @internal
     * @var string
     */
    public $city;

    /**
     * @internal
     * @var string
     */
    public $region;

    /**
     * @internal
     * @var string
     */
    public $street;

    /**
     * @internal
     * @var string
     */
    public $street_additional_info;

    /**
     * @internal
     * @var string|null
     */
    public $number;

    /**
     * @internal
     * @var string
     */
    public $number_suffix = '';

    /**
     * @internal
     * @var string
     */
    public $box_number = '';

    /**
     * @internal
     * @var string
     */
    public $postal_code;

    /**
     * @internal
     * @var string
     */
    public $person;

    /**
     * @internal
     * @var string
     */
    public $company = '';

    /**
     * @internal
     * @var string
     */
    public $email = '';

    /**
     * @internal
     * @var string
     */
    public $phone = '';

    /**
     * @internal
     * @var int
     */
    public $package_type;

    /**
     * @internal
     * @var int
     */
    public $delivery_type = self::DEFAULT_DELIVERY_TYPE;

    /**
     * @internal
     * @var string
     */
    public $delivery_date;

    /**
     * @internal
     * @var bool|null
     */
    public $only_recipient;

    /**
     * @internal
     * @var bool|null
     */
    public $signature;

    /**
     * @internal
     * @var bool|null
     */
    public $return;

    /**
     * @internal
     * @var bool|null
     */
    public $same_day_delivery;

    /**
     * @internal
     * @var bool|null
     */
    public $large_format;

    /**
     * @internal
     * @var bool|null
     */
    public $age_check;

    /**
     * @internal
     * @var string
     */
    public $label_description = '';

    /**
     * @internal
     * @var int
     */
    public $insurance = 0;

    /**
     * @internal
     * @var array
     */
    public $physical_properties = [];

    /**
     * @internal
     * @var int
     */
    public $contents = self::PACKAGE_CONTENTS_COMMERCIAL_GOODS;

    /**
     * @internal
     * @var string
     */
    public $invoice;

    /**
     * @internal
     * @var array
     */
    public $items = [];

    /**
     * @var string|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    protected $carrierClass;

    /**
     * @var null|string
     */
    protected $validatorClass;

    /**
     * @var null|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    private $carrier;

    /**
     * @var bool
     */
    private $partOfMultiCollo = false;

    /**
     * @var bool
     */
    private $auto_detect_pickup = true;

    /**
     * @var bool
     */
    private $save_recipient_address = true;

    /**
     * @var null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    protected $drop_off_point;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->carrier = $this->carrierClass
            ? CarrierFactory::createFromClass($this->carrierClass)
            : null;
    }

    /**
     * @return null|\MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier
     */
    final public function getCarrier(): ?AbstractCarrier
    {
        return $this->carrier;
    }

    /**
     * @param  string $deliveryType
     *
     * @return bool
     */
    public function canHaveDeliveryType(string $deliveryType): bool
    {
        $allowedDeliveryTypes = $this->getAllowedDeliveryTypes();
        if (self::PACKAGE_TYPE_PACKAGE !== $this->getPackageType()) {
            $allowedDeliveryTypes = [self::DELIVERY_TYPE_STANDARD];
        }

        return in_array($deliveryType, $allowedDeliveryTypes, true);
    }

    /**
     * @param  string $option
     *
     * @return bool
     */
    public function canHaveExtraOption(string $option): bool
    {
        return in_array($option, $this->getAllowedExtraOptions(), true);
    }

    /**
     * @param  string $packageType
     *
     * @return bool
     */
    public function canHavePackageType(string $packageType): bool
    {
        return in_array($packageType, $this->getAllowedPackageTypes(), true);
    }

    /**
     * @param  string $option
     *
     * @return bool
     */
    public function canHaveShipmentOption(string $option): bool
    {
        $isPackage         = self::PACKAGE_TYPE_PACKAGE === $this->getPackageType();
        $isPickup          = self::DELIVERY_TYPE_PICKUP === $this->getDeliveryType();
        $optionIsAvailable = in_array($option, $this->getAllowedShipmentOptions(), true);
        $pickupAllowed     = in_array($option, $this->getAllowedShipmentOptionsForPickup(), true);

        return $isPackage && $optionIsAvailable && ($pickupAllowed || ! $isPickup);
    }

    /**
     * The id of the consignment
     * Save this id in your database
     *
     * @return int
     * @deprecated Use getConsignmentId instead
     */
    public function getMyParcelConsignmentId(): int
    {
        return $this->getConsignmentId();
    }

    /**
     * @return array
     */
    public function getInsurancePossibilities(): array
    {
        return $this->getLocalInsurancePossibilities();
    }

    /**
     * @return string
     */
    public function getReferenceIdentifier(): string
    {
        return (string) $this->reference_identifier;
    }

    /**
     * @return string|null
     * @deprecated use getReferenceIdentifier()
     */
    public function getReferenceId(): ?string
    {
        return $this->getReferenceIdentifier();
    }

    /**
     * @param  int $id
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @internal
     * @deprecated Use setConsignmentId instead
     */
    public function setMyParcelConsignmentId(int $id): AbstractConsignment
    {
        return $this->setConsignmentId($id);
    }

    /**
     * @param  string|null $referenceIdentifier
     *
     * @return self
     */
    public function setReferenceIdentifier(?string $referenceIdentifier): self
    {
        $this->reference_identifier = $referenceIdentifier;

        return $this;
    }

    /**
     * @param  string|null $referenceIdentifier
     *
     * @return self
     * @deprecated use setReferenceIdentifier()
     */
    public function setReferenceId(?string $referenceIdentifier): self
    {
        return $this->setReferenceIdentifier($referenceIdentifier);
    }

    /**
     * The id of the consignment
     * Save this id in your database.
     *
     * @return int|null
     */
    public function getConsignmentId(): ?int
    {
        return $this->consignment_id;
    }

    /**
     * @param  int|null $id
     *
     * @return self
     * @internal
     * The id of the consignment
     */
    public function setConsignmentId(?int $id): self
    {
        $this->consignment_id = $id;

        return $this;
    }

    /**
     * @param  null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint $dropOffPoint
     *
     * @return self
     */
    public function setDropOffPoint(?DropOffPoint $dropOffPoint): self
    {
        $this->drop_off_point = $dropOffPoint;
        return $this;
    }

    /**
     * @return null|\MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint
     */
    public function getDropOffPoint(): ?DropOffPoint
    {
        return $this->drop_off_point;
    }

    /**
     * @param  bool $value
     *
     * @return self
     */
    public function setMultiCollo(bool $value = true): self
    {
        $this->partOfMultiCollo = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPartOfMultiCollo(): bool
    {
        return $this->partOfMultiCollo;
    }

    /**
     * @return string|null
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param  string|null $barcode
     *
     * @return self
     * @internal
     */
    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalIdentifier(): ?string
    {
        return $this->externalIdentifier;
    }

    /**
     * @param  null|string  $externalIdentifier
     *
     * @return self
     * @internal
     */
    public function setExternalIdentifier(?string $externalIdentifier): self
    {
        $this->externalIdentifier = $externalIdentifier;

        return $this;
    }

    /**
     * Get the status of the consignment
     * Pattern: [1 – 99]<br>
     * Example:
     *          1 pending - concept
     *          2 pending - registered
     *          3 enroute - handed to carrier
     *          4 enroute - sorting
     *          5 enroute - distribution
     *          6 enroute - customs
     *          7 delivered - at recipient
     *          8 delivered - ready for pickup
     *          9 delivered - package picked up
     *          10 delivered - return shipment ready for pickup
     *          11 delivered - return shipment package picked up
     *          12 printed - letter
     *          13 credit
     *          30 inactive - concept
     *          31 inactive - registered
     *          32 inactive - enroute - handed to carrier
     *          33 inactive - enroute - sorting
     *          34 inactive - enroute - distribution
     *          35 inactive - enroute - customs
     *          36 inactive - delivered - at recipient
     *          37 inactive - delivered - ready for pickup
     *          38 inactive - delivered - package picked up
     *          99 inactive - unknown.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Status of the consignment.
     *
     * @param  int $status
     *
     * @return self
     * @internal
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getShopId(): int
    {
        return $this->shop_id;
    }

    /**
     * @param  mixed $shopId
     *
     * @return self
     * @internal
     * The shop id to which this shipment belongs
     * When the store ID is not specified, the API will look at the API key.
     * Required: No
     * @internal
     */
    public function setShopId(int $shopId): self
    {
        $this->shop_id = $shopId;

        return $this;
    }

    /**
     * Check if the address is outside the EU.
     *
     * @return bool
     * @deprecated Use HasCountry::isToRowCountry()
     */
    public function isCdCountry(): bool
    {
        return $this->isToRowCountry();
    }

    /**
     * Check if the address is inside the EU.
     *
     * @return bool
     * @deprecated Use HasCountry::isToEuCountry()
     */
    public function isEuCountry(): bool
    {
       return $this->isToEuCountry();
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param  string|null  $region
     *
     * @return self
     */
    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * The address city
     * Required: Yes.
     *
     * @param  string $city
     *
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @var bool
     */
    public function getStreet($useStreetAdditionalInfo = false): string
    {
        if (null === $this->street) {
            throw new MissingFieldException(
                'First set the street with setStreet() before running getStreet()'
            );
        }

        if ($useStreetAdditionalInfo && strlen($this->street) >= self::MAX_STREET_LENGTH) {
            $streetParts = SplitStreet::getStreetParts($this->street);

            return $streetParts[0];
        }

        return $this->street;
    }

    /**
     * The address street name
     * Required: Yes or use setFullStreet().
     *
     * @param  string $street
     *
     * @return self
     */
    public function setStreet($street)
    {
        $this->street = trim(str_replace('\n', ' ', $street));

        return $this;
    }

    /**
     * Get additional information for the street that should not be included in the street field.
     *
     * @return string|null
     * @todo move to hasStreet
     */
    public function getStreetAdditionalInfo(): ?string
    {
        if ($this->street === null) {
            return null;
        }

        $streetParts = SplitStreet::getStreetParts($this->street);
        $result      = '';

        if (isset($streetParts[1])) {
            $result .= $streetParts[1];
        }

        $result .= ' ' . (string) $this->street_additional_info;

        return trim($result);
    }

    /**
     * The street additional info
     * Required: No.
     *
     * @param  string|null $streetAdditionalInfo
     *
     * @return self
     */
    public function setStreetAdditionalInfo(?string $streetAdditionalInfo): self
    {
        $this->street_additional_info = $streetAdditionalInfo;

        return $this;
    }

    /**
     * Get entire street.
     *
     * @return string Entire street
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @var bool
     * @todo move to hasCountry
     */
    public function getFullStreet(bool $useStreetAdditionalInfo = false): string
    {
        $fullStreet = $this->getStreet($useStreetAdditionalInfo);

        if ($this->getNumber()) {
            $fullStreet .= ' ' . $this->getNumber();
        }

        if ($this->getBoxNumber()) {
            $fullStreet .= ' ' . SplitStreet::BOX_NL . ' ' . $this->getBoxNumber();
        }

        if ($this->getNumberSuffix()) {
            $fullStreet .= ' ' . $this->getNumberSuffix();
        }

        return trim($fullStreet);
    }

    /**
     * Splitting a full NL address and save it in this object
     * Required: Yes or use setStreet().
     *
     * @param  string $fullStreet
     *
     * @return self
     * @throws MissingFieldException
     * @throws \BadMethodCallException
     * @throws \Exception
     */
    public function setFullStreet(string $fullStreet): self
    {
        if ($this->getCountry() === null) {
            throw new MissingFieldException(
                'First set the country code with setCountry() before running setFullStreet()'
            );
        }

        if (empty($this->getLocalCountryCode())) {
            throw new BadMethodCallException('Can not create a shipment when the local country code is empty.');
        }

        $splitStreet = SplitStreet::splitStreet($fullStreet, $this->getLocalCountryCode(), $this->getCountry());
        $this->setStreet($splitStreet->getStreet());
        $this->setNumber($splitStreet->getNumber());
        $this->setBoxNumber($splitStreet->getBoxNumber());
        $this->setNumberSuffix($splitStreet->getNumberSuffix());

        return $this;
    }

    /**
     * @param  bool $value
     *
     * @return self
     */
    public function setSaveRecipientAddress(bool $value): self
    {
        $this->save_recipient_address = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSaveRecipientAddress(): bool
    {
        return $this->save_recipient_address;
    }

    /**
     * @param  string $barcode
     * @param  string $postalCode
     * @param  string $countryCode
     *
     * @return string
     */
    public function getBarcodeUrl(string $barcode, string $postalCode, string $countryCode): string
    {
        return TrackTraceUrl::create($barcode, $postalCode, $countryCode);
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * Street number
     * Whole numeric value
     * Pattern: [0-9]+
     * Example: 10. 20. NOT 2,3
     * Required: Yes for NL.
     *
     * @param  mixed $number
     *
     * @return self
     */
    public function setNumber($number): self
    {
        $this->number = (string) $number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumberSuffix(): ?string
    {
        return $this->number_suffix;
    }

    /**
     * Street number suffix.
     * Required: no.
     *
     * @param  string|null $numberSuffix
     *
     * @return self
     */
    public function setNumberSuffix(?string $numberSuffix): self
    {
        $this->number_suffix = $numberSuffix;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBoxNumber(): ?string
    {
        return $this->box_number;
    }

    /**
     * Street number suffix.
     * Required: no.
     *
     * @param  string|null $boxNumber
     *
     * @return self
     */
    public function setBoxNumber(?string $boxNumber): self
    {
        $this->box_number = $boxNumber;

        return $this;
    }

    /**
     * @param  array $consignmentEncoded
     *
     * @return array
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function encodeStreet(array $consignmentEncoded): array
    {
        if ($this->getCountry() === self::CC_NL) {
            return array_merge_recursive($consignmentEncoded, [
                'recipient' => [
                    'street'                 => $this->getStreet(true),
                    'street_additional_info' => $this->getStreetAdditionalInfo(),
                    'number'                 => $this->getNumber(),
                    'number_suffix'          => (string) $this->getNumberSuffix(),
                ],
            ]);
        }

        $consignmentEncoded['recipient']['street']                 = $this->getFullStreet(true);
        $consignmentEncoded['recipient']['street_additional_info'] = $this->getStreetAdditionalInfo();

        return $consignmentEncoded;
    }

    /**
     * Check if address is correct
     * Only for Dutch addresses.
     *
     * @param  string $fullStreet
     *
     * @return bool
     * @deprecated Use ValidateStreet::validate()
     */
    public function isCorrectAddress(string $fullStreet): bool
    {
        $localCountry       = $this->getLocalCountryCode();
        $destinationCountry = $this->getCountry();

        return SplitStreet::isCorrectStreet($fullStreet, $localCountry, $destinationCountry);
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * @param  string $postalCode
     *
     * @return self
     * @throws \BadMethodCallException
     * @throws \Exception
     */
    public function setPostalCode(string $postalCode): self
    {
        if ($this->getCountry() === null) {
            throw new MissingFieldException(
                'First set the country code with setCountry() before running setPostalCode()'
            );
        }
        if (empty($this->getLocalCountryCode())) {
            throw new BadMethodCallException('Can not create a shipment when the local country code is empty.');
        }

        if (! ValidatePostalCode::validate($postalCode, $this->getCountry())) {
            throw new BadMethodCallException('Invalid postal code');
        }

        $this->postal_code = $postalCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerson(): string
    {
        return $this->person;
    }

    /**
     * The person at this address
     * Required: Yes.
     *
     * @param  string $person
     *
     * @return self
     */
    public function setPerson(string $person): self
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * Company name
     * Required: no.
     *
     * @param  string|null $company
     *
     * @return self
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * The address email
     * Required: no.
     *
     * @param  string|null  $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * The address phone
     * Required: no.
     *
     * @param  string|null $phone
     *
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param  int|null $default
     *
     * @return int|null
     */
    public function getPackageType($default = null): ?int
    {
        return $this->package_type ?? $default;
    }

    /**
     * The package type
     * For international shipment only package type 1 is allowed
     * Pattern: [1 – 4]<br>
     * Example:
     *          1. package
     *          2. mailbox package
     *          3. letter
     *          4. digital stamp
     * Required: Yes.
     *
     * @param  int $packageType
     *
     * @return self
     * @throws \Exception
     */
    public function setPackageType(int $packageType): self
    {
        $packageTypeMap = array_flip(self::PACKAGE_TYPES_NAMES_IDS_MAP);

        if (! in_array($packageTypeMap[$packageType], $this->getAllowedPackageTypes(), true)) {
            throw new Exception('Use the correct package type for shipment:' . $this->consignment_id);
        }

        $this->package_type = $packageType;

        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryType(): int
    {
        return $this->delivery_type;
    }

    /**
     * The delivery type for the package
     * Required: Yes if delivery_date has been specified.
     *
     * @param  int $deliveryType
     *
     * @return self
     */
    public function setDeliveryType(int $deliveryType): self
    {
        $this->delivery_type = $deliveryType;

        return $this;
    }

    /**
     * @param  bool $value
     *
     * @return self
     */
    public function setAutoDetectPickup(bool $value): self
    {
        $this->auto_detect_pickup = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoDetectPickup(): bool
    {
        return $this->auto_detect_pickup;
    }

    /**
     * @return string|null
     */
    public function getDeliveryDate(): ?string
    {
        return $this->delivery_date;
    }

    /**
     * The delivery date time for this shipment
     * Pattern: YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
     * Example: 2017-01-01 | 2017-01-01 00:00:00
     * Required: Yes if delivery type has been specified.
     *
     * @param  string|null $deliveryDate
     *
     * @return self
     */
    public function setDeliveryDate(?string $deliveryDate): self
    {
        if (! $deliveryDate || ! $this->canHaveExtraOption(self::EXTRA_OPTION_DELIVERY_DATE)) {
            $this->delivery_date = null;
            return $this;
        }

        $result = preg_match(self::DATE_REGEX, $deliveryDate, $matches);

        if ($result) {
            $deliveryDate .= ' 00:00:00';
        } else {
            $result = preg_match(self::DATE_TIME_REGEX, $deliveryDate, $matches);

            if (! $result) {
                throw new BadMethodCallException(
                    'Make sure the date (' . $deliveryDate . ') is correct, like pattern: YYYY-MM-DD HH:MM:SS' . json_encode(
                        $matches
                    )
                );
            }
        }

        $this->delivery_date = $deliveryDate;

        return $this;
    }

    /**
     * Deliver the package to the recipient only
     * Required: No.
     *
     * @param  bool $onlyRecipient
     *
     * @return self
     */
    public function setOnlyRecipient(bool $onlyRecipient): self
    {
        $this->only_recipient = $onlyRecipient && $this->canHaveShipmentOption(self::SHIPMENT_OPTION_ONLY_RECIPIENT);

        return $this;
    }

    /**
     * * Package must be signed for
     * Required: No.
     *
     * @param  bool $signature
     *
     * @return self
     */
    public function setSignature(bool $signature): self
    {
        $this->signature = $signature && $this->canHaveShipmentOption(self::SHIPMENT_OPTION_SIGNATURE);

        return $this;
    }

    /**
     * Return the package if the recipient is not home.
     *
     * @return null|bool
     */
    public function isReturn(): ?bool
    {
        return $this->return;
    }

    /**
     * Return the package if the recipient is not home
     * Required: No.
     *
     * @param  bool $return
     *
     * @return self
     */
    public function setReturn(bool $return): self
    {
        $this->return = $return && $this->canHaveShipmentOption(self::SHIPMENT_OPTION_RETURN);

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isSameDayDelivery(): ?bool
    {
        return $this->same_day_delivery;
    }

    /**
     * @param bool $sameDay
     *
     * @return $this
     */
    public function setSameDayDelivery(bool $sameDay): self
    {
        $this->same_day_delivery = $sameDay
            && in_array(self::SHIPMENT_OPTION_SAME_DAY_DELIVERY, $this->getAllowedShipmentOptions(), true);

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyRecipient(): ?bool
    {
        return $this->only_recipient;
    }

    /**
     * @return bool
     */
    public function isSignature(): ?bool
    {
        return $this->signature;
    }

    /**
     * @return boolean
     */
    public function isLargeFormat(): ?bool
    {
        return $this->large_format;
    }

    /**
     * @return bool
     */
    public function hasAgeCheck(): ?bool
    {
        return $this->age_check;
    }

    /**
     * Large format package
     * Required: No.
     *
     * @param  bool $largeFormat
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setLargeFormat(bool $largeFormat): self
    {
        $this->large_format = $largeFormat && $this->isPackage();

        return $this;
    }

    /**
     * Age check
     * Required: No.
     *
     * @param  bool $ageCheck
     *
     * @return AbstractConsignment
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setAgeCheck(bool $ageCheck): self
    {
        $this->age_check = $ageCheck && $this->isPackage();

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelDescription(): string
    {
        return $this->label_description;
    }

    /**
     * This description will appear on the shipment label
     * Note: This will be overridden for return shipment by the following: Retour – 3SMYPAXXXXXX
     * Required: No.
     *
     * @param  mixed $labelDescription
     *
     * @return self
     */
    public function setLabelDescription($labelDescription): self
    {
        $this->label_description = Str::limit((string) $labelDescription, self::LABEL_DESCRIPTION_MAX_LENGTH - 3);

        return $this;
    }

    /**
     * @return int
     */
    public function getInsurance(): int
    {
        return $this->insurance;
    }

    /**
     * Insurance price for the package.
     * Composite type containing integer and currency. The amount is without decimal separators.
     * Required: No.
     *
     * @param  int|null $insurance
     *
     * @return self
     * @throws \Exception
     */
    public function setInsurance(?int $insurance): self
    {
        if (! $insurance || ! $this->canHaveShipmentOption(self::SHIPMENT_OPTION_INSURANCE)) {
            $this->insurance = 0;
            return $this;
        }

        if (! in_array($insurance, $this->getLocalInsurancePossibilities(), true)
            && $this->getCountry() === $this->getLocalCountryCode()) {
            throw new BadMethodCallException(
                'Insurance must be one of ' . implode(', ', $this->getLocalInsurancePossibilities())
            );
        }

        $this->insurance = $insurance;

        return $this;
    }

    /**
     * Required: Yes for non-EU shipments and digital stamps.
     *
     * @param  array $physicalProperties
     *
     * @return self
     */
    public function setPhysicalProperties(array $physicalProperties): self
    {
        $this->physical_properties = $physicalProperties;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhysicalProperties()
    {
        return $this->physical_properties;
    }

    /**
     * @return int
     */
    public function getContents(): int
    {
        return $this->contents;
    }

    /**
     * The type of contents in the package.
     * The package contents are only needed in case of shipping outside EU,
     * this is mandatory info for customs form.
     * Pattern: [1 - 5]
     * Example: 1. commercial goods
     *          2. commercial samples
     *          3. documents
     *          4. gifts
     *          5. return shipment
     * Required: Yes for shipping outside EU.
     *
     * @param  int $contents
     *
     * @return self
     */
    public function setContents(int $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    /**
     * The invoice number for the commercial goods or samples of package contents.
     * Required: Yes for international shipments.
     *
     * @param  string $invoice
     *
     * @return self
     */
    public function setInvoice(string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return MyParcelCustomsItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * A CustomsItem objects with description in the package.
     * Required: Yes for international shipments.
     *
     * @param  \MyParcelNL\Sdk\src\Model\MyParcelCustomsItem $item
     *
     * @return self
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function addItem(MyParcelCustomsItem $item): self
    {
        $item->ensureFilled();

        $this->items[] = $item;

        return $this;
    }

    /**
     * The total weight for all items in whole grams.
     *
     * @return int
     */
    public function getTotalWeight(): int
    {
        if (! empty($this->getPhysicalProperties()['weight'])) {
            $weight = (int) ($this->getPhysicalProperties()['weight'] ?? null);

            if ($weight) {
                return $weight;
            }
        }

        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += $item->getWeight();
        }

        return $weight === 0 ? 1 : $weight;
    }

    /**
     * The weight has to be entered in grams.
     *
     * @param  int $weight
     *
     * @return self
     */
    public function setTotalWeight(int $weight): self
    {
        $this->setPhysicalProperties(['weight' => $weight]);

        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validate(): bool
    {
        $validator = ValidatorFactory::create($this->validatorClass);

        if ($validator) {
            try {
                $validator
                    ->validateAll($this)
                    ->report();
            } catch (ValidationException $e) {
                throw new Exception($e->getHumanMessage(), $e->getCode(), $e);
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function isPackage(): bool
    {
        if (! $this->getPackageType()) {
            throw new MissingFieldException('Set package_type before setting additional shipment options');
        }

        return self::PACKAGE_TYPE_PACKAGE === $this->getPackageType();
    }

    /**
     * The carrier's local country.
     *
     * @return string
     */
    abstract public function getLocalCountryCode(): string;

    /**
     * @return string[]
     */
    public function getAllowedDeliveryTypes(): array
    {
        return [
            self::DELIVERY_TYPE_STANDARD_NAME,
            self::DELIVERY_TYPE_PICKUP_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedExtraOptions(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAllowedPackageTypes(): array
    {
        return [
            self::PACKAGE_TYPE_PACKAGE_NAME,
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedShipmentOptions(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function getAllowedShipmentOptionsForPickup(): array
    {
        return [];
    }

    /**
     * Array of insurance possibilities for the local country.
     *
     * @return int[]
     */
    protected function getLocalInsurancePossibilities(): array
    {
        return [];
    }

    /**
     * @param  string $shipmentOption
     *
     * @return bool
     * @throws \MyParcelNL\Sdk\src\Exception\InvalidConsignmentException
     */
    private function validateShipmentOption(string $shipmentOption): bool
    {
        if (! $this->canHaveShipmentOption($shipmentOption)) {
            throw new InvalidConsignmentException("$shipmentOption is not allowed in " . static::class);
        }

        return true;
    }

    /**
     * @return null|int
     */
    final public function getCarrierId(): ?int
    {
        return $this->carrier ? $this->carrier->getId() : null;
    }

    /**
     * @return null|string
     */
    final public function getCarrierName(): ?string
    {
        return $this->carrier ? $this->carrier->getName() : null;
    }
}
