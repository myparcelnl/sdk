<?php

declare(strict_types=1);

use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierRedJePakketje;
use MyParcelNL\Sdk\src\Model\Consignment\DropOffPoint;
use MyParcelNL\Sdk\src\Services\Web\DropOffPointWebService;
use PHPUnit\Framework\TestCase;

class DropOffPointTest extends TestCase
{
    public function provideTestDropOffPointData(): array
    {
        return [
            'RedJePakketje' => [
                [
                    'cc'          => 'NL',
                    'company'     => 'MyParcel',
                    'person'      => 'Mr. Parcel',
                    'full_street' => 'Meander 631',
                    'postal_code' => '6825ME',
                    'city'        => 'Arnhem',
                    'phone'       => '123456',
                ],
            ],
        ];
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     * @dataProvider provideTestDropOffPointData
     */
    public function testDropOffPoint(array $consignmentTest): void
    {
        $consignment = ConsignmentFactory::createByCarrierId(CarrierRedJePakketje::getId())
            ->setApiKey(getenv('API_KEY'))
            ->setCountry($consignmentTest['cc'])
            ->setPerson($consignmentTest['person'])
            ->setCompany($consignmentTest['company'])
            ->setFullStreet($consignmentTest['full_street'])
            ->setPostalCode($consignmentTest['postal_code'])
            ->setCity($consignmentTest['city'])
            ->setEmail('your_email@test.nl')
            ->setPhone($consignmentTest['phone']);

        $dropOffPoints = (new DropOffPointWebService())
            ->setApiKey(getenv('API_KEY'))
            ->getDropOffPoints($consignmentTest['postal_code']);

        self::assertNotEmpty($dropOffPoints);

        $dropOffPoint = (new DropOffPoint())
            ->setBoxNumber()
            ->setCc($dropOffPoints[0]['cc'] ?? null)
            ->setCity($dropOffPoints[0]['city'] ?? null)
            ->setLocationCode($dropOffPoints[0]['location_code'] ?? null)
            ->setLocationName($dropOffPoints[0]['location_name'] ?? null)
            ->setNumber($dropOffPoints[0]['number'] ?? null)
            ->setNumberSuffix($dropOffPoints[0]['number_suffix'] ?? null)
            ->setPostalCode($dropOffPoints[0]['postal_code'] ?? null)
            ->setRegion($dropOffPoints[0]['region'] ?? null)
            ->setRetailNetworkId($dropOffPoints[0]['retail_network_id'] ?? null)
            ->setState($dropOffPoints[0]['state'] ?? null)
            ->setStreet($dropOffPoints[0]['street'] ?? null);

        $consignment->setDropOffPoint($dropOffPoint);

        $collection = new MyParcelCollection();
        $collection->addConsignment($consignment);
        $collection->setLinkOfLabels();

        self::assertEquals(
            true,
            preg_match(
                "#^https://api(\.[a-z]+)?\.myparcel\.nl/pdfs#",
                $collection->getLinkOfLabels()
            ),
            'Can\'t get link of PDF'
        );
    }
}
