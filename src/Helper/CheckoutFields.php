<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class CheckoutFields
{
    /**
     * Delivery types from delivery_options endpoint
     */
    const MORNING        = 'morning';
    const STANDARD       = 'standard';
    const EVENING        = 'night';
    const EVENING_NL     = 'avond';
    const PICKUP         = 'retail';
    const PICKUP_EXPRESS = 'retailexpress';

    /**
     * @param $checkoutData
     *
     * @return int
     */
    public function getDeliveryType($checkoutData)
    {
        if (empty($checkoutData)) {
            return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
        }

        $aCheckoutData    = json_decode($checkoutData, true);
        $typeFromCheckout = $this->getTypeFromCheckout($aCheckoutData);

        switch ($typeFromCheckout) {
            case self::MORNING:
                return MyParcelConsignment::DELIVERY_TYPE_MORNING;
            case self::STANDARD:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
            case self::EVENING:
            case self::EVENING_NL:
                return MyParcelConsignment::DELIVERY_TYPE_EVENING;
            case self::PICKUP:
                return MyParcelConsignment::DELIVERY_TYPE_PICKUP;
            case self::PICKUP_EXPRESS:
                return MyParcelConsignment::DELIVERY_TYPE_PICKUP_EXPRESS;
            default:
                return MyParcelConsignment::DELIVERY_TYPE_STANDARD;
        }
    }

    /**
     * @param $aCheckoutData
     *
     * @return array
     */
    private function getTypeFromCheckout($aCheckoutData)
    {
        $typeFromCheckout = null;

        if (! empty($aCheckoutData['time'][0]['price_comment'])) {
            $typeFromCheckout = $aCheckoutData['time'][0]['price_comment'];
        } elseif (! empty($aCheckoutData['price_comment'])) {
            $typeFromCheckout = $aCheckoutData['price_comment'];
        }

        return $typeFromCheckout;
    }
}