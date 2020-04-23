<?php

class MyparcelCheckoutModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $provider = new \Gett\MyParcel\Service\CarrierConfigurationProvider((int) Tools::getValue('id_carrier'));
        $address = new \Address($this->context->cart->id_address_delivery);
        $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

        $params = [
            'config' => [
                'platform' => 'myparcel',
                'carriers' => ['postnl'],

                'priceMorningDelivery' => $provider->get('priceMorningDelivery'),
                'priceStandardDelivery' => $provider->get('priceStandardDelivery'),
                'priceEveningDelivery' => $provider->get('priceEveningDelivery'),
                'priceSignature' => $provider->get('priceSignature'),
                'priceOnlyRecipient' => $provider->get('priceOnlyRecipient'),
                'pricePickup' => $provider->get('pricePickup'),

                'allowSaturdayDelivery' => $provider->get('allowSaturdayDelivery'),
                'allowPickupLocations' => $provider->get('allowPickupLocations'),
                'allowSignature' => $provider->get('allowSignature'),

                'dropOffDays' => $provider->get('dropOffDays'),
                'cutoffTime' => $provider->get('cutoffTime'),
                'deliveryDaysWindow' => $provider->get('deliveryDaysWindow'),
                'dropOffDelay' => $provider->get('dropOffDelay'),
            ],
            'strings' => [
                'wrongPostalCodeCity' => $provider->get('wrongPostalCodeCity'),
                'saturdayDeliveryTitle' => $provider->get('saturdayDeliveryTitle'),

                'city' => $provider->get('city'),
                'postcode' => $provider->get('postcode'),
                'houseNumber' => $provider->get('houseNumber'),
                'addressNotFound' => $provider->get('addressNotFound'),

                'deliveryEveningTitle' => $provider->get('deliveryEveningTitle'),
                'deliveryMorningTitle' => $provider->get('deliveryMorningTitle'),
                'deliveryStandardTitle' => $provider->get('deliveryStandardTitle'),

                'deliveryTitle' => $provider->get('deliveryTitle'),
                'pickupTitle' => $provider->get('pickupTitle'),

                'onlyRecipientTitle' => $provider->get('onlyRecipientTitle'),
                'signatureTitle' => $provider->get('signatureTitle'),

                'pickUpFrom' => $provider->get('pickUpFrom'),
                'openingHours' => $provider->get('openingHours'),

                'closed' => $provider->get('closed'),
                'discount' => $provider->get('discount'),
                'free' => $provider->get('free'),
                'from' => $provider->get('from'),
                'loadMore' => $provider->get('loadMore'),
                'retry' => $provider->get('retry'),
            ],
            'address' => [
                'cc' => 'NL',
                'city' => $address->city,
                'postalCode' => $address->postcode,
            ],
        ];

        echo json_encode($params);
        die();
    }
}
