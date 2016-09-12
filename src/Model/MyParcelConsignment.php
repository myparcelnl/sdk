<?php
/**
 * A model of a consignment
 *
 * This model is used to send a shipment through an ORM and receive information about the shipment.
 *
 * LICENSE: This source file is subject to the Creative Commons License:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2016 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since release 0.1.0
 */
namespace myparcelnl\sdk\Model;


/**
 * A model of a consignment
 *
 * Class Consignment
 * @package myparcelnl\sdk\Model
 */
class MyParcelConsignment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var int
     */
    private $status = 0;

    /**
     * @var integer
     */
    private $shop_id;

    /**
     * @var string
     */
    private $cc = '';

    /**
     * @var string
     */
    private $city = '';

    /**
     * @var string
     */
    private $street = '';

    /**
     * @var integer
     */
    private $number;

    /**
     * @var string
     */
    private $number_suffix = '';

    /**
     * @var string
     */
    private $postal_code = '';

    /**
     * @var string
     */
    private $person = '';

    /**
     * @var string
     */
    private $company = '';

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $phone = '';

    /**
     * @var integer
     */
    private $package_type = 1;

    /**
     * @var integer
     */
    private $delivery_type = 2;

    /**
     * @var string
     */
    private $delivery_date;

    /**
     * @var string
     */
    private $delivery_remark;

    /**
     * @var boolean
     */
    private $only_recipient;

    /**
     * @var boolean
     */
    private $signature;

    /**
     * @var boolean
     */
    private $return;

    /**
     * @var boolean
     */
    private $large_format;

    /**
     * @var string
     */
    private $label_description = '';

    /**
     * @var array
     */
    private $insurance = 0;

    /**
     * @var int
     */
    private $contents;

    /**
     * @var string
     */
    private $invoice;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * The id of the consignment
     *
     * Save this id in your database
     *
     * @return $this
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
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
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
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
     * @return mixed
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * The shop id to which this shipment belongs
     *
     * When the store ID is not specified, the API will look at the API key.
     * Required: No
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
     * @return string
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
     * @return string
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
     * @return string
     */
    public function getStreet()
    {
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
        $this->street = $street;
        return $this;
    }

    /**
     * @return int
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
     * @return string
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
     * @return string
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
     * @return int
     */
    public function getPackageType()
    {
        return $this->package_type;
    }

    /**
     * The package type
     *
     * For international shipment only package type 1 is allowed
     * Pattern: [1 – 3]
     * Example: 1. package
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
     *
     * @return $this
     */
    public function setDeliveryType($delivery_type)
    {
        $this->delivery_type = $delivery_type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * The delivery date time for this shipment
     * Pattern: YYYY-MM-DD
     * Example: 2017-01-01
     * Required: Yes if delivery type has been specified
     *
     * @param string $delivery_date
     *
     * @return $this
     */
    public function setDeliveryDate($delivery_date)
    {
        $this->delivery_date = $delivery_date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryRemark()
    {
        return $this->delivery_remark;
    }

    /**
     * The delivery remark.
     *
     * Required: No
     *
     * @param string $delivery_remark
     *
     * @return $this
     */
    public function setDeliveryRemark($delivery_remark)
    {
        $this->delivery_remark = $delivery_remark;
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
     */
    public function setOnlyRecipient($only_recipient)
    {
        $this->only_recipient = $only_recipient;
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
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
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
     */
    public function setReturn($return)
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLargeFormat()
    {
        return $this->large_format;
    }

    /**
     * Large format package
     *
     * Required: No
     *
     * @param boolean $large_format
     *
     * @return $this
     */
    public function setLargeFormat($large_format)
    {
        $this->large_format = $large_format;
        return $this;
    }

    /**
     * @return mixed
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
     * @param mixed $label_description
     *
     * @return $this
     */
    public function setLabelDescription($label_description)
    {
        $this->label_description = $label_description;
        return $this;
    }

    /**
     * @return array
     */
    public function getInsurance()
    {
        return $this->insurance;
    }

    /**
     * Insurance price for the package.
     *
     * Composite type containing integer and currency. The amount is without decimal
     * separators (in cents).
     * Pattern: [50, 250, 500, 1000, 1500, (...) - 5000]
     * Required: No
     *
     * @param array $insurance
     *
     * @return $this
     */
    public function setInsurance($insurance)
    {
        $this->insurance = $insurance;
        return $this;
    }

    /**
     * @return mixed
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
     * Example  1. commercial goods
     *          2. commercial samples
     *          3. documents
     *          4. gifts
     *          5. return shipment
     * Required: Yes
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
     * Required: Yes for commercial goods, commercial samples and return shipment package contents.
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
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * An array containing CustomsItem objects with description for each item
     * in the package.
     *
     * Required: Yes for international shipments
     *
     * @param array $item
     */
    public function addItems($item)
    {
        $this->items[] = $item;
    }

}