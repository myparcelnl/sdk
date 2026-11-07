<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV2Options;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesDeliveryOptionsPackageTypeV2;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Detects orphaned source models, redundant overrides and ambiguous mappings after regeneration.
 */
class SpecDriftTest extends TestCase
{
    /**
     * Jane removes the files of a class that the spec no longer has (`clean-generated`), so an
     * enum that still exists is still generated.
     */
    public function testEverySourceEnumIsStillGenerated(): void
    {
        foreach (ApiMapperService::profiles() as $domain => $profile) {
            foreach ($profile as $column => $enumClass) {
                $this->assertTrue(
                    enum_exists($enumClass),
                    sprintf(
                        '%s (%s.%s) is no longer generated. Point this profile at the enum that replaced it.',
                        $enumClass,
                        $domain,
                        $column
                    )
                );
            }
        }
    }

    /**
     * ShipmentOptionMapper reads the shipment option names from this model.
     */
    public function testTheOptionModelIsStillGenerated(): void
    {
        $this->assertTrue(
            class_exists(CapabilitiesPostCapabilitiesRequestV2Options::class),
            sprintf(
                '%s is no longer generated. Review the shipment-option mapping sources.',
                CapabilitiesPostCapabilitiesRequestV2Options::class
            )
        );
    }

    /**
     * The spec values behind the package-type overrides, recorded exactly.
     *
     * If one changes, review the override before you touch it. SmallPackage caught up with the
     * delivery-options spelling, so only its old small_package alias remains. Unfranked and Envelope
     * are never returned by delivery options, so their legacy names letter and envelope come from
     * the overrides.
     */
    public function testDisplacedPackageTypeSpecValuesAreUnchanged(): void
    {
        $cases = self::cases(ShipmentResponsesDeliveryOptionsPackageTypeV2::class);

        $this->assertSame('package_small', $cases['SmallPackage'] ?? null);
        $this->assertArrayNotHasKey('Unfranked', $cases);
        $this->assertArrayNotHasKey('Envelope', $cases);
    }

    public function testPickupIsStillAbsentFromTheDeliveryOptionsEnum(): void
    {
        $cases = self::cases(ShipmentDefsDeliveryOptionsDeliveryNameV2::class);

        $this->assertArrayNotHasKey(
            'Pickup',
            $cases,
            'The delivery-options enum now names Pickup. Drop the Pickup override so the value '
            . 'comes from the generated enum instead.'
        );
    }

    /**
     * The self-retiring part: an override that matches what the generator already supplies is
     * dead weight and must be deleted.
     */
    public function testNoOverrideDuplicatesWhatTheGeneratedEnumAlreadySupplies(): void
    {
        // Passes without further assertions when no override value has a generated counterpart.
        $this->addToAssertionCount(1);

        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            foreach ($overrides as $caseName => $columnOverrides) {
                foreach ($columnOverrides as $column => $override) {
                    if (! array_key_exists('value', $override)) {
                        // Alias-only entries intentionally survive after the generated canonical
                        // value catches up; only their backwards-compatibility alias remains.
                        continue;
                    }

                    $enumClass = ApiMapperService::profiles()[$domain][$column];
                    $generated = self::cases($enumClass);

                    if (! array_key_exists($caseName, $generated)) {
                        continue;
                    }

                    $this->assertNotSame(
                        $generated[$caseName],
                        $override['value'],
                        sprintf(
                            'The %s.%s.%s override now equals %s::%s. Delete its value: the '
                            . 'generated enum supplies it on its own. Preserve aliases that are '
                            . 'still part of the backwards-compatibility contract.',
                            $domain,
                            $caseName,
                            $column,
                            $enumClass,
                            $caseName
                        )
                    );
                }
            }
        }
    }

    /**
     * Check the override keys against the source cases, so a misspelling cannot create an extra row.
     */
    public function testEveryOverrideStillPointsAtAKnownCase(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            $known = [];

            foreach (ApiMapperService::profiles()[$domain] as $enumClass) {
                $known += self::cases($enumClass);
            }

            foreach (array_keys($overrides) as $caseName) {
                $this->assertArrayHasKey(
                    $caseName,
                    $known,
                    sprintf(
                        'The %s override for %s matches no case in any of that profile\'s source '
                        . 'enums. Either it is a typo, or the concept left the spec.',
                        $domain,
                        $caseName
                    )
                );
            }
        }
    }

    public function testEveryOverrideTargetsAKnownColumn(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            $knownColumns = array_keys(ApiMapperService::profiles()[$domain]);

            foreach ($overrides as $caseName => $columnOverrides) {
                foreach (array_keys($columnOverrides) as $column) {
                    $this->assertContains(
                        $column,
                        $knownColumns,
                        sprintf('The %s.%s override targets unknown column %s.', $domain, $caseName, $column)
                    );
                }
            }
        }
    }

    public function testOverrideConfigurationHasValidValuesAndAliases(): void
    {
        foreach (ApiMapperService::overrides() as $overrides) {
            foreach ($overrides as $columns) {
                foreach ($columns as $column => $override) {
                    $this->assertNotEmpty($override);
                    $this->assertSame([], array_diff(array_keys($override), ['value', 'aliases']));

                    $values = $override['aliases'] ?? [];
                    $this->assertIsArray($values);

                    if (isset($override['value'])) {
                        $values[] = $override['value'];
                    }

                    foreach ($values as $value) {
                        if (ApiMapperService::COLUMN_ID === $column) {
                            $this->assertIsInt($value);
                        } else {
                            $this->assertIsString($value);
                            $this->assertNotSame('', trim($value));
                        }
                    }

                    if (! array_key_exists('value', $override)) {
                        $this->assertNotEmpty($values);
                    }
                }
            }
        }
    }

    /**
     * Duplicate values would make one input refer to more than one row.
     */
    public function testEveryColumnHasUniqueValues(): void
    {
        $columns = [
            ApiMapperService::COLUMN_LEGACY_NAME,
            ApiMapperService::COLUMN_ID,
            ApiMapperService::COLUMN_V2_NAME,
        ];

        foreach (array_keys(ApiMapperService::profiles()) as $domain) {
            foreach ($columns as $column) {
                $values = array_filter(
                    array_column($this->rowsFor($domain), $column),
                    static function ($value): bool {
                        return null !== $value;
                    }
                );

                $this->assertSame(
                    array_values(array_unique($values, SORT_REGULAR)),
                    array_values($values),
                    sprintf('Duplicate values in %s.%s.', $domain, $column)
                );
            }
        }
    }

    /**
     * A value present in only one source can indicate different case names across versions.
     * Review such changes before accepting them as an incomplete mapping.
     */
    public function testNoConceptIsNamedByOnlyOneSource(): void
    {
        foreach (array_keys(ApiMapperService::profiles()) as $domain) {
            foreach ($this->rowsFor($domain) as $caseName => $row) {
                $populated = array_filter(
                    $row,
                    static function ($value): bool {
                        return null !== $value;
                    }
                );

                $this->assertGreaterThan(
                    1,
                    count($populated),
                    sprintf(
                        '%s.%s is named by only one of its three source enums (%s). Usually this '
                        . 'means two enums spell the same concept differently, so it compiled into '
                        . 'two half rows instead of one. Check for a near-identical case name '
                        . 'in the same domain; if the spelling really did change, add an override.',
                        $domain,
                        $caseName,
                        implode(', ', array_keys($populated))
                    )
                );
            }
        }
    }

    public function testAliasesAreNonEmptyUniqueAndDoNotCollideWithCanonicalValues(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            $seen = [];

            foreach ($overrides as $caseName => $columnOverrides) {
                foreach ($columnOverrides as $column => $override) {
                    $canonical = array_column($this->rowsFor($domain), $column);

                    foreach ($override['aliases'] ?? [] as $alias) {
                        if (is_string($alias)) {
                            $this->assertNotSame(
                                '',
                                trim($alias),
                                sprintf('Alias for %s.%s.%s is empty.', $domain, $caseName, $column)
                            );
                        }

                        $typedAlias = gettype($alias) . ':' . $alias;

                        $this->assertArrayNotHasKey(
                            $typedAlias,
                            $seen[$column] ?? [],
                            sprintf("Alias '%s' occurs more than once in %s.%s.", $alias, $domain, $column)
                        );
                        $this->assertNotContains(
                            $alias,
                            $canonical,
                            sprintf(
                                "Alias '%s' (%s.%s.%s) is also a canonical value, so input for one "
                                . 'concept would resolve to another.',
                                $alias,
                                $domain,
                                $caseName,
                                $column
                            )
                        );

                        $seen[$column][$typedAlias] = $caseName;
                    }
                }
            }
        }
    }

    /**
     * The public API requires integer ids and non-empty string names.
     */
    public function testSourceEnumsExposeTheExpectedValueTypes(): void
    {
        foreach (ApiMapperService::profiles() as $domain => $profile) {
            foreach ($profile as $column => $enumClass) {
                foreach (self::cases($enumClass) as $name => $value) {
                    $message = sprintf('%s::%s has an invalid type for %s.%s.', $enumClass, $name, $domain, $column);

                    if (ApiMapperService::COLUMN_ID === $column) {
                        $this->assertIsInt($value, $message);
                    } else {
                        $this->assertIsString($value, $message);
                        $this->assertNotSame('', $value, $message);
                    }
                }
            }
        }
    }

    /**
     * @param  class-string<\BackedEnum> $enumClass
     *
     * @return array<string, int|string> Case name => value.
     */
    private static function cases(string $enumClass): array
    {
        return array_column($enumClass::cases(), 'value', 'name');
    }

    /**
     * @return array<string, array<string, int|string|null>>
     */
    private function rowsFor(string $domain): array
    {
        return ApiMapperService::allDomains()[$domain];
    }
}
