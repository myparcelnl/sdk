<?php
/**
 * The repository of a MyParcel consignment
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
namespace MyParcelNL\Sdk\src\Model\Repository;


use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;

/**
 * The repository of a MyParcel consignment
 *
 * Class MyParcelConsignmentRepository
 * @package MyParcelNL\Sdk\Model\Repository
 */
class MyParcelConsignmentRepository extends MyParcelConsignment
{
    /**
     * Regular expression used to split street name from house number.
     *
     * This regex goes from right to left
     * Contains php keys to store the data in an array
     */
    const SPLIT_STREET_REGEX =  '~(?P<street>.*?)'.                  // The rest belongs to the street
                                '\s?'.                               // Separator between street and number
                                '(?P<number>\d{1,4})'.               // Number can contain a maximum of 4 numbers
                                '[/\s\-]{0,2}'.                      // Separators between number and addition
                                '(?P<number_suffix>'.
                                    '[a-zA-Z]{1}\d{1,3}|'.           // Numbers suffix starts with a letter followed by numbers or
                                    '-\d{1,4}|'.                     // starts with - and has up to 4 numbers or
                                    '\d{2}\w{1,2}|'.                 // starts with 2 numbers followed by letters or
                                    '[a-zA-Z]{1}[a-zA-Z\s]{0,3}'.    // has up to 4 letters with a space
                                ')?$~';

    /**
     * Consignment types
     */
    const DELIVERY_TYPE_MORNING             = 1;
    const DELIVERY_TYPE_STANDARD            = 2;
    const DELIVERY_TYPE_NIGHT               = 3;
    const DELIVERY_TYPE_RETAIL              = 4;
    const DELIVERY_TYPE_RETAIL_EXPRESS      = 5;

    const DEFAULT_DELIVERY_TYPE = self::DELIVERY_TYPE_STANDARD;

    const PACKAGE_TYPE_NORMAL = 1;
    const PACKAGE_TYPE_DIGITAL_STAMP = 4;

    const DEFAULT_PACKAGE_TYPE = self::PACKAGE_TYPE_NORMAL;

    /**
     * @var array
     */
    private $consignmentEncoded = [];

    /**
     * Get entire street
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
     * @throws \Exception
     */
    public function setFullStreet($fullStreet)
    {
        if ($this->getCountry() === null) {
            throw new \Exception('First set the country code with setCountry() before running setFullStreet()');
        }

        if ($this->getCountry() == MyParcelConsignment::CC_NL) {
            $streetData = $this->splitStreet($fullStreet);
            $this->setStreet($streetData['street']);
            $this->setNumber($streetData['number']);
            $this->setNumberSuffix($streetData['number_suffix']);
        } else {
            $this->setStreet($fullStreet);
        }
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
            $weight += ($item->getWeight());
        }

        if ($weight == 0) {
            $weight = 1;
        }

        return $weight;
    }

    /**
     * Encode all the data before sending it to MyParcel
     *
     * @return array
     * @throws \Exception
     */
    public function apiEncode()
    {
        $this
            ->encodeBaseOptions()
            ->encodeStreet()
            ->encodeExtraOptions()
            ->encodeCdCountry();

        return $this->consignmentEncoded;
    }

    /**
     * Decode all the data after the request with the API
     *
     * @param $data
     *
     * @return $this
     * @throws \Exception
     */
    public function apiDecode($data)
    {
        $this
            ->decodeBaseOptions($data)
            ->decodeExtraOptions($data)
            ->decodeRecipient($data)
            ->decodePickup($data);

        return $this;
    }

    /**
     * Get delivery type from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     * @return int
     * @throws \Exception
     */
    public function getDeliveryTypeFromCheckout($checkoutData)
    {
        if ($checkoutData === null) {
            return self::DELIVERY_TYPE_STANDARD;
        }

        $aCheckoutData = json_decode($checkoutData, true);
        $deliveryType = self::DELIVERY_TYPE_STANDARD;

        if (key_exists('time', $aCheckoutData) &&
            key_exists('price_comment', $aCheckoutData['time'][0]) &&
            $aCheckoutData['time'][0]['price_comment'] !== null
        ) {
            switch ($aCheckoutData['time'][0]['price_comment']) {
                case 'morning':
                    $deliveryType = self::DELIVERY_TYPE_MORNING;
                    break;
                case 'standard':
                    $deliveryType = self::DELIVERY_TYPE_STANDARD;
                    break;
                case 'night':
                case 'avond':
                    $deliveryType = self::DELIVERY_TYPE_NIGHT;
                    break;
            }
        } elseif (key_exists('price_comment', $aCheckoutData) && $aCheckoutData['price_comment'] !== null) {
            switch ($aCheckoutData['price_comment']) {
                case 'retail':
                    $deliveryType = self::DELIVERY_TYPE_RETAIL;
                    break;
                case 'retailexpress':
                    $deliveryType = self::DELIVERY_TYPE_RETAIL_EXPRESS;
                    break;
            }
        }

        return $deliveryType;
    }

    /**
     * Convert delivery date from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     * @return $this
     * @throws \Exception
     */
    public function setDeliveryDateFromCheckout($checkoutData)
    {
        $aCheckoutData = json_decode($checkoutData, true);

        if (
            !is_array($aCheckoutData) ||
            !key_exists('date', $aCheckoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($aCheckoutData['date']);
        }

        return $this;
    }

    /**
     * Convert pickup data from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     * @return $this
     * @throws \Exception
     */
    public function setPickupAddressFromCheckout($checkoutData)
    {
        if ($this->getCountry() !== MyParcelConsignment::CC_NL && $this->getCountry() !== MyParcelConsignment::CC_BE) {
            return $this;
        }

        $aCheckoutData = json_decode($checkoutData, true);

        if (
            !is_array($aCheckoutData) ||
            !key_exists('location', $aCheckoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($aCheckoutData['date']);
        }

        if ($aCheckoutData['price_comment'] == 'retail') {
            $this->setDeliveryType(4);
        } else if ($aCheckoutData['price_comment'] == 'retailexpress') {
            $this->setDeliveryType(5);
        } else {
            throw new \Exception('No PostNL location found in checkout data: ' . $checkoutData);
        }

        $this
            ->setPickupPostalCode($aCheckoutData['postal_code'])
            ->setPickupStreet($aCheckoutData['street'])
            ->setPickupCity($aCheckoutData['city'])
            ->setPickupNumber($aCheckoutData['number'])
            ->setPickupLocationName($aCheckoutData['location'])
            ->setPickupLocationCode($aCheckoutData['location_code']);

        if (isset($aCheckoutData['retail_network_id'])) {
            $this->setPickupNetworkId($aCheckoutData['retail_network_id']);
        }

        return $this;
    }

    /**
     * Get ReturnShipment Object to send to MyParcel
     *
     * @return array
     */
    public function encodeReturnShipment() {
        $data = [
            'parent' => $this->getMyParcelConsignmentId(),
            'carrier' => 1,
            'email' => $this->getEmail(),
            'name' => $this->getPerson(),
        ];

        return $data;
    }

    /**
     * Check if address is correct
     * Only for Dutch addresses
     *
     * @param $fullStreet
     * @return bool
     */
    public function isCorrectAddress($fullStreet)
    {
        $result = preg_match(self::SPLIT_STREET_REGEX, $fullStreet, $matches);

        if (!$result || !is_array($matches)) {
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
     * Check if the address is outside the EU
     *
     * @return bool
     */
    public function isCdCountry()
    {
        return false == $this->isEuCountry();
    }

    /**
     * Check if the address is inside the EU
     *
     * @return bool
     */
    public function isEuCountry() {
        return in_array(
            $this->getCountry(),
            array (
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
                'GB',
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
     * Splits street data into separate parts for street name, house number and extension.
     * Only for Dutch addresses
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @return array
     *
     * @throws \Exception
     */
    private function splitStreet($fullStreet)
    {
        $street = '';
        $number = '';
        $number_suffix = '';

        $fullStreet = trim( preg_replace('/(\r\n)|\n|\r/', ' ', $fullStreet));
        $result = preg_match(self::SPLIT_STREET_REGEX, $fullStreet, $matches);

        if (!$result || !is_array($matches)) {
            // Invalid full street supplied
            throw new \Exception('Invalid full street supplied: ' . $fullStreet);
        }

        if ($fullStreet != $matches[0]) {
            // Characters are gone by preg_match
            throw new \Exception('Something went wrong with splitting up address ' . $fullStreet);
        }

        if (isset($matches['street'])) {
            $street = $matches['street'];
        }

        if (isset($matches['number'])) {
            $number = $matches['number'];
        }

        if (isset($matches['number_suffix'])) {
            $number_suffix = trim($matches['number_suffix'], '-');
        }

        $streetData = array(
            'street' => $street,
            'number' => $number,
            'number_suffix' => $number_suffix,
        );

        return $streetData;
    }

    /**
     * @return $this
     */
    private function encodeBaseOptions()
    {
        $packageType = $this->getPackageType();

        if ($packageType == null) {
            $packageType = self::DEFAULT_PACKAGE_TYPE;
        }

        $this->consignmentEncoded = [
            'recipient' => [
                'cc' => $this->getCountry(),
                'person' => $this->getPerson(),
                'postal_code' => $this->getPostalCode(),
                'city' => (string) $this->getCity(),
                'email' => (string) $this->getEmail(),
                'phone' => (string) $this->getPhone(),
            ],
            'options' => [
                'package_type' => $packageType,
                'label_description' => $this->getLabelDescription(),
            ],
            'carrier' => 1,
        ];

        if ($this->getReferenceId()) {
            $this->consignmentEncoded['reference_identifier'] = $this->getReferenceId();
        }

        if ($this->getCompany()) {
            $this->consignmentEncoded['recipient']['company'] = $this->getCompany();
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function encodeStreet()
    {
        if ($this->getCountry() == MyParcelConsignment::CC_NL) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'recipient' => [
                        'street' => $this->getStreet(true),
                        'street_additional_info' => $this->getStreetAdditionalInfo(),
                        'number' => $this->getNumber(),
                        'number_suffix' => $this->getNumberSuffix(),
                    ],
                ]
            );
        } else {
            $this->consignmentEncoded['recipient']['street'] = $this->getFullStreet(true);
            $this->consignmentEncoded['recipient']['street_additional_info'] = $this->getStreetAdditionalInfo();
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function encodeExtraOptions() {
        if ( $this->getCountry() == self::CC_NL || $this->getCountry() == self::CC_BE ) {
            $this->consignmentEncoded = array_merge_recursive(
                $this->consignmentEncoded,
                [
                    'options' => [
                        'only_recipient' => $this->isOnlyRecipient() ? 1 : 0,
                        'signature' => $this->isSignature() ? 1 : 0,
                        'return' => $this->isReturn() ? 1 : 0,
                        'delivery_type' => $this->getDeliveryType(),
                    ],
                ]
            );
            $this
                ->encodePickup()
                ->encodeInsurance()
                ->encodePhysicalProperties();
        }

        if ($this->isEuCountry()) {
            $this->consignmentEncoded['options']['large_format'] = $this->isLargeFormat() ? 1 : 0;
        }

        if ($this->getDeliveryDate()) {
            $this->consignmentEncoded['options']['delivery_date'] = $this->getDeliveryDate();
        }

        return $this;
    }

    private function encodePickup()
    {
        // Set pickup address
        if (
            $this->getPickupPostalCode() !== null &&
            $this->getPickupStreet() !== null &&
            $this->getPickupCity() !== null &&
            $this->getPickupNumber() !== null &&
            $this->getPickupLocationName() !== null
        ) {
            $this->consignmentEncoded['pickup'] = [
                'postal_code' => $this->getPickupPostalCode(),
                'street' => $this->getPickupStreet(),
                'city' => $this->getPickupCity(),
                'number' => $this->getPickupNumber(),
                'location_name' => $this->getPickupLocationName(),
                'location_code' => $this->getPickupLocationCode(),
                'retail_network_id' => $this->getPickupNetworkId(),
            ];
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function encodeInsurance()
    {
        // Set insurance
        if ($this->getInsurance() > 1) {
            $this->consignmentEncoded['options']['insurance'] = [
                'amount' => (int) $this->getInsurance() * 100,
                'currency' => 'EUR',
            ];
        }

        return $this;
    }

    private function encodePhysicalProperties()
    {
        if (empty($this->getPhysicalProperties()) && $this->getPackageType() != self::PACKAGE_TYPE_DIGITAL_STAMP) {
            return $this;
        }
        if ($this->getPackageType() == self::PACKAGE_TYPE_DIGITAL_STAMP && !isset($this->getPhysicalProperties()['weight'])) {
            throw new \Exception('Weight in physical properties must be set for digital stamp shipments.');
        }

        $this->consignmentEncoded['physical_properties'] = $this->getPhysicalProperties();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function encodeCdCountry()
    {
        if ($this->isEuCountry()) {
            return $this;
        }

        if (empty($this->getItems())) {
            throw new \Exception('Product data must be set for international MyParcel shipments. Use addItem().');
        }

        if (!$this->getPackageType() === 1) {
            throw new \Exception('For international shipments, package_type must be 1 (normal package).');
        }

        if (empty($this->getLabelDescription())) {
            throw new \Exception('Label description/invoice id is required for international shipments. Use getLabelDescription().');
        }

        $items = [];
        foreach ($this->getItems() as $item) {
            $items[] = $this->encodeCdCountryItem($item);
        }

        $this->consignmentEncoded = array_merge_recursive(
            $this->consignmentEncoded, [
                'customs_declaration' => [
                    'contents' => 1,
                    'weight' => $this->getTotalWeight(),
                    'items' => $items,
                    'invoice' => $this->getLabelDescription(),
                ],
                'physical_properties' => $this->getPhysicalProperties() + ['weight' => $this->getTotalWeight()],
            ]
        );

        return $this;
    }

    /**
     * Encode product for the request
     *
     * @var MyParcelCustomsItem $customsItem
     * @var string $currency
     * @return array
     */
    private function encodeCdCountryItem($customsItem, $currency = 'EUR')
    {
        $item = [
            'description' => $customsItem->getDescription(),
            'amount' => $customsItem->getAmount(),
            'weight' => $customsItem->getWeight(),
            'classification' => $customsItem->getClassification(),
            'country' => $customsItem->getCountry(),
            'item_value' =>
                [
                    'amount' => $customsItem->getItemValue(),
                    'currency' => $currency,
                ],
        ];

        return $item;
    }

    /**
     * @param array $data
     * @return $this
     */
    private function decodeBaseOptions($data)
    {
        $recipient = $data['recipient'];
        $options = $data['options'];

        $this
            ->setMyParcelConsignmentId($data['id'])
            ->setReferenceId($data['reference_identifier'])
            ->setBarcode($data['barcode'])
            ->setStatus($data['status'])
            ->setCountry($recipient['cc'])
            ->setPerson($recipient['person'])
            ->setPostalCode($recipient['postal_code'])
            ->setStreet($recipient['street'])
            ->setCity($recipient['city'])
            ->setEmail($recipient['email'])
            ->setPhone($recipient['phone'])
            ->setPackageType($options['package_type'])
            ->setLabelDescription(isset($options['label_description']) ? $options['label_description'] : '')
        ;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     * @throws \Exception
     */
    private function decodeExtraOptions($data)
    {
        $options = $data['options'];
        $fields = [
            'only_recipient' => false,
            'large_format' => false,
            'signature' => false,
            'return' => false,
            'delivery_date' => null,
            'delivery_type' => self::DEFAULT_DELIVERY_TYPE,
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        $methods = [
            'OnlyRecipient' => 'only_recipient',
            'LargeFormat' => 'large_format',
            'Signature' => 'signature',
            'Return' => 'return',
            'DeliveryDate' => 'delivery_date',
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->setByMethods($options, $methods);

        if (key_exists('insurance', $options)) {
            $insuranceAmount = $options['insurance']['amount'];
            $this->setInsurance($insuranceAmount / 100);
        }

        if (isset($options['delivery_type'])) {
            $this->setDeliveryType($options['delivery_type'], false);
        }

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     * @throws \Exception
     */
    private function decodeRecipient($data)
    {
        $recipient = $data['recipient'];
        $fields = [
            'company' => '',
            'number' => null,
            'number_suffix' => '',

        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        $methods = [
            'Company' => 'company',
            'Number' => 'number',
            'NumberSuffix' => 'number_suffix',
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->setByMethods($recipient, $methods);

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    private function decodePickup($data)
    {
        // Set pickup
        if (key_exists('pickup', $data) && $data['pickup'] !== null) {
            $methods = [
                'PickupPostalCode' => 'pickup_postal_code',
                'PickupStreet' => 'pickup_street',
                'PickupCity' => 'pickup_city',
                'PickupNumber' => 'pickup_number',
                'PickupLocationName' => 'pickup_location_name',
                'PickupLocationCode' => 'pickup_location_code',
                'PickupNetworkId' => 'pickup_network_id',
            ];
            /** @noinspection PhpInternalEntityUsedInspection */
            $this->setByMethods($data['pickup'], $methods);
        } else {

            $fields = [
                'pickup_postal_code' => null,
                'pickup_street' => null,
                'pickup_city' => null,
                'pickup_number' => null,
                'pickup_location_name' => null,
                'pickup_location_code' => '',
                'pickup_network_id' => '',

            ];
            /** @noinspection PhpInternalEntityUsedInspection */
            $this->clearFields($fields);
        }

        return $this;
    }
}
