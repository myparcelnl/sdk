<?php

use Gett\MyParcel\Service\CarrierConfigurationProvider;

class MyparcelCheckoutModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $id_carrier = intval(Tools::getValue('id_carrier'));

        $provider = new \Gett\MyParcel\Service\CarrierConfigurationProvider((int) Tools::getValue('id_carrier'));
        $address = new \Address($this->context->cart->id_address_delivery);
        $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

        $params = [
            'config' => [
                'platform' => 'myparcel',
                'carriers' => ['postnl'],

                'priceMorningDelivery' => CarrierConfigurationProvider::get($id_carrier, 'priceMorningDelivery'),
                'priceStandardDelivery' => CarrierConfigurationProvider::get($id_carrier, 'priceStandardDelivery'),
                'priceEveningDelivery' => CarrierConfigurationProvider::get($id_carrier, 'priceEveningDelivery'),
                'priceSignature' => CarrierConfigurationProvider::get($id_carrier, 'priceSignature'),
                'priceOnlyRecipient' => CarrierConfigurationProvider::get($id_carrier, 'priceOnlyRecipient'),
                'pricePickup' => CarrierConfigurationProvider::get($id_carrier, 'pricePickup'),

                'allowSaturdayDelivery' => CarrierConfigurationProvider::get($id_carrier, 'allowSaturdayDelivery'),
                'allowPickupLocations' => CarrierConfigurationProvider::get($id_carrier, 'allowPickupLocations'),
                'allowSignature' => CarrierConfigurationProvider::get($id_carrier, 'allowSignature'),

                'dropOffDays' => CarrierConfigurationProvider::get($id_carrier, 'dropOffDays'),
                'cutoffTime' => CarrierConfigurationProvider::get($id_carrier, 'cutoffTime'),
                'deliveryDaysWindow' => CarrierConfigurationProvider::get($id_carrier, 'deliveryDaysWindow'),
                'dropOffDelay' => CarrierConfigurationProvider::get($id_carrier, 'dropOffDelay'),

                'allowOnlyRecipient' => CarrierConfigurationProvider::get($id_carrier, 'allowOnlyRecipient'),
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
                'cc' => 'BE',
                'city' => $address->city,
                'postalCode' => $address->postcode,
                'number' => $address->address1,
            ],
        ];

        echo json_encode($params);
        die();
    }
}
