<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Mapping;

use LogicException;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefCapabilitiesSharedCarrierV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefShipmentPackageTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesCarrier;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryType;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefTypesDeliveryTypeV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentDefsDeliveryOptionsDeliveryNameV2;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentParametersCarrierName;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\ShipmentResponsesDeliveryOptionsPackageTypeV2;
use ReflectionClass;

/**
 * Maps carriers, delivery types and package types between delivery-options names, v1 ids and v2 names.
 *
 * Generated constants are joined by name. Explicit overrides take precedence; name-to-name
 * lookups may also use case conversion when the result exists in the target column.
 * Unknown values return null. Invalid configuration or missing source classes fail at construction.
 */
final class ApiMapperService
{
    public const COLUMN_LEGACY_NAME = 'legacy_name';
    public const COLUMN_ID          = 'id';
    public const COLUMN_V2_NAME     = 'v2_name';

    public const DOMAIN_CARRIER       = 'carrier';
    public const DOMAIN_DELIVERY_TYPE = 'delivery_type';
    public const DOMAIN_PACKAGE_TYPE  = 'package_type';

    /**
     * Generated enum classes supplying each column, keyed by domain.
     *
     * @var array<string, array<string, class-string>>
     */
    private const PROFILES = [
        self::DOMAIN_CARRIER       => [
            self::COLUMN_LEGACY_NAME => ShipmentParametersCarrierName::class,
            self::COLUMN_ID          => RefTypesCarrier::class,
            self::COLUMN_V2_NAME     => RefCapabilitiesSharedCarrierV2::class,
        ],
        self::DOMAIN_DELIVERY_TYPE => [
            self::COLUMN_LEGACY_NAME => ShipmentDefsDeliveryOptionsDeliveryNameV2::class,
            self::COLUMN_ID          => RefTypesDeliveryType::class,
            self::COLUMN_V2_NAME     => RefTypesDeliveryTypeV2::class,
        ],
        self::DOMAIN_PACKAGE_TYPE  => [
            self::COLUMN_LEGACY_NAME => ShipmentResponsesDeliveryOptionsPackageTypeV2::class,
            self::COLUMN_ID          => RefShipmentPackageType::class,
            self::COLUMN_V2_NAME     => RefShipmentPackageTypeV2::class,
        ],
    ];

    /**
     * Exceptions to shared-constant matching, keyed by domain, constant name and column.
     *
     * A value replaces the generated cell; null blocks its mapping. Aliases are input-only.
     * Alias-only entries preserve older spellings after the generated canonical value changes.
     *
     * SMALL_PACKAGE uses the delivery-options spelling package_small until the spec catches up.
     * UNFRANKED uses the domain name letter; review that convention separately from spec changes.
     *
     * @var array<string, array<string, array<string, array{
     *     value?: int|string|null,
     *     aliases?: array<int, int|string>
     * }>>>
     */
    private const OVERRIDES = [
        self::DOMAIN_CARRIER       => [],
        self::DOMAIN_DELIVERY_TYPE => [
            // Not present in ShipmentDefsDeliveryOptionsDeliveryNameV2 at all.
            'PICKUP' => [
                self::COLUMN_LEGACY_NAME => ['value' => 'pickup'],
            ],
        ],
        self::DOMAIN_PACKAGE_TYPE  => [
            'UNFRANKED'     => [
                self::COLUMN_LEGACY_NAME => [
                    'value'   => 'letter',
                    'aliases' => ['unfranked'],
                ],
            ],
            'SMALL_PACKAGE' => [
                self::COLUMN_LEGACY_NAME => [
                    'value'   => 'package_small',
                    'aliases' => ['small_package'],
                ],
            ],
        ],
    ];

    private const EMPTY_ROW = [
        self::COLUMN_LEGACY_NAME => null,
        self::COLUMN_ID          => null,
        self::COLUMN_V2_NAME     => null,
    ];

    /**
     * @var array<string, self>
     */
    private static array $instances = [];

    /**
     * @var array<class-string, array<string, int|string>>
     */
    private static array $constantCache = [];

    private string $domain;

    /**
     * @var array<string, array<string, int|string|null>>
     */
    private array $rows;

    /**
     * Input aliases indexed by column and scalar type, so integer 1 and string '1' remain distinct.
     *
     * @var array<string, array<string, string>>
     */
    private array $aliases;

    /**
     * Cells assigned by an override, keyed by constant name and column.
     *
     * An overridden source selects a concept. A null target override blocks conversion.
     * Neither may select another concept through name conversion.
     *
     * @var array<string, array<string, bool>>
     */
    private array $overriddenCells;

    /**
     * @var array<string, array<string, int|string>>
     */
    private array $columns = [];

    /**
     * @param  string $domain
     *
     * @throws \LogicException
     * @throws \ReflectionException
     */
    private function __construct(string $domain)
    {
        self::validateOverrides($domain, self::PROFILES[$domain], self::OVERRIDES[$domain]);

        $this->domain          = $domain;
        $this->rows            = self::compile(self::PROFILES[$domain], self::OVERRIDES[$domain]);
        $this->aliases         = self::compileAliases($domain, $this->rows, self::OVERRIDES[$domain]);
        $this->overriddenCells = self::compileOverriddenCells(self::OVERRIDES[$domain]);
    }

    /**
     * Return the carrier mapper.
     *
     * @return self
     */
    public static function forCarrier(): self
    {
        return self::forDomain(self::DOMAIN_CARRIER);
    }

    /**
     * Return the delivery type mapper.
     *
     * @return self
     */
    public static function forDeliveryType(): self
    {
        return self::forDomain(self::DOMAIN_DELIVERY_TYPE);
    }

    /**
     * Return the package type mapper.
     *
     * @return self
     */
    public static function forPackageType(): self
    {
        return self::forDomain(self::DOMAIN_PACKAGE_TYPE);
    }

    /**
     * Map a v1 id to its delivery-options name.
     *
     * @param  int $id
     *
     * @return null|string
     */
    public function legacyNameFromId(int $id): ?string
    {
        return $this->resolve($id, self::COLUMN_ID, self::COLUMN_LEGACY_NAME);
    }

    /**
     * Map a v1 id to its capabilities v2 name.
     *
     * @param  int $id
     *
     * @return null|string
     */
    public function v2NameFromId(int $id): ?string
    {
        return $this->resolve($id, self::COLUMN_ID, self::COLUMN_V2_NAME);
    }

    /**
     * Map a delivery-options name to its v1 id.
     *
     * @param  string $name
     *
     * @return null|int
     */
    public function idFromLegacyName(string $name): ?int
    {
        return $this->resolve($name, self::COLUMN_LEGACY_NAME, self::COLUMN_ID);
    }

    /**
     * Map a delivery-options name to its capabilities v2 name.
     *
     * @param  string $name
     *
     * @return null|string
     */
    public function v2NameFromLegacyName(string $name): ?string
    {
        return $this->resolve($name, self::COLUMN_LEGACY_NAME, self::COLUMN_V2_NAME);
    }

    /**
     * Map a capabilities v2 name to its v1 id.
     *
     * @param  string $name
     *
     * @return null|int
     */
    public function idFromV2Name(string $name): ?int
    {
        return $this->resolve($name, self::COLUMN_V2_NAME, self::COLUMN_ID);
    }

    /**
     * Map a capabilities v2 name to its delivery-options name.
     *
     * @param  string $name
     *
     * @return null|string
     */
    public function legacyNameFromV2Name(string $name): ?string
    {
        return $this->resolve($name, self::COLUMN_V2_NAME, self::COLUMN_LEGACY_NAME);
    }

    /**
     * Return the three-way table keyed by constant name, with null for missing cells.
     *
     * Keys identify concepts, not API values: PICKUP has the v2 value PICKUP_DELIVERY.
     * Input aliases are excluded. Rows are sorted by constant name.
     *
     * @return array<string, array<string, int|string|null>>
     */
    public function allRows(): array
    {
        return $this->rows;
    }

    /**
     * Return v2 names and v1 ids in v1 enum declaration order.
     *
     * Rows missing either value are excluded to preserve the compatibility adapters' map shape.
     *
     * @return array<string, int>
     */
    public function v2ToIdMap(): array
    {
        $map     = [];
        $visited = [];

        // Preserve the order exposed by the compatibility adapters.
        foreach (array_keys(self::constants(self::PROFILES[$this->domain][self::COLUMN_ID])) as $constantName) {
            $visited[$constantName] = true;
            $this->appendCompleteRow($map, $constantName);
        }

        // Append rows that have no position in the v1 declaration.
        foreach (array_keys($this->rows) as $constantName) {
            if (! isset($visited[$constantName])) {
                $this->appendCompleteRow($map, $constantName);
            }
        }

        return $map;
    }

    /**
     * Return the three-way tables for all supported domains.
     *
     * @return array<string, array<string, array<string, int|string|null>>>
     */
    public static function allDomains(): array
    {
        return [
            self::DOMAIN_CARRIER       => self::forCarrier()->allRows(),
            self::DOMAIN_DELIVERY_TYPE => self::forDeliveryType()->allRows(),
            self::DOMAIN_PACKAGE_TYPE  => self::forPackageType()->allRows(),
        ];
    }

    /**
     * @internal Used to verify that source classes remain in the generator manifest.
     *
     * @return array<string, array<string, class-string>>
     */
    public static function profiles(): array
    {
        return self::PROFILES;
    }

    /**
     * @internal Used to verify that mapping exceptions remain necessary.
     *
     * @return array<string, array<string, array<string, array{
     *     value?: int|string|null,
     *     aliases?: array<int, int|string>
     * }>>>
     */
    public static function overrides(): array
    {
        return self::OVERRIDES;
    }

    /**
     * Resolve aliases and overrides, shared constants, then a verified case conversion.
     *
     * @param  int|string $value
     * @param  string     $from  Source COLUMN_* value.
     * @param  string     $to    Target COLUMN_* value.
     *
     * @return int|string|null
     */
    private function resolve($value, string $from, string $to)
    {
        $constantName = $this->matchAlias($value, $from);

        // An alias selects one concept even when its target is missing.
        if (null !== $constantName) {
            return $this->rows[$constantName][$to];
        }

        $constantName = $this->matchExact($value, $from);

        if (null !== $constantName) {
            $target = $this->rows[$constantName][$to];

            if (null !== $target) {
                return $target;
            }

            // Only an ordinary missing cell may fall through to name conversion.
            if ($this->isOverridden($constantName, $from) || $this->isOverridden($constantName, $to)) {
                return null;
            }
        }

        return $this->matchNormalised($value, $from, $to);
    }

    /**
     * Reject invalid columns, constants and scalar types in internal overrides.
     *
     * @param  string                      $domain
     * @param  array<string, class-string> $profile
     * @param  array<string, mixed>        $overrides
     *
     * @return void
     * @throws \LogicException
     */
    private static function validateOverrides(string $domain, array $profile, array $overrides): void
    {
        $knownConstants = [];

        foreach ($profile as $enumClass) {
            $knownConstants += array_fill_keys(array_keys(self::constants($enumClass)), true);
        }

        foreach ($overrides as $constantName => $columnOverrides) {
            if (! isset($knownConstants[$constantName])) {
                throw new LogicException(sprintf('Unknown constant %s in %s overrides.', $constantName, $domain));
            }

            if (! is_array($columnOverrides)) {
                throw new LogicException(sprintf('Invalid override columns for %s.%s.', $domain, $constantName));
            }

            foreach ($columnOverrides as $column => $override) {
                if (! array_key_exists($column, $profile) || ! is_array($override)) {
                    throw new LogicException(sprintf('Invalid override for %s.%s.%s.', $domain, $constantName, $column));
                }

                $unknownKeys = array_diff(array_keys($override), ['value', 'aliases']);

                if ([] !== $unknownKeys || [] === $override) {
                    throw new LogicException(sprintf('Unknown or empty override option for %s.%s.%s.', $domain, $constantName, $column));
                }

                if (
                    array_key_exists('value', $override)
                    && ! self::isValueValidForColumn($override['value'], $column, true)
                ) {
                    throw new LogicException(sprintf('Invalid override value for %s.%s.%s.', $domain, $constantName, $column));
                }

                if (array_key_exists('aliases', $override) && ! is_array($override['aliases'])) {
                    throw new LogicException(sprintf('Invalid aliases for %s.%s.%s.', $domain, $constantName, $column));
                }

                $aliases = $override['aliases'] ?? [];

                if (! array_key_exists('value', $override) && [] === $aliases) {
                    throw new LogicException(sprintf('Empty alias-only override for %s.%s.%s.', $domain, $constantName, $column));
                }

                foreach ($aliases as $alias) {
                    if (! self::isValueValidForColumn($alias, $column, false)) {
                        throw new LogicException(sprintf('Invalid alias value for %s.%s.%s.', $domain, $constantName, $column));
                    }
                }
            }
        }
    }

    /**
     * @param  mixed  $value
     * @param  string $column
     * @param  bool   $allowNull
     *
     * @return bool
     */
    private static function isValueValidForColumn($value, string $column, bool $allowNull): bool
    {
        if (null === $value) {
            return $allowNull;
        }

        return self::COLUMN_ID === $column ? is_int($value) : is_string($value);
    }

    /**
     * Find an input alias in its source column.
     *
     * @param  int|string $value
     * @param  string     $from
     *
     * @return null|string
     */
    private function matchAlias($value, string $from): ?string
    {
        return $this->aliases[$from][self::valueKey($value)] ?? null;
    }

    /**
     * Find the shared constant name using a strict comparison.
     *
     * @param  int|string $value
     * @param  string     $from
     *
     * @return null|string
     */
    private function matchExact($value, string $from): ?string
    {
        $constantName = array_search($value, $this->column($from), true);

        return false === $constantName ? null : $constantName;
    }

    /**
     * Convert names only when neither column contains ids and the target value exists.
     *
     * Carrier legacy names use compact lowercase; other legacy names use snake_case.
     * V2 names use SCREAMING_SNAKE_CASE.
     *
     * @param  int|string $value
     * @param  string     $from
     * @param  string     $to
     *
     * @return null|string
     */
    private function matchNormalised($value, string $from, string $to): ?string
    {
        if (self::COLUMN_ID === $from || self::COLUMN_ID === $to) {
            return null;
        }

        $snake = self::snakeCase((string) $value);

        if (self::COLUMN_V2_NAME === $to) {
            $candidate = strtoupper($snake);
        } elseif (self::DOMAIN_CARRIER === $this->domain) {
            $candidate = str_replace('_', '', $snake);
        } else {
            $candidate = $snake;
        }

        return in_array($candidate, $this->column($to), true) ? $candidate : null;
    }

    /**
     * @param  string $domain
     *
     * @return self
     */
    private static function forDomain(string $domain): self
    {
        if (! isset(self::$instances[$domain])) {
            self::$instances[$domain] = new self($domain);
        }

        return self::$instances[$domain];
    }

    /**
     * Join the union of source constant names and apply overrides.
     *
     * Using all sources preserves concepts that are not yet present in every API version.
     *
     * @param  array<string, class-string> $profile
     * @param  array<string, mixed>        $overrides
     *
     * @return array<string, array<string, int|string|null>>
     */
    private static function compile(array $profile, array $overrides): array
    {
        $rows = [];

        foreach ($profile as $enumClass) {
            $rows += array_fill_keys(array_keys(self::constants($enumClass)), self::EMPTY_ROW);
        }

        foreach ($profile as $column => $enumClass) {
            foreach (self::constants($enumClass) as $constantName => $value) {
                $rows[$constantName][$column] = $value;
            }
        }

        // array_key_exists rather than isset, so an explicit null blocks the automatic match.
        foreach ($overrides as $constantName => $columnOverrides) {
            $rows[$constantName] = $rows[$constantName] ?? self::EMPTY_ROW;

            foreach ($columnOverrides as $column => $override) {
                if (array_key_exists('value', $override)) {
                    $rows[$constantName][$column] = $override['value'];
                }
            }
        }

        ksort($rows);

        return $rows;
    }

    /**
     * Build the alias index, rejecting duplicates and collisions with canonical values.
     *
     * @param  string                                        $domain
     * @param  array<string, array<string, int|string|null>> $rows
     * @param  array<string, mixed>                          $overrides
     *
     * @return array<string, array<string, string>>
     * @throws \LogicException
     */
    private static function compileAliases(string $domain, array $rows, array $overrides): array
    {
        $aliases = [];

        foreach ($overrides as $constantName => $columnOverrides) {
            foreach ($columnOverrides as $column => $override) {
                foreach ($override['aliases'] ?? [] as $alias) {
                    if (is_string($alias) && '' === trim($alias)) {
                        throw new LogicException(sprintf(
                            'Empty alias configured for %s.%s.%s.',
                            $domain,
                            $constantName,
                            $column
                        ));
                    }

                    $key = self::valueKey($alias);

                    if (array_key_exists($key, $aliases[$column] ?? [])) {
                        throw new LogicException(sprintf(
                            "Duplicate alias '%s' configured for %s.%s.",
                            (string) $alias,
                            $domain,
                            $column
                        ));
                    }

                    foreach ($rows as $canonicalName => $row) {
                        if ($row[$column] === $alias) {
                            throw new LogicException(sprintf(
                                "Alias '%s' for %s.%s.%s is already the canonical value of %s.",
                                (string) $alias,
                                $domain,
                                $constantName,
                                $column,
                                $canonicalName
                            ));
                        }
                    }

                    $aliases[$column][$key] = $constantName;
                }
            }
        }

        return $aliases;
    }

    /**
     * @param  array<string, mixed> $overrides
     *
     * @return array<string, array<string, bool>>
     */
    private static function compileOverriddenCells(array $overrides): array
    {
        $cells = [];

        foreach ($overrides as $constantName => $columnOverrides) {
            foreach ($columnOverrides as $column => $override) {
                if (array_key_exists('value', $override)) {
                    $cells[$constantName][$column] = true;
                }
            }
        }

        return $cells;
    }

    /**
     * @param  string $constantName
     * @param  string $column
     *
     * @return bool
     */
    private function isOverridden(string $constantName, string $column): bool
    {
        return $this->overriddenCells[$constantName][$column] ?? false;
    }

    /**
     * @param  array<string, int> $map
     * @param  string             $constantName
     *
     * @return void
     */
    private function appendCompleteRow(array &$map, string $constantName): void
    {
        $v2Name = $this->rows[$constantName][self::COLUMN_V2_NAME];
        $id     = $this->rows[$constantName][self::COLUMN_ID];

        if (null !== $v2Name && null !== $id) {
            $map[$v2Name] = $id;
        }
    }

    /**
     * Convert camelCase, PascalCase, acronyms and separated words to lowercase snake_case.
     *
     * @param  string $value
     *
     * @return string
     */
    private static function snakeCase(string $value): string
    {
        $value = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1_$2', $value) ?? $value;
        $value = preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $value) ?? $value;
        $value = preg_replace('/[^A-Za-z0-9]+/', '_', $value) ?? $value;

        return strtolower(trim($value, '_'));
    }

    /**
     * @param  int|string $value
     *
     * @return string
     */
    private static function valueKey($value): string
    {
        return (is_int($value) ? 'int:' : 'string:') . $value;
    }

    /**
     * Return populated cells from the compiled column, including overrides.
     *
     * @param  string $column
     *
     * @return array<string, int|string>
     */
    private function column(string $column): array
    {
        if (isset($this->columns[$column])) {
            return $this->columns[$column];
        }

        $values = [];

        foreach ($this->rows as $constantName => $row) {
            if (null !== $row[$column]) {
                $values[$constantName] = $row[$column];
            }
        }

        return $this->columns[$column] = $values;
    }

    /**
     * @param  class-string $enumClass
     *
     * @return array<string, int|string>
     * @throws \ReflectionException
     */
    private static function constants(string $enumClass): array
    {
        if (! isset(self::$constantCache[$enumClass])) {
            self::$constantCache[$enumClass] = (new ReflectionClass($enumClass))->getConstants();
        }

        return self::$constantCache[$enumClass];
    }
}
