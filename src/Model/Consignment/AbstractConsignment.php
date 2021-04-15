<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Consignment;

use MyParcelNL\Sdk\src\Concerns\HasCheckoutFields;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Helper\SplitStreet;
use MyParcelNL\Sdk\src\Helper\TrackTraceUrl;
use MyParcelNL\Sdk\src\Helper\ValidatePostalCode;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use MyParcelNL\Sdk\src\Support\Helpers;

/**
 * A model of a consignment
 * Class Consignment
 */
class AbstractConsignment
{
    use HasCheckoutFields;

    /**
     * Consignment types
     */
    public const DELIVERY_TYPE_MORNING  = 1;
    public const DELIVERY_TYPE_STANDARD = 2;
    public const DELIVERY_TYPE_EVENING  = 3;
    public const DELIVERY_TYPE_PICKUP   = 4;

    /**
     * @deprecated Since November 2019 is it no longer possible to use pickup express.
     */
    public const DELIVERY_TYPE_PICKUP_EXPRESS = 5;

    public const DELIVERY_TYPE_MORNING_NAME  = "morning";
    public const DELIVERY_TYPE_STANDARD_NAME = "standard";
    public const DELIVERY_TYPE_EVENING_NAME  = "evening";
    public const DELIVERY_TYPE_PICKUP_NAME   = "pickup";

    /**
     * @deprecated Since November 2019 is it no longer possible to use pickup express.
     */
    public const DELIVERY_TYPE_PICKUP_EXPRESS_NAME = "pickup_express";

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
     * Customs declaration types
     */
    public const PACKAGE_CONTENTS_COMMERCIAL_GOODS   = 1;
    public const PACKAGE_CONTENTS_COMMERCIAL_SAMPLES = 2;
    public const PACKAGE_CONTENTS_DOCUMENTS          = 3;
    public const PACKAGE_CONTENTS_GIFTS              = 4;
    public const PACKAGE_CONTENTS_RETRUN_SHIPMENT    = 5;

    /**
     * Package types
     */
    public const PACKAGE_TYPE_PACKAGE       = 1;
    public const PACKAGE_TYPE_MAILBOX       = 2;
    public const PACKAGE_TYPE_LETTER        = 3;
    public const PACKAGE_TYPE_DIGITAL_STAMP = 4;

    public const PACKAGE_TYPE_PACKAGE_NAME       = "package";
    public const PACKAGE_TYPE_MAILBOX_NAME       = "mailbox";
    public const PACKAGE_TYPE_LETTER_NAME        = "letter";
    public const PACKAGE_TYPE_DIGITAL_STAMP_NAME = "digital_stamp";

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
     */
    public const INSURANCE_POSSIBILITIES_LOCAL = [];

    /**
     * @var string
     */
    protected $local_cc = '';

    /**
     * @internal
     * @var string
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
    public $api_key;

    /**
     * @internal
     * @var string|null
     */
    public $barcode;

    /**
     * @internal
     * @var int
     */
    public $status = null;

    /**
     * @internal
     * @var integer
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
     * @var integer
     */
    public $package_type;

    /**
     * @internal
     * @var integer
     */
    public $delivery_type = self::DEFAULT_DELIVERY_TYPE;

    /**
     * @internal
     * @var string
     */
    public $delivery_date;

    /**
     * @internal
     * @var boolean
     */
    public $only_recipient;

    /**
     * @internal
     * @var boolean
     */
    public $signature;

    /**
     * @internal
     * @var boolean
     */
    public $return;

    /**
     * @internal
     * @var boolean
     */
    public $large_format;

    /**
     * @internal
     * @var boolean
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
     * @internal
     * @var string
     */
    public $pickup_cc;

    /**
     * @internal
     * @var string
     */
    public $pickup_postal_code;

    /**
     * @internal
     * @var string
     */
    public $pickup_street;

    /**
     * @internal
     * @var string
     */
    public $pickup_city;

    /**
     * @internal
     * @var string
     */
    public $pickup_number;

    /**
     * @internal
     * @var string
     */
    public $pickup_location_name;

    /**
     * @internal
     * @var string
     */
    public $pickup_location_code = '';

    /**
     * @internal
     * @var null|string
     */
    public $retail_network_id;

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
     * @var Helpers
     */
    private $helper;

    public function __construct()
    {
        $this->helper = new Helpers();
    }

    /**
     * @return array
     */
    public function getInsurancePossibilities(): array
    {
        return static::INSURANCE_POSSIBILITIES_LOCAL;
    }

    /**
     * @return string|null
     */
    public function getReferenceId()
    {
        return $this->reference_identifier;
    }

    /**
     * @param mixed $reference_identifier
     *
     * @return $this
     */
    public function setReferenceId(?string $reference_identifier): self
    {
        if ($reference_identifier !== null) {
            $this->reference_identifier = (string) $reference_identifier;
        }

        return $this;
    }

    /**
     * The id of the consignment
     * Save this id in your database
     *
     * @return int|null
     */
    public function getConsignmentId(): ?int
    {
        return $this->consignment_id;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     * @internal
     * The id of the consignment
     */
    public function setConsignmentId(?int $id): self
    {
        $this->consignment_id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    /**
     * Set the api key for each shipment.
     * The key must be given to each shipment. So you can create multiple shipments
     * in one time for different shops. This way you will not have to ask for the
     * shop ID. The field shop ID is therefore not necessary.
     * Required: Yes
     *
     * @param string $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->api_key = $apiKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getCarrierId(): int
    {
        /** @noinspection PhpUndefinedClassConstantInspection */
        return static::CARRIER_ID;
    }

    /**
     * @param bool $value
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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
     * @param string|null $barcode
     *
     * @return $this
     * @internal
     */
    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

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
     *          99 inactive - unknown
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Status of the consignment
     *
     * @param int $status
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @internal
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getShopId(): int
    {
        return $this->shop_id;
    }

    /**
     * @param mixed $shop_id
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @internal
     * The shop id to which this shipment belongs
     * When the store ID is not specified, the API will look at the API key.
     * Required: No
     * @internal
     */
    public function setShopId($shop_id): self
    {
        $this->shop_id = $shop_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->cc;
    }

    /**
     * The address country code
     * ISO3166-1 alpha2 country code<br>
     * <br>
     * Pattern: [A-Z]{2}<br>
     * Example: NL, BE, CW<br>
     * Required: Yes
     *
     * @param string $cc
     *
     * @return $this
     */
    public function setCountry($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * Check if the address is outside the EU
     *
     * @return bool
     * @todo move to hasCountry trait maken
     */
    public function isCdCountry()
    {
        return false === $this->isEuCountry();
    }

    /**
     * Check if the address is inside the EU
     *
     * @return bool
     * @todo move to hasCountry
     */
    public function isEuCountry(): bool
    {
        return in_array(
            $this->getCountry(),
            self::EURO_COUNTRIES
        );
    }

    /**
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * The address city
     * Required: Yes
     *
     * @param string $city
     *
     * @return $this
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
     * Required: Yes or use setFullStreet()
     *
     * @param string $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = trim(str_replace('\n', ' ', $street));

        return $this;
    }

    /**
     * Get additional information for the street that should not be included in the street field
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
     * Required: No
     *
     * @param string $street_additional_info
     *
     * @return $this
     */
    public function setStreetAdditionalInfo(string $street_additional_info): self
    {
        $this->street_additional_info = $street_additional_info;

        return $this;
    }

    /**
     * Get entire street
     *
     * @return string Entire street
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
            $fullStreet .= ' ' . splitstreet::BOX_NL . ' ' . $this->getBoxNumber();
        }

        if ($this->getNumberSuffix()) {
            $fullStreet .= ' ' . $this->getNumberSuffix();
        }

        return trim($fullStreet);
    }

    /**
     * Splitting a full NL address and save it in this object
     * Required: Yes or use setStreet()
     *
     * @param string $fullStreet
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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

        if (empty($this->local_cc)) {
            throw new \BadMethodCallException('Can not create a shipment when the local country code is empty.');
        }
        $fullStreet = SplitStreet::splitStreet($fullStreet, $this->local_cc, $this->getCountry());
        $this->setStreet($fullStreet->getStreet());
        $this->setNumber($fullStreet->getNumber());
        $this->setBoxNumber($fullStreet->getBoxNumber());
        $this->setNumberSuffix($fullStreet->getNumberSuffix());

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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
     * @param string $barcode
     * @param string $postalCode
     * @param string $countryCode
     *
     * @return string
     */
    public function getBarcodeUrl(string $barcode, string $postalCode, string $countryCode): string
    {
        $barcodeUrl = TrackTraceUrl::create($barcode, $postalCode, $countryCode);

        return $barcodeUrl;
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
     * Required: Yes for NL
     *
     * @param mixed $number
     *
     * @return $this
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
     * Required: no
     *
     * @param string|null $numberSuffix
     *
     * @return $this
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
     * Required: no
     *
     * @param string|null $boxNumber
     *
     * @return $this
     */
    public function setBoxNumber(?string $boxNumber): self
    {
        $this->box_number = $boxNumber;

        return $this;
    }

    /**
     * @param array $consignmentEncoded
     *
     * @return array
     */
    public function encodeStreet(array $consignmentEncoded): array
    {
        $consignmentEncoded['recipient']['street']                 = $this->getFullStreet(true);
        $consignmentEncoded['recipient']['street_additional_info'] = $this->getStreetAdditionalInfo();

        return $consignmentEncoded;
    }

    /**
     * Check if address is correct
     * Only for Dutch addresses
     *
     * @param $fullStreet
     *
     * @return bool
     */
    public function isCorrectAddress(string $fullStreet): bool
    {
        $localCountry       = $this->local_cc;
        $destinationCountry = $this->getCountry();

        return SplitStreet::isCorrectStreet($fullStreet, $localCountry, $destinationCountry);
    }

    /**
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param string $postalCode
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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
        if (empty($this->local_cc)) {
            throw new \BadMethodCallException('Can not create a shipment when the local country code is empty.');
        }

        if (! ValidatePostalCode::validate($postalCode, $this->getCountry())) {
            throw new \BadMethodCallException('Invalid postal code');
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
     * Required: Yes
     *
     * @param string $person
     *
     * @return $this
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
     * Required: no
     *
     * @param string|null $company
     *
     * @return $this
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * The address email
     * Required: no
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
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
     * Required: no
     *
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone): ?self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param int|null $default
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
     * Pattern: [1 – 3]<br>
     * Example:
     *          1. package
     *          2. mailbox package
     *          3. letter
     * Required: Yes
     *
     * @param int $packageType
     *
     * @return $this
     */
    public function setPackageType(int $packageType): self
    {
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
     * Required: Yes if delivery_date has been specified
     *
     * @param int  $deliveryType
     * @param bool $needDeliveryDate
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setDeliveryType(int $deliveryType, bool $needDeliveryDate = false): self
    {
        $this->delivery_type = $deliveryType;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
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
     * Required: Yes if delivery type has been specified
     *
     * @param string|null $delivery_date
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \BadMethodCallException
     */
    public function setDeliveryDate(?string $delivery_date): self
    {
        if (! $delivery_date) {
            $this->delivery_date = null;

            return $this;
        }

        $result = preg_match(self::DATE_REGEX, $delivery_date, $matches);

        if ($result) {
            $delivery_date = (string) $delivery_date . ' 00:00:00';
        } else {
            $result = preg_match(self::DATE_TIME_REGEX, $delivery_date, $matches);

            if (! $result) {
                throw new \BadMethodCallException(
                    'Make sure the date ('
                    . $delivery_date
                    . ') is correct, like pattern: YYYY-MM-DD HH:MM:SS'
                    . json_encode($matches)
                );
            }
        }

        $this->delivery_date = (string) $delivery_date;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnlyRecipient(): bool
    {
        return false;
    }

    /**
     * Deliver the package to the recipient only
     * Required: No
     *
     * @param bool $only_recipient
     *
     * @return $this
     */
    public function setOnlyRecipient(bool $only_recipient): self
    {
        if ($only_recipient) {
            throw new \BadMethodCallException('Only recipient has to be false in ' . static::class);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSignature(): bool
    {
        return false;
    }

    /**
     * * Package must be signed for
     * Required: No
     *
     * @param bool $signature
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setSignature(bool $signature): self
    {
        if ($signature) {
            throw new \BadMethodCallException('Signature has to be false in ' . static::class);
        }

        return $this;
    }

    /**
     * Return the package if the recipient is not home
     *
     * @return boolean
     */
    public function isReturn()
    {
        return $this->return;
    }

    /**
     * Return the package if the recipient is not home
     * Required: No
     *
     * @param bool $return
     *
     * @return $this
     * @throws \Exception
     */
    public function setReturn(bool $return): self
    {
        $this->return = $this->canHaveOption($return);

        return $this;
    }

    /**
     * @return bool
     */
    public function isLargeFormat(): bool
    {
        return false;
    }

    /**
     * Large format package
     * Required: No
     *
     * @param bool $largeFormat
     *
     * @return $this
     */
    public function setLargeFormat(bool $largeFormat): self
    {
        if ($largeFormat) {
            throw new \BadMethodCallException('Large format has to be false in ' . static::class);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAgeCheck(): bool
    {
        return false;
    }

    /**
     * Age check
     * Required: No
     *
     * @param bool $ageCheck
     *
     * @return AbstractConsignment
     */
    public function setAgeCheck(bool $ageCheck): self
    {
        if ($ageCheck) {
            throw new \BadMethodCallException('Age check has to be false in ' . static::class);
        }

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
     * Note: This will be overridden for return shipment by the following: Retour – 3SMYPAMYPAXXXXXX
     * Required: No
     *
     * @param mixed $label_description
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setLabelDescription($label_description): self
    {
        $this->label_description = (string) $label_description;

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
     * Required: No
     *
     * @param int|null $insurance
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @throws \Exception
     */
    public function setInsurance(?int $insurance): self
    {
        if (null === $insurance) {
            $this->insurance = null;

            return $this;
        }

        if (empty(static::INSURANCE_POSSIBILITIES_LOCAL)) {
            throw new \BadMethodCallException('Property insurance_possibilities_local not found in ' . static::class);
        }

        if (empty($this->local_cc)) {
            throw new \BadMethodCallException('Property local_cc not found in ' . static::class);
        }

        if (! in_array($insurance, static::INSURANCE_POSSIBILITIES_LOCAL) && $this->getCountry() == $this->local_cc) {
            throw new \BadMethodCallException(
                'Insurance must be one of ' . implode(', ', static::INSURANCE_POSSIBILITIES_LOCAL)
            );
        }

        if (! $this->canHaveOption()) {
            $insurance = 0;
        }

        $this->insurance = $insurance;

        return $this;
    }

    /**
     * Required: Yes for non-EU shipments and digital stamps
     *
     * @param array $physical_properties
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPhysicalProperties(array $physical_properties): self
    {
        $this->physical_properties = $physical_properties;

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
     * @return integer
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
     * Required: Yes for shipping outside EU
     *
     * @param int $contents
     *
     * @return $this
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
     * Required: Yes for international shipments
     *
     * @param string $invoice
     *
     * @return $this
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
     * Required: Yes for international shipments
     *
     * @param \MyParcelNL\Sdk\src\Model\MyParcelCustomsItem $item
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function addItem(MyParcelCustomsItem $item): self
    {
        $item->ensureFilled();

        $this->items[] = $item;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupCountry(): ?string
    {
        return $this->pickup_cc;
    }

    /**
     * @param string $pickupCountry
     *
     * @return AbstractConsignment
     */
    public function setPickupCountry(string $pickupCountry): self
    {
        $this->pickup_cc = $pickupCountry;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupPostalCode(): ?string
    {
        return $this->pickup_postal_code;
    }

    /**
     * Pattern:  d{4}\s?[A-Z]{2}
     * Example:  2132BH
     * Required: Yes for pickup location
     *
     * @param string $pickup_postal_code
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupPostalCode(string $pickup_postal_code): self
    {
        $this->pickup_postal_code = $pickup_postal_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupStreet(): ?string
    {
        return $this->pickup_street;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Burgemeester van Stamplein
     * Required: Yes for pickup location
     *
     * @param string $pickup_street
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupStreet(string $pickup_street): self
    {
        $this->pickup_street = $pickup_street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupCity(): ?string
    {
        return $this->pickup_city;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Hoofddorp
     * Required: Yes for pickup location
     *
     * @param string $pickup_city
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupCity(string $pickup_city): self
    {
        $this->pickup_city = $pickup_city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupNumber(): ?string
    {
        return $this->pickup_number;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  270
     * Required: Yes for pickup location
     *
     * @param string $pickup_number
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupNumber(string $pickup_number): self
    {
        $this->pickup_number = (string) $pickup_number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupLocationName(): ?string
    {
        return $this->pickup_location_name;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $pickup_location_name
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupLocationName(string $pickup_location_name): self
    {
        $this->pickup_location_name = $pickup_location_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPickupLocationCode(): string
    {
        return $this->pickup_location_code;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $pickup_location_code
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setPickupLocationCode($pickup_location_code): self
    {
        $this->pickup_location_code = $pickup_location_code;

        return $this;
    }

    /**
     * @return null|string
     * @deprecated Use getRetailNetworkId instead
     *
     */
    public function getPickupNetworkId(): ?string
    {
        return $this->getRetailNetworkId();
    }

    /**
     * @return null|string
     */
    public function getRetailNetworkId(): ?string
    {
        return $this->retail_network_id;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $retailNetworkId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     * @deprecated Use setRetailNetworkId instead
     */
    public function setPickupNetworkId($retailNetworkId): self
    {
        if (! empty($retailNetworkId)) {
            throw new \BadMethodCallException('Pickup network id has to be empty in ' . static::class);
        }

        return $this;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $retailNetworkId
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setRetailNetworkId(string $retailNetworkId): self
    {
        if (! empty($retailNetworkId)) {
            throw new \BadMethodCallException('Retail network id has to be empty in ' . static::class);
        }

        return $this;
    }

    /**
     * The total weight for all items in whole grams
     *
     * @return int
     */
    public function getTotalWeight(): int
    {
        if (! empty($this->getPhysicalProperties()['weight'])) {
            $weight = (int) $this->getPhysicalProperties()['weight'] ?? null;
            if ($weight) {
                return $weight;
            }
        }

        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += $item->getWeight();
        }

        if ($weight == 0) {
            $weight = 1;
        }

        return $weight;
    }

    /**
     * The weight has to be entered in grams
     *
     * @param int $weight
     *
     * @return \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    public function setTotalWeight(int $weight): self
    {
        $this->setPhysicalProperties(['weight' => $weight]);

        return $this;
    }

    /**
     * Only package type 1 can have extra options
     *
     * @param $option
     *
     * @return bool
     * @throws MissingFieldException
     */
    protected function canHaveOption(bool $option = true): bool
    {
        if ($this->getPackageType() === null) {
            throw new MissingFieldException('Set package type before ' . $option);
        }

        return $this->getPackageType() == self::PACKAGE_TYPE_PACKAGE ? $option : false;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}
