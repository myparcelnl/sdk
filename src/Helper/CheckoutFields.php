<?php
declare(strict_types=1);


namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class CheckoutFields
{
    /**
     * Delivery type
     */
    const MORNING       = 'morning';
    const STANDARD      = 'standard';
    const NIGHT         = 'night';
    const AVOND         = 'avond';
    const RETAIL        = 'retail';
    const RETAILEXPRESS = 'retailexpress';

    /**
     * @param $checkoutData
     * @return int
     */
    public function getDeliveryType($checkoutData)
    {
        $aCheckoutData    = json_decode($checkoutData, true);
        $typeFromCheckout = $this->getTypeFromCheckout($aCheckoutData);

        switch ($typeFromCheckout) {
            case self::MORNING:
                return MyParcelConsignment::DELIVERY_TYPE_MORNING;
            case self::STANDARD:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
            case self::NIGHT:
            case self::AVOND:
                return MyParcelConsignment::DELIVERY_TYPE_NIGHT;
            case self::RETAIL:
                return MyParcelConsignment::DELIVERY_TYPE_RETAIL;
            case self::RETAILEXPRESS:
                return MyParcelConsignment::DELIVERY_TYPE_RETAIL_EXPRESS;
            default:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
        }
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