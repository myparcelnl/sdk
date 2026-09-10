<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Services\Mapping;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesOptionsV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsPackageTypeV2;
use MyParcelNL\Sdk\Services\Mapping\ApiMapperService;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;
use ReflectionClass;

/**
 * Detects orphaned source models, redundant overrides and ambiguous mappings after regeneration.
 */
class SpecDriftTest extends TestCase
{
    private const REPO_ROOT = __DIR__ . '/../../..';

    /**
     * Scoped deliberately to the nine classes the profiles name. A repo-wide sweep would flag
     * the hand-written typeMappings overrides (FixedShipmentRecipient, FixedPrincipal) that
     * legitimately live outside the manifest.
     */
    public function testEverySourceEnumIsStillProducedByTheGenerator(): void
    {
        foreach (ApiMapperService::profiles() as $domain => $profile) {
            foreach ($profile as $column => $enumClass) {
                $this->assertTrue(
                    $this->isInGeneratorManifest($enumClass),
                    sprintf(
                        "%s (%s.%s) is not listed in its client's .openapi-generator/FILES manifest, "
                        . 'so the generator no longer produces it. It is an orphan: the file may still '
                        . 'be on disk and class_exists() may still return true, but its values are frozen. '
                        . 'Point this profile at the enum that replaced it.',
                        $enumClass,
                        $domain,
                        $column
                    )
                );
            }
        }
    }

    public function testOptionModelsAreStillProducedByTheGenerator(): void
    {
        foreach ([CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions::class, CapabilitiesOptionsV2::class] as $modelClass) {
            $this->assertTrue(
                $this->isInGeneratorManifest($modelClass),
                sprintf('%s is no longer generated. Review the shipment-option mapping sources.', $modelClass)
            );
        }
    }

    /**
     * The spec values the package-type overrides displace, recorded exactly.
     *
     * If either changes, review before touching the override. The two entries have different
     * causes: SMALL_PACKAGE is spec lag and retires when core-api ships the fix; UNFRANKED is a
     * domain-name choice with no working delivery-options counterpart and probably does not.
     */
    public function testDisplacedPackageTypeSpecValuesAreUnchanged(): void
    {
        $this->assertSame('unfranked', ShipmentResponsesDeliveryOptionsPackageTypeV2::UNFRANKED);
        $this->assertSame('small_package', ShipmentResponsesDeliveryOptionsPackageTypeV2::SMALL_PACKAGE);
    }

    public function testPickupIsStillAbsentFromTheDeliveryOptionsEnum(): void
    {
        $constants = (new ReflectionClass(ShipmentDefsDeliveryOptionsDeliveryNameV2::class))->getConstants();

        $this->assertArrayNotHasKey(
            'PICKUP',
            $constants,
            'The delivery-options enum now names PICKUP. Drop the PICKUP override so the value '
            . 'comes from the generated enum instead.'
        );
    }

    /**
     * The self-retiring part: an override that matches what the generator already supplies is
     * dead weight and must be deleted.
     */
    public function testNoOverrideDuplicatesWhatTheGeneratedEnumAlreadySupplies(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            foreach ($overrides as $constantName => $columnOverrides) {
                foreach ($columnOverrides as $column => $override) {
                    if (! array_key_exists('value', $override)) {
                        // Alias-only entries intentionally survive after the generated canonical
                        // value catches up; only their backwards-compatibility alias remains.
                        continue;
                    }

                    $enumClass = ApiMapperService::profiles()[$domain][$column];
                    $generated = (new ReflectionClass($enumClass))->getConstants();

                    if (! array_key_exists($constantName, $generated)) {
                        continue;
                    }

                    $this->assertNotSame(
                        $generated[$constantName],
                        $override['value'],
                        sprintf(
                            'The %s.%s.%s override now equals %s::%s. Delete its value — the '
                            . 'generated enum supplies it on its own. Preserve aliases that are '
                            . 'still part of the backwards-compatibility contract.',
                            $domain,
                            $constantName,
                            $column,
                            $enumClass,
                            $constantName
                        )
                    );
                }
            }
        }
    }

    /**
     * Check exception keys against source constants so misspellings cannot create extra rows.
     */
    public function testEveryOverrideStillPointsAtAKnownConstant(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            $known = [];

            foreach (ApiMapperService::profiles()[$domain] as $enumClass) {
                $known += (new ReflectionClass($enumClass))->getConstants();
            }

            foreach (array_keys($overrides) as $constantName) {
                $this->assertArrayHasKey(
                    $constantName,
                    $known,
                    sprintf(
                        'The %s override for %s matches no constant in any of that profile\'s source '
                        . 'enums. Either it is a typo, or the concept left the spec.',
                        $domain,
                        $constantName
                    )
                );
            }
        }
    }

    public function testEveryOverrideTargetsAKnownColumn(): void
    {
        foreach (ApiMapperService::overrides() as $domain => $overrides) {
            $knownColumns = array_keys(ApiMapperService::profiles()[$domain]);

            foreach ($overrides as $constantName => $columnOverrides) {
                foreach (array_keys($columnOverrides) as $column) {
                    $this->assertContains(
                        $column,
                        $knownColumns,
                        sprintf('The %s.%s override targets unknown column %s.', $domain, $constantName, $column)
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
     * A value present in only one source can indicate different constant names across versions.
     * Review such changes before accepting them as an incomplete mapping.
     */
    public function testNoConceptIsNamedByOnlyOneSource(): void
    {
        foreach (array_keys(ApiMapperService::profiles()) as $domain) {
            foreach ($this->rowsFor($domain) as $constantName => $row) {
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
                        . 'two half rows instead of one. Check for a near-identical constant name '
                        . 'in the same domain; if the spelling really did change, add an override.',
                        $domain,
                        $constantName,
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

            foreach ($overrides as $constantName => $columnOverrides) {
                foreach ($columnOverrides as $column => $override) {
                    $canonical = array_column($this->rowsFor($domain), $column);

                    foreach ($override['aliases'] ?? [] as $alias) {
                        if (is_string($alias)) {
                            $this->assertNotSame(
                                '',
                                trim($alias),
                                sprintf('Alias for %s.%s.%s is empty.', $domain, $constantName, $column)
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
                                $constantName,
                                $column
                            )
                        );

                        $seen[$column][$typedAlias] = $constantName;
                    }
                }
            }
        }
    }

    /**
     * The public API requires integer ids and non-empty string names. Generator metadata
     * constants must not become mapping rows.
     */
    public function testSourceEnumsExposeTheExpectedValueTypes(): void
    {
        foreach (ApiMapperService::profiles() as $domain => $profile) {
            foreach ($profile as $column => $enumClass) {
                foreach ((new ReflectionClass($enumClass))->getConstants() as $name => $value) {
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
     * @return array<string, array<string, int|string|null>>
     */
    private function rowsFor(string $domain): array
    {
        return ApiMapperService::allDomains()[$domain];
    }

    /**
     * @param  class-string $enumClass
     */
    private function isInGeneratorManifest(string $enumClass): bool
    {
        $parts     = explode('\\', $enumClass);
        $shortName = array_pop($parts);
        $group     = array_pop($parts);          // Model
        $client    = array_pop($parts);          // CoreApi, OrderApi, ...

        $manifest = sprintf('%s/src/Client/Generated/%s/.openapi-generator/FILES', self::REPO_ROOT, $client);

        $this->assertFileExists($manifest);

        $entries = file($manifest, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        return in_array(sprintf('%s/%s.php', $group, $shortName), $entries, true);
    }
}
