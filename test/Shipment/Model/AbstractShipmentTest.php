<?php

use MyParcelNL\Sdk\src\Model\Address;
use MyParcelNL\Sdk\src\Model\Shipment\PickupLocation;
use MyParcelNL\Sdk\src\Shipment\Model\AbstractShipment;

it('works', function () {
    $address = new Address();

    $address->country = 'NL';
    $address->fullStreet = 'Antareslaan 31';

    expect(
        (new AbstractShipment([
            'recipient' => $address,
        ]))->toArray()
    )->toEqual([]);
});


it('pickup location', function() {
    $pickupLocation = new PickupLocation();

    expect($pickupLocation->toArray())->toEqual([]);
});
