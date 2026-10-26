<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Pins the public output of the mapping services. A change of API client generator must not change this output.
 *
 * Set UPDATE_SNAPSHOTS=1 to write the snapshot again after a deliberate change.
 */
class MappingSnapshotTest extends TestCase
{
    private const SNAPSHOT = __DIR__ . '/Snapshots/mapping.json';

    /**
     * @var array<string, string> Domain => factory method on ApiMapperService.
     */
    private const MAPPERS = [
        ApiMapperService::DOMAIN_CARRIER       => 'forCarrier',
        ApiMapperService::DOMAIN_DELIVERY_TYPE => 'forDeliveryType',
        ApiMapperService::DOMAIN_PACKAGE_TYPE  => 'forPackageType',
    ];

    /**
     * The JSON names of the v2 capabilities options. This list is written out, so the input does not come from
     * generated code.
     */
    private const V2_OPTION_NAMES = [
        'additionalInsurance',
        'deliverAtPostalPoint',
        'freshFood',
        'frozen',
        'hideSender',
        'insurance',
        'noTracking',
        'oversizedPackage',
        'printReturnLabelAtDropOff',
        'priorityDelivery',
        'recipientOnlyDelivery',
        'returnOnFirstFailedDelivery',
        'requiresAgeVerification',
        'requiresCashOnDelivery',
        'requiresReceiptCode',
        'requiresSignature',
        'sameDayDelivery',
        'saturdayDelivery',
        'scheduledCollection',
    ];

    public function testOutputMatchesSnapshot(): void
    {
        $actual = [
            'api_mapper'             => $this->buildApiMapperSnapshot(),
            'shipment_option_mapper' => $this->buildShipmentOptionMapperSnapshot(),
        ];

        if (getenv('UPDATE_SNAPSHOTS') || ! is_file(self::SNAPSHOT)) {
            file_put_contents(
                self::SNAPSHOT,
                json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n"
            );

            self::markTestIncomplete('The test wrote the snapshot. Check the diff, then run the test again.');
        }

        $expected = json_decode((string) file_get_contents(self::SNAPSHOT), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame($expected, $actual);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildApiMapperSnapshot(): array
    {
        $snapshot = ['all_domains' => ApiMapperService::allDomains()];

        foreach (self::MAPPERS as $domain => $factory) {
            $mapper = ApiMapperService::$factory();
            $inputs = $this->inputsFor($mapper);

            $snapshot[$domain] = [
                'v2_to_id_map'             => $mapper->v2ToIdMap(),
                'legacy_name_from_id'      => $this->mapEach($inputs['id'], [$mapper, 'legacyNameFromId']),
                'v2_name_from_id'          => $this->mapEach($inputs['id'], [$mapper, 'v2NameFromId']),
                'id_from_legacy_name'      => $this->mapEach($inputs['legacy_name'], [$mapper, 'idFromLegacyName']),
                'v2_name_from_legacy_name' => $this->mapEach($inputs['legacy_name'], [$mapper, 'v2NameFromLegacyName']),
                'id_from_v2_name'          => $this->mapEach($inputs['v2_name'], [$mapper, 'idFromV2Name']),
                'legacy_name_from_v2_name' => $this->mapEach($inputs['v2_name'], [$mapper, 'legacyNameFromV2Name']),
            ];
        }

        return $snapshot;
    }

    /**
     * Return the values to feed into the lookup methods of one domain.
     *
     * @return array{id: int[], legacy_name: string[], v2_name: string[]}
     */
    private function inputsFor(ApiMapperService $mapper): array
    {
        $rows = $mapper->allRows();

        $ids = array_values(array_filter(array_column($rows, ApiMapperService::COLUMN_ID), fn ($value) => null !== $value));

        $legacyNames = array_values(array_filter(array_column($rows, ApiMapperService::COLUMN_LEGACY_NAME), fn ($value) => null !== $value));

        $v2Names = array_values(array_filter(array_column($rows, ApiMapperService::COLUMN_V2_NAME), fn ($value) => null !== $value));

        return [
            'id'          => $ids,
            'legacy_name' => $legacyNames,
            'v2_name'     => $v2Names,
        ];
    }

    /**
     * @return array<string, null|string>
     */
    private function buildShipmentOptionMapperSnapshot(): array
    {
        $names = array_merge(
            array_keys(ShipmentOptionMapper::aliases()),
            array_values(ShipmentOptionMapper::aliases()),
            ShipmentOptionMapper::unmappableOptions(),
            self::V2_OPTION_NAMES,
            ['SAME_DAY_DELIVERY', 'unknown_option']
        );

        return $this->mapEach(array_values(array_unique($names)), [new ShipmentOptionMapper(), 'v2PropertyFromName']);
    }

    /**
     * Call the method once for each input, and key the result by the input.
     *
     * @param  array<int|string> $inputs
     *
     * @return array<int|string, mixed>
     */
    private function mapEach(array $inputs, callable $method): array
    {
        $result = [];

        foreach ($inputs as $input) {
            $result[$input] = $method($input);
        }

        return $result;
    }
}
