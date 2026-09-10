<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Mapping;

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
 * Converts carrier, delivery type and package type values between API versions.
 *
 * Select a mapper, then request the target format:
 *
 *     $mapper = ApiMapperService::forCarrier();
 *     $mapper->idFromLegacyName('dhlforyou'); // 9
 *     $mapper->v2NameFromId(9);              // 'DHL_FOR_YOU'
 *
 * Unknown values return null. Explicit exceptions take priority over matching constant names.
 * Name-to-name conversions may also change case if the result exists in the target definition.
 * Shipment options use model properties and have no IDs; their mapping is maintained alongside
 * this service in ShipmentOptionMapper.
 *
 * @see \MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper
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
     * Each domain is a category of values: carriers, delivery types or package types.
     * Its profile selects the generated classes for legacy names, v1 IDs and v2 names.
     * Constants with the same name in these classes refer to the same value.
     *
     * To add a category, define its profile, optional exceptions and a public factory method.
     * Domain keys also identify categories in allDomains(); keep existing keys stable.
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
     * A value replaces the generated value; null marks it as unsupported.
     * An alias is another accepted spelling of the same input. For example, unfranked is
     * accepted as well as letter, but conversions back to a legacy name always return letter.
     * An entry with only aliases keeps old input spellings working after a definition changes.
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

    private string $domain;

    /**
     * @var array<string, array<string, int|string|null>>
     */
    private array $rows;

    /**
     * @var array<string, array<string, array{value?: int|string|null, aliases?: array<int, int|string>}>>
     */
    private array $mappingOverrides;

    /**
     * @param  string $domain
     *
     * @throws \ReflectionException
     */
    private function __construct(string $domain)
    {
        $this->domain           = $domain;
        $this->mappingOverrides = self::OVERRIDES[$domain] ?? [];
        $this->rows             = self::compile(self::PROFILES[$domain], $this->mappingOverrides);
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
        $rows = $this->rows;
        ksort($rows);

        return $rows;
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
        $map = [];

        foreach ($this->rows as $row) {
            if (null !== $row[self::COLUMN_V2_NAME] && null !== $row[self::COLUMN_ID]) {
                $map[$row[self::COLUMN_V2_NAME]] = $row[self::COLUMN_ID];
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
        $maps = [];

        foreach (array_keys(self::PROFILES) as $domain) {
            $maps[$domain] = self::forDomain($domain)->allRows();
        }

        return $maps;
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
     * @param  int|string $value
     * @param  string     $from
     * @param  string     $to
     *
     * @return int|string|null
     */
    private function resolve($value, string $from, string $to)
    {
        foreach ($this->mappingOverrides as $constantName => $overrides) {
            $source = $overrides[$from] ?? [];

            if (
                in_array($value, $source['aliases'] ?? [], true)
                || (array_key_exists('value', $source) && $value === $source['value'])
            ) {
                return $this->rows[$constantName][$to];
            }
        }

        foreach ($this->rows as $constantName => $row) {
            if ($value !== $row[$from]) {
                continue;
            }

            // A missing generated target may use name conversion. An explicit null may not.
            if (null !== $row[$to] || array_key_exists('value', $this->mappingOverrides[$constantName][$to] ?? [])) {
                return $row[$to];
            }

            break;
        }

        return $this->matchNormalised($value, $from, $to);
    }

    /**
     * Convert names only when neither column contains IDs and the target value exists.
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

        return in_array($candidate, array_column($this->rows, $to), true) ? $candidate : null;
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
     * Join source constants and apply exceptions. Start with IDs to keep the existing map order.
     *
     * @param  array<string, class-string> $profile
     * @param  array<string, mixed>        $overrides
     *
     * @return array<string, array<string, int|string|null>>
     */
    private static function compile(array $profile, array $overrides): array
    {
        $rows    = [];
        $profile = [self::COLUMN_ID => $profile[self::COLUMN_ID]] + $profile;

        foreach ($profile as $column => $enumClass) {
            foreach ((new ReflectionClass($enumClass))->getConstants() as $constantName => $value) {
                $rows[$constantName]          = $rows[$constantName] ?? self::EMPTY_ROW;
                $rows[$constantName][$column] = $value;
            }
        }

        foreach ($overrides as $constantName => $columns) {
            foreach ($columns as $column => $override) {
                if (array_key_exists('value', $override)) {
                    $rows[$constantName][$column] = $override['value'];
                }
            }
        }

        return $rows;
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
}
