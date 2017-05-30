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
     * For the full description go to:
     * @link https://gist.github.com/reindert-vetter/a90fdffe7d452f92d1c65bbf759f6e38
     */
    const SPLIT_STREET_REGEX = '~(?P<street>.*?)\s?(?P<street_suffix>(?P<number>[\d]+)[\s|-]?(?P<number_suffix>[a-zA-Z/\s]{0,5}$|[0-9/]{0,5}$|\s[a-zA-Z]{1}[0-9]{0,3}$|\s[0-9]{2}[a-zA-Z]{0,3}$))$~';

    /**
     * Get entire street
     *
     * @return string Entire street
     */
    public function getFullStreet()
    {
        $fullStreet = $this->getStreet();

        if ($this->getNumber())
            $fullStreet .= ' ' . $this->getNumber();

        if ($this->getNumberSuffix())
            $fullStreet .= ' ' . $this->getNumberSuffix();

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
        if ($this->getCountry() === null)
            throw new \Exception('First set the country code with setCountry() before running setFullStreet()');

        if ($this->getCountry() == 'NL') {
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
     * @todo get weight of all items
     *
     * @return int
     */
    public function getTotalWeight()
    {
        return 1;
    }

    /**
     * Encode all the data before sending it to MyParcel
     *
     * @return array
     */
    public function apiEncode()
    {
        $aConsignment = [
            'recipient' => [
                'cc' => $this->getCountry(),
                'person' => $this->getPerson(),
                'postal_code' => $this->getPostalCode(),
                'city' => $this->getCity(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
            ],
            'options' => [
                'package_type' => $this->getPackageType(),
                'label_description' => $this->getLabelDescription(),
            ],
            'carrier' => 1,
        ];

        if ($this->getCompany())
            $aConsignment['recipient']['company'] = $this->getCompany();

        if ($this->getCountry() == 'NL') {
            $aConsignment = array_merge_recursive(
                $aConsignment,
                [
                    'recipient' => [
                        'street' => $this->getStreet(),
                        'number' => $this->getNumber(),
                        'number_suffix' => $this->getNumberSuffix(),
                    ],
                    'options' => [
                        'large_format' => $this->isLargeFormat() ? 1 : 0,
                        'only_recipient' => $this->isOnlyRecipient() ? 1 : 0,
                        'signature' => $this->isSignature() ? 1 : 0,
                        'return' => $this->isReturn() ? 1 : 0,
                        'delivery_type' => $this->getDeliveryType(),
                    ],
                ]
            );

            // Set insurance
            if ($this->getInsurance() > 1) {
                $aConsignment['options']['insurance'] = [
                    'amount' => (int)$this->getInsurance() * 100,
                    'currency' => 'EUR',
                ];
            }
            
            // Set pickup address
            if (
                $this->getPickupPostalCode() !== null &&
                $this->getPickupStreet() !== null &&
                $this->getPickupCity() !== null &&
                $this->getPickupNumber() !== null &&
                $this->getPickupLocationName() !== null
            ) {
                $aConsignment['pickup'] = [
                      'postal_code' => $this->getPickupPostalCode(),
                      'street' => $this->getPickupStreet(),
                      'city' => $this->getPickupCity(),
                      'number' => $this->getPickupNumber(),
                      'location_name' => $this->getPickupLocationName(),
                ];
            }

        } else {
            $aConsignment['recipient']['street'] = $this->getFullStreet();
        }

        if ($this->isCdCountry()) {
            $aConsignment = array_merge_recursive(
                $aConsignment, [
                    'customs_declaration' => [
                        'contents' => 1,
                        'weight' => $this->getTotalWeight(),
                        'items' => [
                            [
                                'description' => 'Product',
                                'amount' => 1,
                                'weight' => 0,
                                'classification' => '0000',
                                'country' => 'NL',
                                'item_value' =>
                                    [
                                        'amount' => 100,
                                        'currency' => "EUR",
                                    ],
                            ]
                        ],
                        'invoice' => $this->getLabelDescription(),
                    ],
                    'physical_properties' => [
                        'weight' => $this->getTotalWeight()
                    ]
                ]
            );
        }

        if ($this->getDeliveryDate())
            $aConsignment['options']['delivery_date'] = $this->getDeliveryDate();


        return $aConsignment;
    }

    /**
     * Decode all the data after the request with the API
     *
     * @param $data
     *
     * @return $this
     */
    public function apiDecode($data)
    {
        $recipient = $data['recipient'];
        $options = $data['options'];

        $this
            ->setMyParcelConsignmentId($data['id'])
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
            ->setLabelDescription($options['label_description'])
        ;

        if (key_exists('company', $recipient))
            $this->setCompany($recipient['company']);

        if (key_exists('only_recipient', $recipient))
            $this->setOnlyRecipient($recipient['only_recipient']);

        if (key_exists('signature', $recipient))
            $this->setSignature($recipient['signature']);

        if (key_exists('return', $recipient))
            $this->setReturn($recipient['return']);

        if (key_exists('number', $recipient))
            $this->setNumber($recipient['number']);

        if (key_exists('number_suffix', $recipient))
            $this->setNumberSuffix($recipient['number_suffix']);

        // Set options
        if (key_exists('insurance', $data['options']))
            $this->setInsurance($data['options']['insurance']['amount'] / 100);

        if (key_exists('delivery_date', $data['options']))
            $this->setDeliveryDate($data['options']['delivery_date']);

        if (key_exists('delivery_type', $data['options']))
            $this->setDeliveryType($data['options']['delivery_type']);

        // Set pickup
        if (key_exists('pickup', $data)  && $data['pickup'] !== null) {
            if (key_exists('pickup_postal_code', $data['pickup']))
                $this->setPostalCode($data['pickup']['pickup_postal_code']);

            if (key_exists('pickup_street', $data['pickup']))
                $this->getPickupStreet($data['pickup']['pickup_street']);

            if (key_exists('pickup_city', $data['pickup']))
                $this->setPickupCity($data['pickup']['pickup_city']);

            if (key_exists('pickup_number', $data['pickup']))
                $this->setPickupNumber($data['pickup']['pickup_number']);

            if (key_exists('pickup_location_name', $data['pickup']))
                $this->getPickupLocationName($data['pickup']['pickup_location_name']);
        } else {
            $this
                ->setPickupPostalCode(null)
                ->setPickupStreet(null)
                ->setPickupCity(null)
                ->setPickupNumber(null)
                ->setPickupLocationName(null);
        }

        return $this;
    }

    public function setPickupAddressFromCheckout($checkoutData)
    {
        if ($this->getCountry() !== 'NL' && $this->getDeliveryType() !== 4 && $this->getDeliveryType() !== 5) {
            return $this;
        }

        $aCheckoutData = json_decode($checkoutData, true);

        if (
            !is_array($aCheckoutData) ||
            !key_exists('location', $aCheckoutData)
        ) {
            throw new \Exception('No PostNL location found in checkout data: ' . $checkoutData);
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($aCheckoutData['date']);
        }

        $this
            ->setPickupPostalCode($aCheckoutData['postal_code'])
            ->setPickupStreet($aCheckoutData['street'])
            ->setPickupCity($aCheckoutData['city'])
            ->setPickupNumber($aCheckoutData['number'])
            ->setPickupLocationName($aCheckoutData['location']);

        return $this;
    }

    /**
     * Splits street data into separate parts for street name, house number and extension.
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

        $result = preg_match(self::SPLIT_STREET_REGEX, $fullStreet, $matches);

        if (!$result || !is_array($matches) || $fullStreet != $matches[0]) {
            if ($fullStreet != $matches[0]) {
                // Characters are gone by preg_match
                throw new \Exception('Something went wrong with splitting up address ' . $fullStreet);
            } else {
                // Invalid full street supplied
                throw new \Exception('Invalid full street supplied: ' . $fullStreet);
            }
        }

        if (isset($matches['street'])) {
            $street = $matches['street'];
        }

        if (isset($matches['number'])) {
            $number = $matches['number'];
        }

        if (isset($matches['number_suffix'])) {
            $number_suffix = trim($matches['number_suffix']);
        }

        $streetData = array(
            'street' => $street,
            'number' => $number,
            'number_suffix' => $number_suffix,
        );

        return $streetData;
    }

    /**
     * Check if the address is outside the EU
     *
     * @return bool
     */
    private function isCdCountry()
    {
        return !in_array(
            $this->getCountry(),
            self::EU_COUNTRIES
        );
    }
}