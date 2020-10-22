<?php

use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Service\CarrierConfigurationProvider;

class MyParcelBECheckoutModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $id_carrier = intval(Tools::getValue('id_carrier'));

        $address = new \Address($this->context->cart->id_address_delivery);
        $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);
        $carrier = new \Carrier($id_carrier);
        $carrierName = str_replace(' ', '', strtolower($carrier->name));
        $carrierSettings = [
            'bpost' => ['allowDeliveryOptions' => false],
            'dpd' => ['allowDeliveryOptions' => false],
            'postnl' => ['allowDeliveryOptions' => false],
        ];
        $activeCarrierSettings = [
            'allowDeliveryOptions' => true,
            'allowEveningDelivery' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowEveningDelivery'),
            'allowMondayDelivery' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowMondayDelivery'),
            'allowMorningDelivery' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowMorningDelivery'),
            'allowSaturdayDelivery' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowSaturdayDelivery'),
            'allowOnlyRecipient' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowOnlyRecipient'),
            'allowSignature' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowSignature'),
            'allowPickupPoints' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowPickupPoints'),
            'deliveryDaysWindow' => (int) (CarrierConfigurationProvider::get($id_carrier, 'deliveryDaysWindow') ?? 1),
            // TODO: remove allowPickupLocations after fixing the allowPickupPoints reference
            'allowPickupLocations' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowPickupPoints'),
        ];
// Having the options as false seems to matter, at least for allowSignature | BE | Bpost
//        foreach ($activeCarrierSettings as $key => $value) {
//            if ($key != 'allowDeliveryOptions' && ($value === false || $value === 0)) {
//                unset($activeCarrierSettings[$key]);
//            }
//        }
        $carrierSettings[$carrierName] = array_merge($carrierSettings[$carrierName], $activeCarrierSettings);
        $dropOffDelay = (int) CarrierConfigurationProvider::get($id_carrier, 'dropOffDelay');
        $cutoffExceptions = CarrierConfigurationProvider::get($id_carrier, Constant::CUTOFF_EXCEPTIONS);
        $cutoffExceptions = @json_decode(
            $cutoffExceptions,
            true
        );
        if (!is_array($cutoffExceptions)) {
            $cutoffExceptions = array();
        }
        $dropOffDateObj = new DateTime('today');
        $deliveryDateObj = new DateTime('tomorrow');// Delivery is next day
        $weekDayNumber = $dropOffDateObj->format('N');
        $dayName = Constant::WEEK_DAYS[$weekDayNumber];
        $cutoffTimeToday = CarrierConfigurationProvider::get($id_carrier, $dayName . 'CutoffTime');
        if ($dropOffDelay > 0) {
            $dropOffDateObj->modify('+' . $dropOffDelay . ' day');
            $deliveryDateObj->modify('+' . $dropOffDelay . ' day');
//            $weekDayNumber = $dropOffDateObj->format('N');
//            $dayName = Constant::WEEK_DAYS[$weekDayNumber];
//            $cutoffTimeToday = CarrierConfigurationProvider::get($id_carrier, $dayName . 'CutoffTime');
        }
        $exceptionCutoffToday = null;
        if (isset($cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff']) && $cutoffTimeToday !== false) {
            $cutoffTimeToday = $cutoffExceptions[$dropOffDateObj->format('d-m-Y')]['cutoff'];
        }
        foreach (range(1, 14) as $day) {
            if (!isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['cutoff'])
                && isset($cutoffExceptions[$deliveryDateObj->format('d-m-Y')]['nodispatch'])) {
                $deliveryDateObj->modify('+1 day');
                $dropOffDelay++;
            } else {
                // first available day found
                break;
            }
        }
        $priceStandardDelivery = $this->context->cart->getCarrierCost(
            $id_carrier,
            true,
            new Country($address->id_country)
        );
        if (empty($cutoffTimeToday)) {
            $cutoffTimeToday = Constant::DEFAULT_CUTOFF_TIME;
        }
        $params = [
            'config' => [
                'platform' => ($this->module->isBE() ? 'belgie' : 'myparcel'),
                'carrierSettings' => $carrierSettings,

                'priceMorningDelivery' => Tools::ps_round(CarrierConfigurationProvider::get($id_carrier, 'priceMorningDelivery'), 2),
                'priceStandardDelivery' => Tools::ps_round($priceStandardDelivery, 2),
                'priceEveningDelivery' => Tools::ps_round(CarrierConfigurationProvider::get($id_carrier, 'priceEveningDelivery'), 2),
                'priceSignature' => Tools::ps_round(CarrierConfigurationProvider::get($id_carrier, 'priceSignature'), 2),
                'priceOnlyRecipient' => Tools::ps_round(CarrierConfigurationProvider::get($id_carrier, 'priceOnlyRecipient'), 2),
                'pricePickup' => Tools::ps_round(CarrierConfigurationProvider::get($id_carrier, 'pricePickup'), 2),

                //'allowMondayDelivery' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowMondayDelivery'),
                //'allowMorningDelivery' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowMorningDelivery'),
                //'allowEveningDelivery' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowEveningDelivery'),
                //'allowSaturdayDelivery' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowSaturdayDelivery'),
                //'allowPickupPoints' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowPickupPoints'),
                // TODO: remove allowPickupLocations after fixing the allowPickupPoints reference
                //'allowPickupLocations' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowPickupPoints'),
                'allowSignature' => (bool) CarrierConfigurationProvider::get($id_carrier, 'allowSignature'),

                'dropOffDays' => array_map(
                    'intval',
                    explode(',', CarrierConfigurationProvider::get($id_carrier, 'dropOffDays'))
                ),
                'cutoffTime' => $cutoffTimeToday,
                'deliveryDaysWindow' => (int) (CarrierConfigurationProvider::get($id_carrier, 'deliveryDaysWindow') ?? 1),
                'dropOffDelay' => $dropOffDelay,

                //'allowOnlyRecipient' => (int) CarrierConfigurationProvider::get($id_carrier, 'allowOnlyRecipient'),
            ],
            'strings' => [
                'wrongPostalCodeCity' => CarrierConfigurationProvider::get($id_carrier, 'wrongPostalCodeCity'),
                'saturdayDeliveryTitle' => CarrierConfigurationProvider::get($id_carrier, 'saturdayDeliveryTitle'),

                'city' => CarrierConfigurationProvider::get($id_carrier, 'city'),
                'postcode' => CarrierConfigurationProvider::get($id_carrier, 'postcode'),
                'houseNumber' => CarrierConfigurationProvider::get($id_carrier, 'houseNumber'),
                'addressNotFound' => CarrierConfigurationProvider::get($id_carrier, 'addressNotFound'),

                'deliveryEveningTitle' => CarrierConfigurationProvider::get($id_carrier, 'deliveryEveningTitle'),
                'deliveryMorningTitle' => CarrierConfigurationProvider::get($id_carrier, 'deliveryMorningTitle'),
                'deliveryStandardTitle' => CarrierConfigurationProvider::get($id_carrier, 'deliveryStandardTitle'),

                'deliveryTitle' => CarrierConfigurationProvider::get($id_carrier, 'deliveryTitle'),
                'pickupTitle' => CarrierConfigurationProvider::get($id_carrier, 'pickupTitle'),

                'onlyRecipientTitle' => CarrierConfigurationProvider::get($id_carrier, 'onlyRecipientTitle'),
                'signatureTitle' => CarrierConfigurationProvider::get($id_carrier, 'signatureTitle'),

                'pickUpFrom' => CarrierConfigurationProvider::get($id_carrier, 'pickUpFrom'),
                'openingHours' => CarrierConfigurationProvider::get($id_carrier, 'openingHours'),

                'closed' => CarrierConfigurationProvider::get($id_carrier, 'closed'),
                'discount' => CarrierConfigurationProvider::get($id_carrier, 'discount'),
                'free' => CarrierConfigurationProvider::get($id_carrier, 'free'),
                'from' => CarrierConfigurationProvider::get($id_carrier, 'from'),
                'loadMore' => CarrierConfigurationProvider::get($id_carrier, 'loadMore'),
                'retry' => CarrierConfigurationProvider::get($id_carrier, 'retry'),
            ],
            'address' => [
                'cc' => strtoupper(Country::getIsoById($address->id_country)),
                'city' => $address->city,
                'postalCode' => $address->postcode,
                'number' => $address->address1,
            ],
            'delivery_settings' => $this->module->getDeliverySettingsByCart((int) $this->context->cart->id),
        ];

        echo json_encode($params);
        die();
    }
}
