<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Factory;

use BadMethodCallException;
use DateTime;
use MyParcelNL\Sdk\src\Factory\DeliveryOptionsAdapterFactory;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierPostNL;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

class DeliveryOptionsAdapterFactoryTest extends TestCase
{
    /**
     * @return array
     * @throws \Exception
     */
    public function provideCreateData(): array
    {
        $date = (new DateTime())->format('Ymd H:i:s');
        return $this->createProviderDataset([
            'DeliveryOptionsV2Adapter'                                => [
                'carrier' => CarrierPostNL::NAME,
                'date'    => $date,
                'time'    => [
                    [
                        'type' => AbstractConsignment::DELIVERY_TYPE_STANDARD,
                    ],
                ],
                'options' => [
                    'signature'      => true,
                    'only_recipient' => true,
                    'insurance'      => 5000,
                ],
            ],
            'DeliveryOptionsV2Adapter with pickup'                    => [
                'carrier'           => CarrierPostNL::NAME,
                'date'              => $date,
                'time'              => [
                    [
                        'type' => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    ],
                ],
                'options'           => [
                    'signature'      => true,
                    'only_recipient' => true,
                    'insurance'      => 5000,
                ],
                'cc'                => AbstractConsignment::CC_NL,
                'city'              => 'Hoofddorp',
                'location_code'     => '123456',
                'location_name'     => 'Primera Sanders',
                'number'            => '1',
                'postal_code'       => '2132BA',
                'retail_network_id' => 'PNPNL-01',
                'street'            => 'Polderplein',
            ],
            'DeliveryOptionsV2Adapter with pickup but empty location' => [
                'carrier' => CarrierPostNL::NAME,
                'date'    => $date,
                'time'    => [
                    [
                        'type' => AbstractConsignment::DELIVERY_TYPE_PICKUP,
                    ],
                ],
                'options' => [],
            ],
            'DeliveryOptionsV3Adapter'                                => [
                'carrier'         => CarrierPostNL::NAME,
                'date'            => $date,
                'deliveryType'    => AbstractConsignment::DELIVERY_TYPE_STANDARD_NAME,
                'packageType'     => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                'shipmentOptions' => [
                    'signature'         => true,
                    'only_recipient'    => true,
                    'insurance'         => 1000,
                    'age_check'         => true,
                    'large_format'      => true,
                    'return'            => true,
                    'label_description' => $this->faker->words(3, true),
                ],
                'pickupLocation'  => null,
            ],
            'DeliveryOptionsV3Adapter with pickup'                    => [
                'carrier'         => CarrierPostNL::NAME,
                'date'            => $date,
                'deliveryType'    => AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
                'packageType'     => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                'shipmentOptions' => [
                    'signature'         => true,
                    'only_recipient'    => true,
                    'insurance'         => 1000,
                    'age_check'         => true,
                    'large_format'      => true,
                    'return'            => true,
                    'label_description' => $this->faker->words(3, true),
                ],
                'pickupLocation'  => [
                    'country'           => AbstractConsignment::CC_NL,
                    'location_code'     => '123456',
                    'retail_network_id' => 'PNPNL-01',
                    'location_name'     => 'Primera Sanders',
                    'street'            => 'Polderplein',
                    'number'            => '1',
                    'postal_code'       => '2132BA',
                    'city'              => 'Hoofddorp',
                ],
            ],
            'DeliveryOptionsV3Adapter with null values'               => [
                'carrier'         => null,
                'date'            => null,
                'deliveryType'    => null,
                'packageType'     => null,
                'shipmentOptions' => null,
                'pickupLocation'  => null,
            ],
            'DeliveryOptionsV3Adapter with pickup but empty location' => [
                'carrier'         => CarrierPostNL::NAME,
                'date'            => $date,
                'deliveryType'    => AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME,
                'packageType'     => AbstractConsignment::PACKAGE_TYPE_PACKAGE_NAME,
                'shipmentOptions' => [],
                'pickupLocation'  => [],
            ],
        ]);
    }

    /**
     * @param  array $testData
     *
     * @throws \Exception
     * @dataProvider provideCreateData
     */
    public function testCreate(array $testData): void
    {
        $deliveryOptions = DeliveryOptionsAdapterFactory::create($testData);

        self::assertIsArray($deliveryOptions->toArray());
        // Expect no exceptions to be thrown from the following statements:
        $deliveryOptions->getCarrierId();
        $deliveryOptions->getDeliveryTypeId();
        $deliveryOptions->getPackageTypeId();
    }

    /**
     * @throws \Exception
     */
    public function testCreateInvalid(): void
    {
        $this->expectException(BadMethodCallException::class);
        DeliveryOptionsAdapterFactory::create(['this_does' => 'not belong']);
    }
}
