<?php declare(strict_types=1);
/**
 * A model of a consignment
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\Model;


use InvalidArgumentException;
use MyParcelNL\Sdk\src\Concerns\HasCheckoutFields;
use MyParcelNL\Sdk\src\Helper\SplitStreet;
use MyParcelNL\Sdk\src\Support\Helpers;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;

/**
 * A model of a consignment
 *
 * Class Consignment
 */
class MyParcelConsignment
{
    use HasCheckoutFields;

    /**
     * Consignment types
     */
    const DELIVERY_TYPE_MORNING        = 1;
    const DELIVERY_TYPE_STANDARD       = 2;
    const DELIVERY_TYPE_EVENING        = 3;
    const DELIVERY_TYPE_PICKUP         = 4;
    const DELIVERY_TYPE_PICKUP_EXPRESS = 5;

    const DEFAULT_DELIVERY_TYPE = self::DELIVERY_TYPE_STANDARD;

    /**
     * Package types
     */
    const PACKAGE_TYPE_PACKAGE       = 1;
    const PACKAGE_TYPE_DIGITAL_STAMP = 4;

    const DEFAULT_PACKAGE_TYPE = self::PACKAGE_TYPE_PACKAGE;

    /**
     * Regular expression used to make sure the date is correct.
     */
    const DATE_REGEX        = '~(\d{4}-\d{2}-\d{2})$~';
    const DATE_TIME_REGEX   = '~(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})$~';
    const STATUS_CONCEPT    = 1;
    const MAX_STREET_LENGTH = 40;

    const CC_NL = 'NL';
    const CC_BE = 'BE';

    /** @deprecated Use MyParcelConsignment::DELIVERY_TYPE_EVENING */
    const DELIVERY_TYPE_NIGHT = self::DELIVERY_TYPE_EVENING;
    /** @deprecated Use MyParcelConsignment::DELIVERY_TYPE_PICKUP */
    const DELIVERY_TYPE_RETAIL = self::DELIVERY_TYPE_PICKUP;
    /** @deprecated Use MyParcelConsignment::DELIVERY_TYPE_PICKUP_EXPRESS */
    const DELIVERY_TYPE_RETAIL_EXPRESS = self::DELIVERY_TYPE_PICKUP_EXPRESS;
    /** @deprecated Use MyParcelConsignment::PACKAGE_TYPE_PACKAGE */
    const PACKAGE_TYPE_NORMAL = self::PACKAGE_TYPE_PACKAGE;

    /**
     * @internal
     * @var string
     */
    public $reference_identifier;

    /**
     * @internal
     * @var int
     */
    public $myparcel_consignment_id;

    /**
     * @internal
     * @var string|null
     */
    public $api_key;

    /**
     * @var bool
     */
    private $partOfMultiCollo = false;

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
     * @var integer
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
    public $delivery_type = MyParcelConsignment::DEFAULT_DELIVERY_TYPE;

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
    public $contents;

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
     * @var string
     */
    public $pickup_network_id = '';

    /**
     * @var array
     */
    private $insurance_possibilities = [0, 50, 100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000];

    /**
     * @var Helpers
     */
    private $helper;

    public function __construct()
    {
        $this->helper = new Helpers();
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
    public function setReferenceId($reference_identifier)
    {
        if ($reference_identifier !== null) {
            $this->reference_identifier = (string) $reference_identifier;
        }

        return $this;
    }

    /**
     * The id of the consignment
     *
     * Save this id in your database
     *
     * @return int
     */
    public function getMyParcelConsignmentId()
    {
        return $this->myparcel_consignment_id;
    }

    /**
     * @internal
     *
     * The id of the consignment
     *
     * @return $this
     *
     * @param int $id
     */
    public function setMyParcelConsignmentId($id)
    {
        $this->myparcel_consignment_id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set the api key for each shipment.
     *
     * The key must be given to each shipment. So you can create multiple shipments
     * in one time for different shops. This way you will not have to ask for the
     * shop ID. The field shop ID is therefore not necessary.
     * Required: Yes
     *
     * @param string $apiKey
     *
     * @return $this
     * @throws \Exception
     */
    public function setApiKey($apiKey)
    {
        if (! is_string($apiKey)) {
            throw new \Exception('Api key need to be a type of sting');
        }

        $this->api_key = $apiKey;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return MyParcelConsignment
     */
    public function setMultiCollo($value = true)
    {
        $this->partOfMultiCollo = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPartOfMultiCollo()
    {
        return $this->partOfMultiCollo;
    }


    /**
     * @return string|null
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @internal
     *
     * @param null $barcode
     *
     * @return $this
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Get the status of the consignment
     *
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Status of the consignment
     *
     * @internal
     *
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * @internal
     *
     * The shop id to which this shipment belongs
     *
     * When the store ID is not specified, the API will look at the API key.
     * Required: No
     *
     * @internal
     *
     * @param mixed $shop_id
     *
     * @return $this
     */
    public function setShopId($shop_id)
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
     *
     * ISO3166-1 alpha2 country code<br>
     * <br>
     * Pattern: [A-Z]{2,2}<br>
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
     * @todo move to hasCountry
     *
     * @return bool
     */
    public function isCdCountry()
    {
        return false === $this->isEuCountry();
    }

    /**
     * Check if the address is inside the EU
     *
     * @todo move to hasCountry
     *
     * @return bool
     */
    public function isEuCountry()
    {
        return in_array(
            $this->getCountry(),
            array(
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
            )
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
     *
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
     * @var bool
     * @return string|null
     */
    public function getStreet($useStreetAdditionalInfo = false)
    {
        if ($useStreetAdditionalInfo && strlen($this->street) >= self::MAX_STREET_LENGTH) {
            $streetParts = SplitStreet::getStreetParts($this->street);

            return $streetParts[0];
        }

        return $this->street;
    }

    /**
     * The address street name
     *
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
     * @todo move to hasStreet
     *
     * @return string
     */
    public function getStreetAdditionalInfo()
    {
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
    public function setStreetAdditionalInfo($street_additional_info)
    {
        $this->street_additional_info = $street_additional_info;

        return $this;
    }

    /**
     * Get entire street
     *
     * @todo move to hasCountry
     *
     * @var bool
     *
     * @return string Entire street
     */
    public function getFullStreet($useStreetAdditionalInfo = false)
    {
        $fullStreet = $this->getStreet($useStreetAdditionalInfo);

        if ($this->getNumber()) {
            $fullStreet .= ' ' . $this->getNumber();
        }

        if ($this->getNumberSuffix()) {
            $fullStreet .= ' ' . $this->getNumberSuffix();
        }

        return trim($fullStreet);
    }

    /**
     * Splitting a full NL address and save it in this object
     *
     * Required: Yes or use setStreet()
     *
     * @param $fullStreet
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setFullStreet($fullStreet)
    {
        if ($this->getCountry() === null) {
            throw new MissingFieldException('First set the country code with setCountry() before running setFullStreet()');
        }

        if ($this->getCountry() == MyParcelConsignment::CC_NL) {
            $streetData = SplitStreet::splitStreet($fullStreet);
            $this->setStreet($streetData['street']);
            $this->setNumber($streetData['number']);
            $this->setNumberSuffix($streetData['number_suffix']);
        } else {
            $this->setStreet($fullStreet);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Street number
     *
     * Whole numeric value
     * Pattern: [0-9]+
     * Example: 10. 20. NOT 2,3
     * Required: Yes for NL
     *
     * @param int $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumberSuffix()
    {
        return $this->number_suffix;
    }

    /**
     * Street number suffix.
     *
     * Required: no
     *
     * @param string $number_suffix
     *
     * @return $this
     */
    public function setNumberSuffix($number_suffix)
    {
        $this->number_suffix = $number_suffix;

        return $this;
    }

    /**
     * Check if address is correct
     * Only for Dutch addresses
     *
     * @param $fullStreet
     *
     * @return bool
     */
    public function isCorrectAddress($fullStreet)
    {
        $result = preg_match(SplitStreet::SPLIT_STREET_REGEX, $fullStreet, $matches);

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
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * The address postal code
     *
     * Required: Yes for NL and EU destinations except for IE
     *
     * @param string $postal_code
     *
     * @return $this
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * The person at this address
     *
     * Required: Yes
     *
     * @param string $person
     *
     * @return $this
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Company name
     *
     * Required: no
     *
     * @param string $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * The address email
     *
     * Required: no
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * The address phone
     *
     * Required: no
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPackageType()
    {
        return $this->package_type;
    }

    /**
     * The package type
     *
     * For international shipment only package type 1 is allowed
     * Pattern: [1 – 3]<br>
     * Example:
     *          1. package
     *          2. mailbox package
     *          3. letter
     * Required: Yes
     *
     * @param int $package_type
     *
     * @return $this
     */
    public function setPackageType($package_type)
    {
        $this->package_type = $package_type;

        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    /**
     * The delivery type for the package
     *
     * Required: Yes if delivery_date has been specified
     *
     * @param int $delivery_type
     * @param bool $needDeliveryDate
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setDeliveryType($delivery_type, $needDeliveryDate = true)
    {
        if ($needDeliveryDate &&
            $delivery_type !== self::DELIVERY_TYPE_STANDARD &&
            $this->getDeliveryDate() == null
        ) {
            throw new MissingFieldException('If delivery type !== 2, first set delivery date with setDeliveryDate() before running setDeliveryType() for shipment: ' . $this->myparcel_consignment_id);
        }

        $this->delivery_type = $delivery_type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * The delivery date time for this shipment
     * Pattern: YYYY-MM-DD | YYYY-MM-DD HH:MM:SS
     * Example: 2017-01-01 | 2017-01-01 00:00:00
     * Required: Yes if delivery type has been specified
     *
     * @param string $delivery_date
     *
     * @return $this
     * @throws \Exception
     */
    public function setDeliveryDate($delivery_date)
    {

        $result = preg_match(self::DATE_REGEX, $delivery_date, $matches);

        if ($result) {
            $delivery_date = (string) $delivery_date . ' 00:00:00';
        } else {
            $result = preg_match(self::DATE_TIME_REGEX, $delivery_date, $matches);

            if (! $result) {
                throw new InvalidArgumentException('Make sure the date (' . $delivery_date . ') is correct, like pattern: YYYY-MM-DD HH:MM:SS' . json_encode($matches));
            }
        }

        if (new \DateTime() > new \DateTime($delivery_date)) {
            $datetime = new \DateTime();
            $datetime->modify('+1 day');
            $delivery_date = $datetime->format('Y\-m\-d\ h:i:s');
        }

        $this->delivery_date = (string) $delivery_date;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isOnlyRecipient()
    {
        return $this->only_recipient;
    }

    /**
     * Deliver the package to the recipient only
     *
     * Required: No
     *
     * @param boolean $only_recipient
     *
     * @return $this
     * @throws \Exception
     */
    public function setOnlyRecipient($only_recipient)
    {
        $this->only_recipient = $this->canHaveOption($only_recipient);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSignature()
    {
        return $this->signature;
    }

    /**
     * Package must be signed for
     *
     * Required: No
     *
     * @param boolean $signature
     *
     * @return $this
     * @throws \Exception
     */
    public function setSignature($signature)
    {
        $this->signature = $this->canHaveOption($signature);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isReturn()
    {
        return $this->return;
    }

    /**
     * Return the package if the recipient is not home
     *
     * Required: No
     *
     * @param boolean $return
     *
     * @return $this
     * @throws \Exception
     */
    public function setReturn($return)
    {
        $this->return = $this->canHaveOption($return);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isLargeFormat()
    {
        return (bool) $this->large_format;
    }

    /**
     * @return boolean
     */
    public function hasAgeCheck()
    {
        return (bool) $this->age_check;
    }

    /**
     * Large format package
     *
     * Required: No
     *
     * @param boolean $large_format
     *
     * @return $this
     * @throws \Exception
     */
    public function setLargeFormat($large_format)
    {
        $this->large_format = $this->canHaveOption($large_format);

        return $this;
    }

    /**
     * Age check
     *
     * Required: No
     *
     * @param boolean $age_check
     *
     * @return $this
     * @throws \Exception
     */
    public function setAgeCheck($age_check)
    {
        $this->age_check = $this->canHaveOption($age_check);

        return $this;
    }

    /**
     * @return string
     */
    public function getLabelDescription()
    {
        return $this->label_description;
    }

    /**
     * This description will appear on the shipment label
     *
     * Note: This will be overridden for return shipment by the following: Retour – 3SMYPAMYPAXXXXXX
     *
     * Required: No
     *
     * @param string $label_description
     *
     * @return $this
     */
    public function setLabelDescription($label_description)
    {
        $this->label_description = (string) $label_description;

        return $this;
    }

    /**
     * @return int
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * Insurance price for the package.
     *
     * Composite type containing integer and currency. The amount is without decimal
     * separators.
     * Pattern: [0, 50, 100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000]
     * Required: No
     *
     * @param int $insurance
     *
     * @return $this
     * @throws InvalidArgumentException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setInsurance($insurance)
    {
        if (! in_array($insurance, $this->insurance_possibilities) && $this->getCountry() == self::CC_NL) {
            throw new InvalidArgumentException('Insurance must be one of [0, 50, 100, 250, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000]');
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
     * @return MyParcelConsignment
     */
    public function setPhysicalProperties($physical_properties)
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
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * The type of contents in the package.
     *
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
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * The invoice number for the commercial goods or samples of package contents.
     *
     * Required: Yes for international shipments
     *
     * @param string $invoice
     *
     * @return $this
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return MyParcelCustomsItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * A CustomsItem objects with description in the package.
     *
     * Required: Yes for international shipments
     *
     * @param MyParcelCustomsItem $item
     *
     * @return $this
     * @throws \Exception
     */
    public function addItem($item)
    {
        $item->ensureFilled();

        $this->items[] = $item;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupPostalCode()
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
     * @return MyParcelConsignment
     */
    public function setPickupPostalCode($pickup_postal_code)
    {
        $this->pickup_postal_code = $pickup_postal_code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupStreet()
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
     * @return MyParcelConsignment
     */
    public function setPickupStreet($pickup_street)
    {
        $this->pickup_street = $pickup_street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupCity()
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
     * @return MyParcelConsignment
     */
    public function setPickupCity($pickup_city)
    {
        $this->pickup_city = $pickup_city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupNumber()
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
     * @return MyParcelConsignment
     */
    public function setPickupNumber($pickup_number)
    {
        $this->pickup_number = (string) $pickup_number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPickupLocationName()
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
     * @return MyParcelConsignment
     */
    public function setPickupLocationName($pickup_location_name)
    {
        $this->pickup_location_name = $pickup_location_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPickupLocationCode()
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
     * @return MyParcelConsignment
     */
    public function setPickupLocationCode($pickup_location_code)
    {
        $this->pickup_location_code = $pickup_location_code;

        return $this;
    }
    /**
     * @return string
     */
    public function getPickupNetworkId()
    {
        return $this->pickup_network_id;
    }

    /**
     * Pattern:  [0-9A-Za-z]
     * Example:  Albert Heijn
     * Required: Yes for pickup location
     *
     * @param string $pickup_network_id
     *
     * @return MyParcelConsignment
     */
    public function setPickupNetworkId($pickup_network_id)
    {
        $this->pickup_network_id = $pickup_network_id;

        return $this;
    }

    /**
     * The total weight for all items in whole grams
     *
     * @return int
     */
    public function getTotalWeight()
    {
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
     * Only package type 1 can have extra options
     *
     * @param $option
     *
     * @return bool
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    private function canHaveOption($option = true)
    {
        if ($this->getPackageType() === null) {
            throw new MissingFieldException('Set package type before ' . $option);
        }

        return $this->getPackageType() == MyParcelConsignment::PACKAGE_TYPE_PACKAGE ? $option : false;
    }
}
