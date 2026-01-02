<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Model\Consignment;

use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\Services\ConsignmentEncode;
use MyParcelNL\Sdk\Test\Bootstrap\ConsignmentTestCase;

class PostNLPriorityTest extends ConsignmentTestCase
{
    public function testPriorityDeliveryOption(): void
    {
        $consignment = new PostNLConsignment();
        $consignment
            ->setCountry('NL')
            ->setPostalCode('1234AB')
            ->setStreet('Street')
            ->setNumber('1')
            ->setCity('City')
            ->setPerson('Person')
            ->setPackageType(AbstractConsignment::PACKAGE_TYPE_MAILBOX)
            ->setPriorityDelivery(true);

        $this->assertTrue($consignment->isPriorityDelivery(), 'Priority delivery should be true');

        $encoder = new ConsignmentEncode([$consignment]);
        $encoded = $encoder->apiEncode();

        $this->assertArrayHasKey('options', $encoded);
        $this->assertArrayHasKey('priority_delivery', $encoded['options']);
        $this->assertEquals(1, $encoded['options']['priority_delivery'], 'Priority delivery should be encoded as 1');
    }

    public function testPriorityDeliveryOptionNotAllowedForPackage(): void
    {
        $consignment = new PostNLConsignment();
        $consignment
            ->setCountry('NL')
            ->setPackageType(AbstractConsignment::PACKAGE_TYPE_PACKAGE)
            ->setPriorityDelivery(true);

        $this->assertFalse($consignment->isPriorityDelivery(), 'Priority delivery should be false for standard package');
    }
}
