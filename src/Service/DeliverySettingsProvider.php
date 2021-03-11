<?php

namespace Gett\MyparcelBE\Service;

use Context;
use Country;
use DateTime;
use Gett\MyparcelBE\Constant;
use Module;
use Tools;
use Validate;

class DeliverySettingsProvider
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @var int
     */
    private $idCarrier;

    /**
     * @var int
     */
    private $idOrder;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Module $module, int $idCarrier = null, Context $context = null)
    {
        $this->module = $module;
        $this->idCarrier = (int) $idCarrier;
        $this->context = $context ?? Context::getContext();
    }

    public function setOrderId(int $idOrder): self
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function get(): array
    {
        $this->initCart();
        if (!Validate::isLoadedObject($this->context->cart)) {
            return [];
        }
        $address = new \Address($this->context->cart->id_address_delivery);
        $houseNumber = preg_replace('/[^0-9]/', '', $address->address1);
        if (\Configuration::get(Constant::USE_ADDRESS2_AS_STREET_NUMBER_CONFIGURATION_NAME)) {
            $houseNumber = trim($address->address2);
        }
        $carrier = new \Carrier($this->idCarrier);
        $carrierName = str_replace(' ', '', strtolower($carrier->name));
        $carrierSettings = [
            'bpost' => ['allowDeliveryOptions' => false],
            'dpd' => ['allowDeliveryOptions' => false],
            'postnl' => ['allowDeliveryOptions' => false],
        ];
        $activeCarrierSettings = [
            'allowDeliveryOptions' => true,
            'allowEveningDelivery' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowEveningDelivery'),
            'allowMondayDelivery' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowMondayDelivery'),
            'allowMorningDelivery' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowMorningDelivery'),
            'allowSaturdayDelivery' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowSaturdayDelivery'),
            'allowOnlyRecipient' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowOnlyRecipient'),
            'allowSignature' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowSignature'),
            'allowPickupPoints' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowPickupPoints'),
            'deliveryDaysWindow' => (int) (CarrierConfigurationProvider::get($this->idCarrier, 'deliveryDaysWindow') ?? 1),
            // TODO: remove allowPickupLocations after fixing the allowPickupPoints reference
            'allowPickupLocations' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowPickupPoints'),
        ];
        // Having the options as false seems to matter, at least for allowSignature | BE | Bpost
//        foreach ($activeCarrierSettings as $key => $value) {
//            if ($key != 'allowDeliveryOptions' && ($value === false || $value === 0)) {
//                unset($activeCarrierSettings[$key]);
//            }
//        }
        $carrierSettings[$carrierName] = array_merge($carrierSettings[$carrierName], $activeCarrierSettings);
        $dropOffDelay = (int) CarrierConfigurationProvider::get($this->idCarrier, 'dropOffDelay');
        $deliveryDaysWindow = (int) (CarrierConfigurationProvider::get($this->idCarrier, 'deliveryDaysWindow') ?? 1);
        $cutoffExceptions = CarrierConfigurationProvider::get($this->idCarrier, Constant::CUTOFF_EXCEPTIONS);
        $cutoffExceptions = @json_decode(
            $cutoffExceptions,
            true
        );
        if (!is_array($cutoffExceptions)) {
            $cutoffExceptions = [];
        }
        $now = new DateTime('now');
        $dropOffDateObj = new DateTime('today');
        $deliveryDateObj = new DateTime('tomorrow'); // Delivery is next day
        $weekDayNumber = $dropOffDateObj->format('N');
        $dayName = Constant::WEEK_DAYS[$weekDayNumber];
        $cutoffTimeToday = CarrierConfigurationProvider::get($this->idCarrier, $dayName . 'CutoffTime');
        $this->updateDatesByDropOffDelay($dropOffDelay, $dropOffDateObj, $deliveryDateObj);

        // Update the dropOffDateObj with the cutoff time. Ex. 17:00 hour
        $this->updateCutoffTime($cutoffTimeToday, $dropOffDateObj, $cutoffExceptions);
        if ($now > $dropOffDateObj) {
            ++$dropOffDelay;
        }

        $exceptionCutoffToday = null;

        foreach (range(1, ($deliveryDaysWindow > 1 ? $deliveryDaysWindow : 1)) as $day) {
            if (!isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['cutoff'])
                && isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['nodispatch'])) {
                $deliveryDateObj->modify('+1 day');
                ++$dropOffDelay;
            } else {
                // first available day found
                break;
            }
        }
        // Trigger the $module->getOrderShippingCost() method then use the calculated carrier shipping cost
        $this->context->cart->getCarrierCost(
            $this->idCarrier,
            true,
            new Country($address->id_country)
        );
        $priceStandardDelivery = Tools::ps_round($this->module->cartCarrierStandardShippingCost, 2);
        if (!empty($this->module->carrierStandardShippingCost[(int) $this->idCarrier])) {
            $priceStandardDelivery = $this->module->carrierStandardShippingCost[$this->idCarrier];
        }

        return [
            'config' => [
                'platform' => ($this->module->isBE() ? 'belgie' : 'myparcel'),
                'carrierSettings' => $carrierSettings,

                'priceMorningDelivery' => Tools::ps_round(CarrierConfigurationProvider::get($this->idCarrier, 'priceMorningDelivery'), 2),
                'priceStandardDelivery' => Tools::ps_round($priceStandardDelivery, 2),
                'priceEveningDelivery' => Tools::ps_round(CarrierConfigurationProvider::get($this->idCarrier, 'priceEveningDelivery'), 2),
                'priceSignature' => Tools::ps_round(CarrierConfigurationProvider::get($this->idCarrier, 'priceSignature'), 2),
                'priceOnlyRecipient' => Tools::ps_round(CarrierConfigurationProvider::get($this->idCarrier, 'priceOnlyRecipient'), 2),
                'pricePickup' => Tools::ps_round(CarrierConfigurationProvider::get($this->idCarrier, 'pricePickup'), 2),

                //'allowMondayDelivery' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowMondayDelivery'),
                //'allowMorningDelivery' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowMorningDelivery'),
                //'allowEveningDelivery' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowEveningDelivery'),
                //'allowSaturdayDelivery' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowSaturdayDelivery'),
                //'allowPickupPoints' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowPickupPoints'),
                // TODO: remove allowPickupLocations after fixing the allowPickupPoints reference
                //'allowPickupLocations' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowPickupPoints'),
                'allowSignature' => (bool) CarrierConfigurationProvider::get($this->idCarrier, 'allowSignature'),

                'dropOffDays' => array_map(
                    'intval',
                    explode(',', CarrierConfigurationProvider::get($this->idCarrier, 'dropOffDays'))
                ),
                'cutoffTime' => $cutoffTimeToday,
                'deliveryDaysWindow' => $deliveryDaysWindow,
                'dropOffDelay' => $dropOffDelay,

                //'allowOnlyRecipient' => (int) CarrierConfigurationProvider::get($this->idCarrier, 'allowOnlyRecipient'),
            ],
            'strings' => [
                'wrongPostalCodeCity' => CarrierConfigurationProvider::get($this->idCarrier, 'wrongPostalCodeCity'),
                'saturdayDeliveryTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'saturdayDeliveryTitle'),

                'city' => CarrierConfigurationProvider::get($this->idCarrier, 'city'),
                'postcode' => CarrierConfigurationProvider::get($this->idCarrier, 'postcode'),
                'houseNumber' => CarrierConfigurationProvider::get($this->idCarrier, 'houseNumber'),
                'addressNotFound' => CarrierConfigurationProvider::get($this->idCarrier, 'addressNotFound'),

                'deliveryEveningTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'deliveryEveningTitle'),
                'deliveryMorningTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'deliveryMorningTitle'),
                'deliveryStandardTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'deliveryStandardTitle'),

                'deliveryTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'deliveryTitle'),
                'pickupTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'pickupTitle'),

                'onlyRecipientTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'onlyRecipientTitle'),
                'signatureTitle' => CarrierConfigurationProvider::get($this->idCarrier, 'signatureTitle'),

                'pickUpFrom' => CarrierConfigurationProvider::get($this->idCarrier, 'pickUpFrom'),
                'openingHours' => CarrierConfigurationProvider::get($this->idCarrier, 'openingHours'),

                'closed' => CarrierConfigurationProvider::get($this->idCarrier, 'closed'),
                'discount' => CarrierConfigurationProvider::get($this->idCarrier, 'discount'),
                'free' => CarrierConfigurationProvider::get($this->idCarrier, 'free'),
                'from' => CarrierConfigurationProvider::get($this->idCarrier, 'from'),
                'loadMore' => CarrierConfigurationProvider::get($this->idCarrier, 'loadMore'),
                'retry' => CarrierConfigurationProvider::get($this->idCarrier, 'retry'),
            ],
            'address' => [
                'cc' => strtoupper(Country::getIsoById($address->id_country)),
                'city' => $address->city,
                'postalCode' => $address->postcode,
                'number' => $houseNumber,
            ],
            'delivery_settings' => $this->module->getDeliverySettingsByCart((int) $this->context->cart->id),
        ];
    }

    private function updateDatesByDropOffDelay($dropOffDelay, $dropOffDateObj, $deliveryDateObj): void
    {
        if ($dropOffDelay > 0) {
            $dropOffDateObj->modify('+' . $dropOffDelay . ' day');
            $deliveryDateObj->modify('+' . $dropOffDelay . ' day');
//            $weekDayNumber = $dropOffDateObj->format('N');
//            $dayName = Constant::WEEK_DAYS[$weekDayNumber];
//            $cutoffTimeToday = CarrierConfigurationProvider::get($id_carrier, $dayName . 'CutoffTime');
        }
    }

    private function updateCutoffTime(&$cutoffTimeToday, $dropOffDateObj, $cutoffExceptions): void
    {
        if (isset($cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff']) && $cutoffTimeToday !== false) {
            $cutoffTimeToday = $cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff'];
        }
        if (empty($cutoffTimeToday)) {
            $cutoffTimeToday = Constant::DEFAULT_CUTOFF_TIME;
        }

        list($hour, $minute) = explode(':', $cutoffTimeToday);
        $dropOffDateObj->setTime((int) $hour, (int) $minute, 0, 0);
    }

    private function initCart(): void
    {
        if ((!isset($this->context->cart) || !$this->context->cart->id) && $this->idOrder) {
            $order = new \Order($this->idOrder);
            $cart = new \Cart($order->id_cart);
            $this->context->cart = $cart;
        }
    }
}
