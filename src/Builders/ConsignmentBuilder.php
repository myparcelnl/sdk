<?php
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\src\Builders;

use MyParcelNL\Sdk\src\Helper\SplitStreet;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class ConsignmentBuilder extends MyParcelConsignment
{

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
     * @todo move to hasCheckout
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
     * @todo move to hasCheckout
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
}