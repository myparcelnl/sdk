<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class AbstractConsignmentTest extends TestCase
{
    private const TEST_DATA = [
        'cc'                  => 'NL',
        'city'                => 'Heerhugowaard',
        'street'              => 'Dorpsstraat',
        'number'              => '1',
        'box_number'          => '2',
        'number_suffix'       => 'b',
        'region'              => 'Noord-Holland',
        'state'               => 'NH',
        'postal_code'         => '1701AA',
        'person'              => 'Pietje Puk',
        'package_type'        => AbstractConsignment::PACKAGE_TYPE_PACKAGE,
        'only_recipient'      => true,
        'signature'           => true,
        'return'              => true,
        'large_format'        => true,
        'age_check'           => true,
        'label_description'   => 'Voorbeeld123',
        'shop_id'             => 1,
        'status'              => 99,
        'external_identifier' => '123456789',
        'barcode'             => '3STOIS912345678',
        'history'             => [
            [
                'status' => 1,
                'time'   => '2023-01-01 12:00:00',
                'text'   => 'Test status',
            ],
            [
                'status' => 2,
                'time'   => '2023-01-02 12:00:00',
                'text'   => 'Test status 2',
            ],
        ],
        'link_tracktrace'     => 'https://postnl.nl/tracktrace/?B=3SMYPA126329191&P=2182KD&D=NL&T=C&L=NL',
    ];

    /**
     * @return void
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function testAbstractConsignment(): void
    {
        $consignment = new PostNLConsignment();

        $consignment
            ->setCountry(self::TEST_DATA['cc'])
            ->setCity(self::TEST_DATA['city'])
            ->setStreet(self::TEST_DATA['street'])
            ->setNumber(self::TEST_DATA['number'])
            ->setBoxNumber(self::TEST_DATA['box_number'])
            ->setNumberSuffix(self::TEST_DATA['number_suffix'])
            ->setRegion(self::TEST_DATA['region'])
            ->setState(self::TEST_DATA['state'])
            ->setPostalCode(self::TEST_DATA['postal_code'])
            ->setPerson(self::TEST_DATA['person'])
            ->setPackageType(self::TEST_DATA['package_type'])
            ->setOnlyRecipient(self::TEST_DATA['only_recipient'])
            ->setSignature(self::TEST_DATA['signature'])
            ->setReturn(self::TEST_DATA['return'])
            ->setLargeFormat(self::TEST_DATA['large_format'])
            ->setAgeCheck(self::TEST_DATA['age_check'])
            ->setLabelDescription(self::TEST_DATA['label_description'])
            ->setShopId(self::TEST_DATA['shop_id'])
            ->setStatus(self::TEST_DATA['status'])
            ->setExternalIdentifier(self::TEST_DATA['external_identifier'])
            ->setBarcode(self::TEST_DATA['barcode'])
            ->setHistory(self::TEST_DATA['history'])
            ->setTrackTraceUrl(self::TEST_DATA['link_tracktrace']);

        self::assertEquals(self::TEST_DATA['cc'], $consignment->getCountry());
        self::assertEquals(self::TEST_DATA['city'], $consignment->getCity());
        self::assertEquals(self::TEST_DATA['street'], $consignment->getStreet());
        self::assertEquals(self::TEST_DATA['number'], $consignment->getNumber());
        self::assertEquals(self::TEST_DATA['box_number'], $consignment->getBoxNumber());
        self::assertEquals(self::TEST_DATA['number_suffix'], $consignment->getNumberSuffix());
        self::assertEquals(self::TEST_DATA['region'], $consignment->getRegion());
        self::assertEquals(self::TEST_DATA['state'], $consignment->getState());
        self::assertEquals(self::TEST_DATA['postal_code'], $consignment->getPostalCode());
        self::assertEquals(self::TEST_DATA['person'], $consignment->getPerson());
        self::assertEquals(self::TEST_DATA['only_recipient'], $consignment->isOnlyRecipient());
        self::assertEquals(self::TEST_DATA['signature'], $consignment->isSignature());
        self::assertEquals(self::TEST_DATA['return'], $consignment->isReturn());
        self::assertEquals(self::TEST_DATA['large_format'], $consignment->isLargeFormat());
        self::assertEquals(self::TEST_DATA['age_check'], $consignment->hasAgeCheck());
        self::assertEquals(self::TEST_DATA['label_description'], $consignment->getLabelDescription());
        self::assertEquals(self::TEST_DATA['shop_id'], $consignment->getShopId());
        self::assertEquals(self::TEST_DATA['status'], $consignment->getStatus());
        self::assertEquals(self::TEST_DATA['external_identifier'], $consignment->getExternalIdentifier());
        self::assertEquals(self::TEST_DATA['barcode'], $consignment->getBarcode());
        self::assertEquals(self::TEST_DATA['history'], $consignment->getHistory());
        self::assertEquals(self::TEST_DATA['link_tracktrace'], $consignment->getTrackTraceUrl());
    }
}
