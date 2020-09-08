<?php declare(strict_types=1);
/**
 * @author Reindert Vetter <reindert@myparcel.nl>
 */

namespace MyParcelNL\Sdk\src\Concerns;

use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Helper\CheckoutFields;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

/**
 * @deprecated
 */
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
     * @deprecated
     *
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
     * @throws \Exception
     * @deprecated
     *
     */
    public function setDeliveryDateFromCheckout(?string $checkoutData)
    {
        if (! $checkoutData) {
            return $this;
        }

        $aCheckoutData = json_decode($checkoutData, true);

        if (
            ! is_array($aCheckoutData) ||
            ! key_exists('date', $aCheckoutData)
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
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @deprecated
     *
     */
    public function setPickupAddressFromCheckout(?string $checkoutData)
    {
        if ($this->getCountry() !== AbstractConsignment::CC_NL && $this->getCountry() !== AbstractConsignment::CC_BE) {
            return $this;
        }

        if (! $checkoutData) {
            return $this;
        }

        $aCheckoutData = json_decode($checkoutData, true);

        if (! is_array($aCheckoutData) ||
            ! key_exists('location', $aCheckoutData)
        ) {
            return $this;
        }

        if ($this->getDeliveryDate() == null) {
            $this->setDeliveryDate($aCheckoutData['date']);
        }

        if ($aCheckoutData['price_comment'] == 'retail') {
            $this->setDeliveryType(AbstractConsignment::DELIVERY_TYPE_PICKUP);

        } else if ($aCheckoutData['price_comment'] == 'retailexpress') {
            $this->setDeliveryType(AbstractConsignment::DELIVERY_TYPE_PICKUP_EXPRESS);
        } else {
            throw new MissingFieldException('No PostNL location found in checkout data: ' . $checkoutData);
        }
        $this
            ->setPickupPostalCode($aCheckoutData['postal_code'])
            ->setPickupStreet($aCheckoutData['street'])
            ->setPickupCity($aCheckoutData['city'])
            ->setPickupNumber($aCheckoutData['number'])
            ->setPickupCountry($aCheckoutData['cc'])
            ->setPickupLocationName($aCheckoutData['location'])
            ->setPickupLocationCode($aCheckoutData['location_code']);

        if (isset($aCheckoutData['retail_network_id'])) {
            $this->setRetailNetworkId($aCheckoutData['retail_network_id']);
        }

        return $this;
    }
}
