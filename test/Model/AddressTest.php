<?php

namespace MyParcelNL\Sdk\Test\Model;

use MyParcelNL\Sdk\src\Model\Address;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    /**
     * @return void
     */
    public function testAddress(): void
    {
        $address = new Address([
            'person'     => 'Ms. Parcel',
            'phone'      => '0612345678',
            'postalCode' => '2132JE',
            'country'    => 'NL',
            'fullStreet' => 'Antareslaan 31',
        ]);

        self::assertEquals([
            'country'              => 'NL',
            'boxNumber'            => null,
            'email'                => null,
            'number'               => '31',
            'numberSuffix'         => null,
            'person'               => 'Ms. Parcel',
            'phone'                => '0612345678',
            'postalCode'           => '2132JE',
            'region'               => null,
            'street'               => 'Antareslaan',
            'streetAdditionalInfo' => null,
            'fullStreet'           => 'Antareslaan 31',
        ], $address->toArray());
    }
}
