<?php
/**
 * @author Reindert Vetter <reindert@myparcel.nl>
 */

namespace MyParcelNL\Sdk\src\Concerns;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

trait HasCheckoutFields
{
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
        $aCheckoutData = json_decode($checkoutData, true);
        $typeFromCheckout = $this->getTypeFromCheckout($aCheckoutData);

        switch ($typeFromCheckout) {
            case MyParcelRequest::MORNING:
                return MyParcelConsignment::DELIVERY_TYPE_MORNING;
            case MyParcelRequest::STANDARD:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
            case MyParcelRequest::NIGHT:
            case MyParcelRequest::AVOND:
                return MyParcelConsignment::DELIVERY_TYPE_NIGHT;
            case MyParcelRequest::RETAIL:
                return MyParcelConsignment::DELIVERY_TYPE_RETAIL;
            case MyParcelRequest::RETAILEXPRESS:
                return MyParcelConsignment::DELIVERY_TYPE_RETAIL_EXPRESS;
            default:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
        }
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

        if (!is_array($aCheckoutData) ||
            !key_exists('location', $aCheckoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($aCheckoutData['date']);
        }

        if ($aCheckoutData['price_comment'] == 'retail') {
            $this->setDeliveryType(MyParcelConsignment::DELIVERY_TYPE_RETAIL);
        } else if ($aCheckoutData['price_comment'] == 'retailexpress') {
            $this->setDeliveryType(MyParcelConsignment::DELIVERY_TYPE_RETAIL_EXPRESS);
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
     * @param $aCheckoutData
     * @return array
     */
    private function getTypeFromCheckout($aCheckoutData)
    {
        $typeFromCheckout = null;

        if (!empty($aCheckoutData['time'][0]['price_comment'])) {
            $typeFromCheckout = $aCheckoutData['time'][0]['price_comment'];
        } elseif (!empty($aCheckoutData['price_comment'])) {
            $typeFromCheckout = $aCheckoutData['price_comment'];
        }

        return $typeFromCheckout;
    }

}