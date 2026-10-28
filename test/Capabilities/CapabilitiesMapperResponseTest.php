<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesResponse;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * The tests read a response as the API sends it: with the camelCase field names of the v2 capabilities API.
 */
final class CapabilitiesMapperResponseTest extends TestCase
{
    private const OPTION = ['isSelectedByDefault' => false, 'isRequired' => false, 'requires' => [], 'excludes' => []];

    public function testMapFromCoreApiMergesAndDedupes(): void
    {
        $res = self::map([
            [
                'carrier'          => 'POSTNL',
                'packageTypes'     => ['PACKAGE', 'MAILBOX'],
                'deliveryTypes'    => ['STANDARD_DELIVERY', 'EVENING_DELIVERY'],
                'transactionTypes' => ['B2C'],
                'options'          => ['requiresSignature' => self::OPTION, 'recipientOnlyDelivery' => self::OPTION],
                'collo'            => ['max' => 3],
            ],
            [
                'carrier'          => 'POSTNL',
                'packageTypes'     => ['PACKAGE'],
                'deliveryTypes'    => ['STANDARD_DELIVERY', 'MORNING_DELIVERY'],
                'transactionTypes' => ['B2B'],
                'options'          => ['recipientOnlyDelivery' => self::OPTION, 'requiresReceiptCode' => self::OPTION],
                'collo'            => ['max' => 5],
            ],
        ]);

        $this->assertSame(['PACKAGE', 'MAILBOX'], $res->getPackageTypes());
        $this->assertEqualsCanonicalizing(['STANDARD_DELIVERY', 'EVENING_DELIVERY', 'MORNING_DELIVERY'], $res->getDeliveryTypes());
        $this->assertEqualsCanonicalizing(['requiresSignature', 'recipientOnlyDelivery', 'requiresReceiptCode'], $res->getShipmentOptions());
        $this->assertSame('POSTNL', $res->getCarrier());
        $this->assertEqualsCanonicalizing(['B2C', 'B2B'], $res->getTransactionTypes());
        $this->assertSame(5, $res->getColloMax());
    }

    public function testMapFromCoreApiKeepsAValueTheSpecDoesNotKnow(): void
    {
        $res = self::map([['carrier' => 'FUTURE_CARRIER', 'packageTypes' => ['FUTURE_PACKAGE']]]);

        $this->assertSame('FUTURE_CARRIER', $res->getCarrier());
        $this->assertSame(['FUTURE_PACKAGE'], $res->getPackageTypes());
    }

    public function testMapFromCoreApiSetsCarrierNullWhenInconsistent(): void
    {
        $res = self::map([['carrier' => 'POSTNL'], ['carrier' => 'DHL_FOR_YOU']]);

        $this->assertNull($res->getCarrier());
    }

    public function testMapFromCoreApiCarrierStaysNullAfterInconsistency(): void
    {
        $res = self::map([['carrier' => 'POSTNL'], ['carrier' => 'DHL_FOR_YOU'], ['carrier' => 'UPS_STANDARD']]);

        $this->assertNull($res->getCarrier());
    }

    public function testMapFromCoreApiHandlesMissingOptions(): void
    {
        $res = self::map([[
            'carrier'          => 'POSTNL',
            'packageTypes'     => ['PACKAGE'],
            'deliveryTypes'    => ['STANDARD_DELIVERY'],
            'transactionTypes' => ['B2C'],
        ]]);

        $this->assertSame(['PACKAGE'], $res->getPackageTypes());
        $this->assertSame(['STANDARD_DELIVERY'], $res->getDeliveryTypes());
        $this->assertSame([], $res->getShipmentOptions());
    }

    public function testMapFromCoreApiHandlesMissingLists(): void
    {
        $res = self::map([['carrier' => 'POSTNL', 'options' => []]]);

        $this->assertSame([], $res->getPackageTypes());
        $this->assertSame([], $res->getDeliveryTypes());
        $this->assertSame([], $res->getShipmentOptions());
        $this->assertSame([], $res->getTransactionTypes());
        $this->assertNull($res->getColloMax());
    }

    public function testMapFromCoreApiEmptyOrMissingResults(): void
    {
        $mapper = new CapabilitiesMapper();

        foreach ([['results' => []], []] as $body) {
            $res = $mapper->mapFromCoreApi(CoreApiModels::toModel($body, CapabilitiesResponsesCapabilitiesV2::class));

            $this->assertSame([], $res->getPackageTypes());
            $this->assertSame([], $res->getDeliveryTypes());
            $this->assertSame([], $res->getShipmentOptions());
            $this->assertSame([], $res->getTransactionTypes());
            $this->assertNull($res->getCarrier());
            $this->assertNull($res->getColloMax());
        }
    }

    /**
     * @param array<int, array<string, mixed>> $results
     */
    private static function map(array $results): CapabilitiesResponse
    {
        $core = CoreApiModels::toModel(['results' => $results], CapabilitiesResponsesCapabilitiesV2::class);

        return (new CapabilitiesMapper())->mapFromCoreApi($core);
    }
}
