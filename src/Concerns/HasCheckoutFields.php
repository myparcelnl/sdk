<?php declare(strict_types=1);
/**
 * @author Reindert Vetter <reindert@myparcel.nl>
 */

namespace MyParcelNL\Sdk\src\Concerns;

use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Helper\CheckoutFields;
use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

trait HasCheckoutFields
{
    /**
     * Get delivery type from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     *
     * @return int
     * @throws \Exception
     */
    public function getDeliveryTypeFromCheckout($checkoutData)
    {
        $helper = new CheckoutFields();

        return $helper->getDeliveryType($checkoutData);
    }

    /**
     * Convert delivery date from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     *
     * @return $this
     */
    public function setDeliveryDateFromCheckout($checkoutData = null)
    {
        if (! $checkoutData) {
            return $this;
        }

        $checkoutData = json_decode($checkoutData, true);
      
        if (
            ! is_array($checkoutData) ||
            ! key_exists('date', $checkoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($checkoutData['date']);
        }

        return $this;
    }

    /**
     * Convert pickup data from checkout
     *
     * You can use this if you use the following code in your checkout: https://github.com/myparcelnl/checkout
     *
     * @param string $checkoutData
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setPickupAddressFromCheckout($checkoutData = null)
    {
        if ($this->getCountry() !== MyParcelConsignment::CC_NL && $this->getCountry() !== MyParcelConsignment::CC_BE) {
            return $this;
        }

        if (! $checkoutData) {
            return $this;
        }

        $checkoutData = json_decode($checkoutData, true);

        if (! is_array($checkoutData) ||
            ! key_exists('location', $checkoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($checkoutData['date']);
        }

        if ($checkoutData['price_comment'] == 'retail') {
            $this->setDeliveryType(MyParcelConsignment::DELIVERY_TYPE_PICKUP);
        } else if ($checkoutData['price_comment'] == 'retailexpress') {
            $this->setDeliveryType(MyParcelConsignment::DELIVERY_TYPE_PICKUP_EXPRESS);
        } else {
            throw new MissingFieldException('No PostNL location found in checkout data: ' . $checkoutData);
        }

        $this
            ->setPickupPostalCode($checkoutData['postal_code'])
            ->setPickupStreet($checkoutData['street'])
            ->setPickupCity($checkoutData['city'])
            ->setPickupNumber($checkoutData['number'])
            ->setPickupLocationName($checkoutData['location'])
            ->setPickupLocationCode($checkoutData['location_code']);

        if (isset($checkoutData['retail_network_id'])) {
            $this->setPickupNetworkId($checkoutData['retail_network_id']);
        }

        return $this;
    }
}