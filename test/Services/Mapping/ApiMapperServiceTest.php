<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * Tests all three resolution tiers and the public conversion methods.
 */
class ApiMapperServiceTest extends TestCase
{
    public function testTablesContainEveryGeneratedConcept(): void
    {
        foreach (ApiMapperService::profiles() as $domain => $profile) {
            $expected = [];

            foreach ($profile as $enumClass) {
                $expected += (new ReflectionClass($enumClass))->getConstants();
            }

            ksort($expected);

            $this->assertSame(array_keys($expected), array_keys(ApiMapperService::allDomains()[$domain]), $domain);
        }
    }

    public function testRowsAreKeyedByTheSharedConstantName(): void
    {
        $rows = ApiMapperService::forCarrier()->allRows();

        $this->assertSame(
            [
                ApiMapperService::COLUMN_LEGACY_NAME => 'dhlforyou',
                ApiMapperService::COLUMN_ID          => 9,
                ApiMapperService::COLUMN_V2_NAME     => 'DHL_FOR_YOU',
            ],
            $rows['DHL_FOR_YOU']
        );
    }

    /**
     * These four have a v1 id but no capabilities v2 name. A sparse row is the honest answer;
     * inventing a value the capabilities endpoint would reject is not.
     */
    public function testCarriersWithoutACapabilitiesValueKeepASparseRow(): void
    {
        $rows = ApiMapperService::forCarrier()->allRows();

        foreach (['BOL', 'DHL_CHEAP_CARGO', 'INSTABOX', 'UPS'] as $constantName) {
            $this->assertNull($rows[$constantName][ApiMapperService::COLUMN_V2_NAME], $constantName);
            $this->assertNotNull($rows[$constantName][ApiMapperService::COLUMN_ID], $constantName);
        }
    }

    /**
     * PICKUP has no constant in ShipmentDefsDeliveryOptionsDeliveryNameV2, so its legacy name
     * comes from the override. Its id and v2 name still come from the generated enums.
     */
    public function testPickupGetsItsLegacyNameFromTheOverride(): void
    {
        $rows = ApiMapperService::forDeliveryType()->allRows();

        $this->assertSame('pickup', $rows['PICKUP'][ApiMapperService::COLUMN_LEGACY_NAME]);
        $this->assertSame(4, $rows['PICKUP'][ApiMapperService::COLUMN_ID]);
        $this->assertSame('PICKUP_DELIVERY', $rows['PICKUP'][ApiMapperService::COLUMN_V2_NAME]);
    }

    public function testRowsAreSortedByConstantNameForDeterminism(): void
    {
        $keys = array_keys(ApiMapperService::forPackageType()->allRows());

        $sorted = $keys;
        sort($sorted);

        $this->assertSame($sorted, $keys);
    }

    public function testReadingSortedRowsDoesNotChangeTheCompatibilityMapOrder(): void
    {
        $mapper = ApiMapperService::forPackageType();
        $before = $mapper->v2ToIdMap();

        $mapper->allRows();

        $this->assertSame($before, $mapper->v2ToIdMap());
    }

    public function testAllDomainsReturnsEveryTable(): void
    {
        $all = ApiMapperService::allDomains();

        $this->assertSame(array_keys(ApiMapperService::profiles()), array_keys($all));
        $this->assertSame(ApiMapperService::forCarrier()->allRows(), $all[ApiMapperService::DOMAIN_CARRIER]);
        $this->assertSame(ApiMapperService::forDeliveryType()->allRows(), $all[ApiMapperService::DOMAIN_DELIVERY_TYPE]);
        $this->assertSame(ApiMapperService::forPackageType()->allRows(), $all[ApiMapperService::DOMAIN_PACKAGE_TYPE]);
        $this->assertJson((string) json_encode($all));
    }

    public function testAllSixDirectionsAgreeForEveryMappedConcept(): void
    {
        foreach ([ApiMapperService::forCarrier(), ApiMapperService::forDeliveryType(), ApiMapperService::forPackageType()] as $mapper) {
            foreach ($mapper->allRows() as $name => $row) {
                $legacy = $row[ApiMapperService::COLUMN_LEGACY_NAME];
                $id     = $row[ApiMapperService::COLUMN_ID];
                $v2     = $row[ApiMapperService::COLUMN_V2_NAME];

                if (null !== $id) {
                    $this->assertSame($legacy, $mapper->legacyNameFromId($id), $name);
                    $this->assertSame($v2, $mapper->v2NameFromId($id), $name);
                }

                if (null !== $legacy) {
                    $this->assertSame($id, $mapper->idFromLegacyName($legacy), $name);

                    if (null !== $v2) {
                        $this->assertSame($v2, $mapper->v2NameFromLegacyName($legacy), $name);
                        $this->assertSame($legacy, $mapper->legacyNameFromV2Name($v2), $name);
                    }
                }

                if (null !== $v2) {
                    $this->assertSame($id, $mapper->idFromV2Name($v2), $name);
                }
            }
        }
    }

    public function testNewEnumConstantsMapWithoutHandWrittenEntries(): void
    {
        $legacy = new class {
            public const FUTURE = 'future_service';
        };
        $ids = new class {
            public const FUTURE = 99;
        };
        $v2 = new class {
            public const FUTURE  = 'FUTURE_SERVICE';
            public const V2_ONLY = 'V2_ONLY';
        };
        $mapper = $this->compiledMapperFixture([
            ApiMapperService::COLUMN_LEGACY_NAME => get_class($legacy),
            ApiMapperService::COLUMN_ID          => get_class($ids),
            ApiMapperService::COLUMN_V2_NAME     => get_class($v2),
        ]);

        $this->assertSame('future_service', $mapper->legacyNameFromId(99));
        $this->assertSame('FUTURE_SERVICE', $mapper->v2NameFromId(99));
        $this->assertSame(99, $mapper->idFromLegacyName('future_service'));
        $this->assertSame('FUTURE_SERVICE', $mapper->v2NameFromLegacyName('future_service'));
        $this->assertSame(99, $mapper->idFromV2Name('FUTURE_SERVICE'));
        $this->assertSame('future_service', $mapper->legacyNameFromV2Name('FUTURE_SERVICE'));
        $this->assertSame($this->row(null, null, 'V2_ONLY'), $mapper->allRows()['V2_ONLY']);
        $this->assertNull($mapper->idFromV2Name('V2_ONLY'));
    }

    public function testValueAndAliasOverridesCompileForEveryColumn(): void
    {
        $mapper = $this->compiledMapperFixture(
            ApiMapperService::profiles()[ApiMapperService::DOMAIN_PACKAGE_TYPE],
            [
                'PACKAGE' => [
                    ApiMapperService::COLUMN_LEGACY_NAME => ['value' => 'parcel', 'aliases' => ['package']],
                    ApiMapperService::COLUMN_ID          => ['value' => 99, 'aliases' => [1]],
                    ApiMapperService::COLUMN_V2_NAME     => ['value' => 'PARCEL', 'aliases' => ['PACKAGE']],
                ],
            ]
        );

        $this->assertSame('parcel', $mapper->legacyNameFromId(99));
        $this->assertSame('PARCEL', $mapper->v2NameFromId(99));
        $this->assertSame(99, $mapper->idFromLegacyName('parcel'));
        $this->assertSame('PARCEL', $mapper->v2NameFromLegacyName('parcel'));
        $this->assertSame(99, $mapper->idFromV2Name('PARCEL'));
        $this->assertSame('parcel', $mapper->legacyNameFromV2Name('PARCEL'));
        $this->assertSame('parcel', $mapper->legacyNameFromId(1));
        $this->assertSame('PARCEL', $mapper->v2NameFromId(1));
        $this->assertSame(99, $mapper->idFromLegacyName('package'));
        $this->assertSame(99, $mapper->idFromV2Name('PACKAGE'));
        $this->assertNull($mapper->idFromLegacyName('1'));
    }

    public function testAliasOnlyAndNullOverridesSurviveCompilation(): void
    {
        $mapper = $this->compiledMapperFixture(
            ApiMapperService::profiles()[ApiMapperService::DOMAIN_PACKAGE_TYPE],
            [
                'PACKAGE' => [
                    ApiMapperService::COLUMN_LEGACY_NAME => ['aliases' => ['parcel']],
                    ApiMapperService::COLUMN_V2_NAME     => ['value' => null],
                ],
            ]
        );

        $this->assertSame('package', $mapper->legacyNameFromId(1));
        $this->assertSame(1, $mapper->idFromLegacyName('parcel'));
        $this->assertNull($mapper->v2NameFromLegacyName('package'));
        $this->assertNull($mapper->v2NameFromId(1));
        $this->assertNull($mapper->idFromV2Name('PACKAGE'));
    }

    public function testProfilesAndOverridesAreExposedForTheDriftGuard(): void
    {
        $this->assertArrayHasKey(ApiMapperService::DOMAIN_CARRIER, ApiMapperService::profiles());
        $this->assertSame([], ApiMapperService::overrides()[ApiMapperService::DOMAIN_CARRIER]);
    }

    /**
     * Tested directly rather than through the deprecated adapters that consume it, so its
     * coverage does not disappear with them if they are removed later.
     */
    public function testV2ToIdMapExcludesExactlyTheSparseRows(): void
    {
        $carrier = ApiMapperService::forCarrier();
        $map     = $carrier->v2ToIdMap();

        $this->assertCount(count($carrier->allRows()) - 4, $map);
        $this->assertSame(1, $map['POSTNL']);
        $this->assertSame(21, $map['DHL_FREIGHT']);

        foreach (['BOL', 'DHL_CHEAP_CARGO', 'INSTABOX', 'UPS'] as $sparse) {
            $this->assertArrayNotHasKey($sparse, $map, $sparse);
        }

        $this->assertArrayHasKey('BOL', $carrier->allRows(), 'allRows() must keep the sparse rows.');
    }

    /**
     * Guards the null filter. Without it PHP casts the null v2 name to '', producing a phantom
     * entry that every downstream map built from all() would inherit.
     */
    public function testV2ToIdMapNeverContainsAnEmptyKey(): void
    {
        foreach ([ApiMapperService::forCarrier(), ApiMapperService::forDeliveryType(), ApiMapperService::forPackageType()] as $mapper) {
            $map = $mapper->v2ToIdMap();

            $this->assertArrayNotHasKey('', $map);

            foreach ($map as $v2Name => $id) {
                $this->assertIsString($v2Name);
                $this->assertNotSame('', $v2Name);
                $this->assertIsInt($id);
            }
        }
    }

    public function testV2ToIdMapIsCompleteWhenNoRowIsSparse(): void
    {
        foreach ([ApiMapperService::forDeliveryType(), ApiMapperService::forPackageType()] as $mapper) {
            $this->assertCount(count($mapper->allRows()), $mapper->v2ToIdMap());
        }
    }

    /**
     * allRows() is sorted for deterministic inspection, but the deprecated adapters have always
     * exposed declaration order. These arrays are the pre-INT-1441 order, with the three newly
     * discovered carriers appended where the v1 enum declares them.
     */
    public function testV2ToIdMapsPreserveV1DeclarationOrder(): void
    {
        $this->assertExistingOrder(
            [
                'POSTNL',
                'BPOST',
                'CHEAP_CARGO',
                'DPD',
                'DHL_FOR_YOU',
                'DHL_PARCEL_CONNECT',
                'DHL_EUROPLUS',
                'UPS_STANDARD',
                'UPS_EXPRESS_SAVER',
                'GLS',
                'BRT',
                'TRUNKRS',
                'INPOST',
                'POSTE_ITALIANE',
                'SPRING',
                'VIA_TIM',
                'DHL_FREIGHT',
            ],
            array_keys(ApiMapperService::forCarrier()->v2ToIdMap())
        );

        $this->assertExistingOrder(
            [
                'MORNING_DELIVERY',
                'STANDARD_DELIVERY',
                'EVENING_DELIVERY',
                'PICKUP_DELIVERY',
                'SAME_DAY_DELIVERY',
                'EXPRESS_DELIVERY',
                'EARLY_MORNING_DELIVERY',
            ],
            array_keys(ApiMapperService::forDeliveryType()->v2ToIdMap())
        );

        $this->assertExistingOrder(
            ['PACKAGE', 'MAILBOX', 'UNFRANKED', 'DIGITAL_STAMP', 'PALLET', 'SMALL_PACKAGE', 'ENVELOPE'],
            array_keys(ApiMapperService::forPackageType()->v2ToIdMap())
        );
    }

    public function testV2ToIdMapAppendsACompleteOverrideOnlyRow(): void
    {
        $rows          = ApiMapperService::forPackageType()->allRows();
        $rows['EXTRA'] = $this->row('extra', 99, 'EXTRA');
        $mapper        = $this->mapperFixture(ApiMapperService::DOMAIN_PACKAGE_TYPE, $rows);
        $map           = $mapper->v2ToIdMap();

        $this->assertSame('EXTRA', array_key_last($map));
        $this->assertSame(99, $map['EXTRA']);
    }

    /**
     * The override replaces the compiled column, so all six directions agree by construction.
     */
    public function testOverriddenLegacyNameIsCanonicalInEveryDirection(): void
    {
        $packageType = ApiMapperService::forPackageType();

        $this->assertSame('letter', $packageType->legacyNameFromId(3));
        $this->assertSame('letter', $packageType->legacyNameFromV2Name('UNFRANKED'));
        $this->assertSame(3, $packageType->idFromLegacyName('letter'));
        $this->assertSame('UNFRANKED', $packageType->v2NameFromLegacyName('letter'));

        $this->assertSame('package_small', $packageType->legacyNameFromId(6));
        $this->assertSame(6, $packageType->idFromLegacyName('package_small'));
    }

    /**
     * The value the override displaced stays accepted as input, but never comes back as output.
     */
    public function testDisplacedSpecValueIsAcceptedAsAliasButNeverReturned(): void
    {
        $packageType = ApiMapperService::forPackageType();

        $this->assertSame(3, $packageType->idFromLegacyName('unfranked'));
        $this->assertSame(6, $packageType->idFromLegacyName('small_package'));
        $this->assertSame('UNFRANKED', $packageType->v2NameFromLegacyName('unfranked'));

        $this->assertNotSame('unfranked', $packageType->legacyNameFromId(3));
        $this->assertNotSame('small_package', $packageType->legacyNameFromId(6));
    }

    public function testPickupResolvesInBothDirections(): void
    {
        $deliveryType = ApiMapperService::forDeliveryType();

        $this->assertSame('pickup', $deliveryType->legacyNameFromId(4));
        $this->assertSame(4, $deliveryType->idFromLegacyName('pickup'));
        $this->assertSame('PICKUP_DELIVERY', $deliveryType->v2NameFromLegacyName('pickup'));
    }

    public function testAliasesDoNotLeakBetweenDomains(): void
    {
        $this->assertNull(ApiMapperService::forCarrier()->idFromLegacyName('unfranked'));
        $this->assertNull(ApiMapperService::forDeliveryType()->idFromLegacyName('unfranked'));
    }

    /**
     * An alias is legacy input only: it never resolves through the v2 column.
     */
    public function testAliasIsNotAcceptedOnTheV2Side(): void
    {
        $this->assertNull(ApiMapperService::forPackageType()->idFromV2Name('unfranked'));
        $this->assertNull(ApiMapperService::forPackageType()->idFromV2Name('letter'));
    }

    /**
     * Documents a consequence of the ticket's tier 3 that is easy to mistake for a bug.
     *
     * legacyNameFromV2Name('letter') returns 'letter' rather than null: both sides are name
     * columns, so tier 3 fires, lowercases the input and finds it in the target column. The
     * method is therefore idempotent on input that is already a legacy name.
     *
     * This verifies a target spelling, not membership in the source enum. Use the strict
     * id conversion methods when the source must be a canonical value or an explicit alias.
     */
    public function testNameToNameLookupsAreIdempotentOnInputAlreadyInTargetForm(): void
    {
        $packageType = ApiMapperService::forPackageType();

        $this->assertSame('letter', $packageType->legacyNameFromV2Name('letter'));
        $this->assertSame('UNFRANKED', $packageType->v2NameFromLegacyName('UNFRANKED'));

        // And still null for something that is in neither column.
        $this->assertNull($packageType->legacyNameFromV2Name('nonexistent'));
    }

    public function testIdFromLegacyName(): void
    {
        $this->assertSame(9, ApiMapperService::forCarrier()->idFromLegacyName('dhlforyou'));
        $this->assertSame(1, ApiMapperService::forCarrier()->idFromLegacyName('postnl'));
    }

    public function testV2NameFromId(): void
    {
        $this->assertSame('DHL_FOR_YOU', ApiMapperService::forCarrier()->v2NameFromId(9));
        $this->assertSame('POSTE_ITALIANE', ApiMapperService::forCarrier()->v2NameFromId(18));
    }

    public function testIdFromV2Name(): void
    {
        $this->assertSame(9, ApiMapperService::forCarrier()->idFromV2Name('DHL_FOR_YOU'));
    }

    public function testV2NameFromLegacyNameAndBack(): void
    {
        $carrier = ApiMapperService::forCarrier();

        $this->assertSame('POSTNL', $carrier->v2NameFromLegacyName('postnl'));
        $this->assertSame('postnl', $carrier->legacyNameFromV2Name('POSTNL'));
    }

    /**
     * The pay-off of joining on the constant name: these two legacy names follow no rule at all,
     * and they still resolve without a single hand-written entry.
     */
    public function testIrregularLegacyCarrierNamesResolveWithoutAnyOverride(): void
    {
        $carrier = ApiMapperService::forCarrier();

        $this->assertSame('bol.com', $carrier->legacyNameFromId(7));
        $this->assertSame('dhl', $carrier->legacyNameFromId(6));
        $this->assertSame(7, $carrier->idFromLegacyName('bol.com'));
    }

    public function testMissingCellReturnsNullInsteadOfInventingAValue(): void
    {
        $this->assertNull(ApiMapperService::forCarrier()->v2NameFromId(7));
    }

    public function testUnknownInputReturnsNull(): void
    {
        $carrier = ApiMapperService::forCarrier();

        $this->assertNull($carrier->idFromLegacyName('nonexistent'));
        $this->assertNull($carrier->legacyNameFromId(999));
        $this->assertNull($carrier->idFromV2Name('NONEXISTENT'));
    }

    public function testDeliveryTypeAndPackageTypeResolveToo(): void
    {
        $this->assertSame(1, ApiMapperService::forDeliveryType()->idFromLegacyName('morning'));
        $this->assertSame('MORNING_DELIVERY', ApiMapperService::forDeliveryType()->v2NameFromId(1));

        $this->assertSame(1, ApiMapperService::forPackageType()->idFromLegacyName('package'));
        $this->assertSame('DIGITAL_STAMP', ApiMapperService::forPackageType()->v2NameFromId(4));
    }

    /**
     * The canonical key is an array key, not an API value. RefTypesDeliveryTypeV2::PICKUP is
     * keyed 'PICKUP' but spelled 'PICKUP_DELIVERY', so the key itself does not resolve.
     */
    public function testConstantNamesAreNotApiValues(): void
    {
        $this->assertNull(ApiMapperService::forDeliveryType()->idFromV2Name('PICKUP'));
        $this->assertSame(4, ApiMapperService::forDeliveryType()->idFromV2Name('PICKUP_DELIVERY'));
    }

    public function testNamesAreNormalisedTowardsTheTargetDomain(): void
    {
        $carrier = ApiMapperService::forCarrier();

        $this->assertSame('DHL_FOR_YOU', $carrier->v2NameFromLegacyName('DHL_FOR_YOU'));
        $this->assertSame('DHL_FOR_YOU', $carrier->v2NameFromLegacyName('dhlForYou'));
        $this->assertSame('dhlforyou', $carrier->legacyNameFromV2Name('DHL-For-You'));
        $this->assertSame('postnl', $carrier->legacyNameFromV2Name('postnl'));

        $packageType = ApiMapperService::forPackageType();

        $this->assertSame('DIGITAL_STAMP', $packageType->v2NameFromLegacyName('digitalStamp'));
        $this->assertSame('digital_stamp', $packageType->legacyNameFromV2Name('DIGITAL-STAMP'));
    }

    public function testMissingAutomaticTargetFallsThroughToTierThree(): void
    {
        $mapper = $this->mapperFixture(
            ApiMapperService::DOMAIN_PACKAGE_TYPE,
            [
                'SOURCE_SPELLING' => $this->row('digitalStamp', null, null),
                'TARGET_SPELLING' => $this->row(null, null, 'DIGITAL_STAMP'),
            ]
        );

        $this->assertSame('DIGITAL_STAMP', $mapper->v2NameFromLegacyName('digitalStamp'));
    }

    public function testAliasToSparseRowDoesNotFallThroughToAnotherConcept(): void
    {
        $mapper = $this->mapperFixture(
            ApiMapperService::DOMAIN_PACKAGE_TYPE,
            [
                'SOURCE_CONCEPT' => $this->row('new_name', 42, null),
                'TARGET_CONCEPT' => $this->row(null, null, 'OLD_NAME'),
            ],
            ['SOURCE_CONCEPT' => [ApiMapperService::COLUMN_LEGACY_NAME => ['aliases' => ['old_name']]]]
        );

        $this->assertNull($mapper->v2NameFromLegacyName('old_name'));
    }

    public function testExplicitNullTargetBlocksTierThree(): void
    {
        $mapper = $this->mapperFixture(
            ApiMapperService::DOMAIN_PACKAGE_TYPE,
            [
                'SOURCE_SPELLING' => $this->row('digitalStamp', null, null),
                'TARGET_SPELLING' => $this->row(null, null, 'DIGITAL_STAMP'),
            ],
            ['SOURCE_SPELLING' => [ApiMapperService::COLUMN_V2_NAME => ['value' => null]]]
        );

        $this->assertNull($mapper->v2NameFromLegacyName('digitalStamp'));
    }

    public function testOverriddenSourceWithMissingTargetDoesNotSelectAnotherConcept(): void
    {
        $mapper = $this->mapperFixture(
            ApiMapperService::DOMAIN_PACKAGE_TYPE,
            [
                'SOURCE_CONCEPT' => $this->row('manual_name', 42, null),
                'TARGET_CONCEPT' => $this->row(null, null, 'MANUAL_NAME'),
            ],
            ['SOURCE_CONCEPT' => [ApiMapperService::COLUMN_LEGACY_NAME => ['value' => 'manual_name']]]
        );

        $this->assertNull($mapper->v2NameFromLegacyName('manual_name'));
    }

    /**
     * Aliases are column-scoped rather than legacy-only. This fixture also documents the
     * alias-only lifecycle: the canonical generated value stays untouched while an old input
     * spelling continues to resolve.
     */
    public function testAliasOnlyOverrideCanTargetEitherNameColumn(): void
    {
        $mapper = $this->mapperFixture(
            ApiMapperService::DOMAIN_PACKAGE_TYPE,
            ['EXAMPLE' => $this->row('new_name', 42, 'NEW_NAME')],
            [
                'EXAMPLE' => [
                    ApiMapperService::COLUMN_LEGACY_NAME => ['aliases' => ['old_name']],
                    ApiMapperService::COLUMN_V2_NAME     => ['aliases' => ['OLD_NAME']],
                ],
            ]
        );

        $this->assertSame(42, $mapper->idFromLegacyName('old_name'));
        $this->assertSame(42, $mapper->idFromV2Name('OLD_NAME'));
        $this->assertSame('new_name', $mapper->legacyNameFromId(42));
    }

    /**
     * Tier 3 must never invent a value: 'MORNING' uppercases to itself, but the v2 enum spells
     * it 'MORNING_DELIVERY', so there is nothing to return.
     */
    public function testTierThreeNeverInventsAValueThatDoesNotExist(): void
    {
        $this->assertNull(ApiMapperService::forDeliveryType()->v2NameFromLegacyName('MORNING'));
        $this->assertNull(ApiMapperService::forCarrier()->v2NameFromLegacyName('NOPE'));
    }

    /**
     * Tier 3 is narrow by design: with an id on either side it does not fire at all. This is what
     * keeps the deprecated facades behaving exactly as they did — every path through them has an
     * id on one side, which is why PackageType::isValid('package') stays false.
     */
    public function testWrongCaseDoesNotResolveWhenAnIdIsInvolved(): void
    {
        $this->assertNull(ApiMapperService::forCarrier()->idFromLegacyName('DHL_FOR_YOU'));
        $this->assertNull(ApiMapperService::forPackageType()->idFromV2Name('package'));
        $this->assertNull(ApiMapperService::forDeliveryType()->idFromLegacyName('MORNING'));

        // The id side is unaffected: it still resolves through tier 2, in canonical form.
        $this->assertSame('dhlforyou', ApiMapperService::forCarrier()->legacyNameFromId(9));
    }

    /**
     * Keep existing values and their relative order while allowing new generated entries.
     *
     * @param  string[] $expected
     * @param  string[] $actual
     *
     * @return void
     */
    private function assertExistingOrder(array $expected, array $actual): void
    {
        $this->assertSame($expected, array_values(array_intersect($actual, $expected)));
    }

    /**
     * Exercise the real compilation steps with isolated source enums and overrides.
     *
     * @param  array<string, class-string> $profile
     * @param  array<string, mixed>        $overrides
     *
     * @return \MyParcelNL\Sdk\Services\Mapping\ApiMapperService
     */
    private function compiledMapperFixture(array $profile, array $overrides = []): ApiMapperService
    {
        $method = new ReflectionMethod(ApiMapperService::class, 'compile');

        if (PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }

        $rows = $method->invoke(null, $profile, $overrides);

        return $this->mapperFixture(ApiMapperService::DOMAIN_PACKAGE_TYPE, $rows, $overrides);
    }

    /**
     * @param  string                                       $domain
     * @param  array<string, array<string, int|string|null>> $rows
     * @param  array<string, mixed>                         $overrides
     *
     * @return \MyParcelNL\Sdk\Services\Mapping\ApiMapperService
     */
    private function mapperFixture(string $domain, array $rows, array $overrides = []): ApiMapperService
    {
        $reflection = new ReflectionClass(ApiMapperService::class);

        /** @var \MyParcelNL\Sdk\Services\Mapping\ApiMapperService $mapper */
        $mapper = $reflection->newInstanceWithoutConstructor();

        foreach (['domain' => $domain, 'rows' => $rows, 'mappingOverrides' => $overrides] as $name => $value) {
            $property = $reflection->getProperty($name);

            if (PHP_VERSION_ID < 80100) {
                $property->setAccessible(true);
            }

            $property->setValue($mapper, $value);
        }

        return $mapper;
    }

    /**
     * @param  int|string|null $legacyName
     * @param  int|string|null $id
     * @param  int|string|null $v2Name
     * @return array<string, int|string|null>
     */
    private function row($legacyName, $id, $v2Name): array
    {
        return [
            ApiMapperService::COLUMN_LEGACY_NAME => $legacyName,
            ApiMapperService::COLUMN_ID          => $id,
            ApiMapperService::COLUMN_V2_NAME     => $v2Name,
        ];
    }
}
