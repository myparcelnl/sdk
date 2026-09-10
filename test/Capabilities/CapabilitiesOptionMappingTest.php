<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Capabilities;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\CapabilitiesOptionsV2;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesMapper;
use MyParcelNL\Sdk\Model\Capabilities\CapabilitiesRequest;
use MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper;
use MyParcelNL\Sdk\Test\Bootstrap\TestCase;

/**
 * Verifies that all generated v1 options are mapped or explicitly unsupported in v2 requests.
 *
 * @see \MyParcelNL\Sdk\Services\Mapping\ShipmentOptionMapper
 */
class CapabilitiesOptionMappingTest extends TestCase
{
    /**
     * The completeness guard. Add an option to the v1 spec and this fails until someone has
     * decided what it maps to.
     */
    public function testEveryOptionInTheSpecIsAccountedFor(): void
    {
        $unaccounted = [];
        $mustMap     = array_diff($this->specOptionKeys(), ShipmentOptionMapper::unmappableOptions());

        foreach ($mustMap as $key) {
            $targetProperty = $this->resolvedTargetProperty($key);

            if (null === $targetProperty) {
                $unaccounted[$key] = ShipmentOptionMapper::aliases()[$key] ?? $key;
            }
        }

        $this->assertSame(
            [],
            $unaccounted,
            "These concrete v1 request options resolve to no property on CapabilitiesOptionsV2, so "
            . "mapOptions() would skip them. For each: add an alias to LEGACY_TO_V2_PROPERTIES if v2 "
            . "renamed the concept, or add it to UNSUPPORTED_OPTIONS with a request-specific reason. "
            . "Source key => attempted target property: "
            . json_encode($unaccounted)
        );
    }

    public function testEveryMappableV1OptionPassesThroughTheRealRequestMapper(): void
    {
        $input = [];

        foreach (array_diff($this->specOptionKeys(), ShipmentOptionMapper::unmappableOptions()) as $key) {
            $input[$key] = (object) ['source' => $key];
        }

        $request = CapabilitiesRequest::forCountry('NL')->withOptions($input);
        $options = (new CapabilitiesMapper())->mapToCoreApi($request)->getOptions();

        $this->assertNotNull($options);

        foreach ($input as $source => $marker) {
            $target = $this->resolvedTargetProperty($source);
            $this->assertNotNull($target, $source);
            $getter = CapabilitiesOptionsV2::getters()[$target];

            $this->assertSame($marker, $options->{$getter}(), sprintf('%s must map to %s.', $source, $target));
        }
    }

    public function testEveryGeneratedV2JsonNameRemainsAcceptedAsInput(): void
    {
        $input   = [];
        $markers = [];

        foreach (CapabilitiesOptionsV2::attributeMap() as $property => $jsonName) {
            $input[$jsonName] = (object) ['source' => $jsonName];
            $markers[$property] = $input[$jsonName];
        }

        $request = CapabilitiesRequest::forCountry('NL')->withOptions($input);
        $options = (new CapabilitiesMapper())->mapToCoreApi($request)->getOptions();

        $this->assertNotNull($options);

        foreach ($markers as $property => $marker) {
            $getter = CapabilitiesOptionsV2::getters()[$property];

            $this->assertSame($marker, $options->{$getter}(), sprintf('%s JSON input must remain accepted.', $property));
        }
    }

    /**
     * Alias sources and targets are both generated schema properties. This catches spelling
     * mistakes as well as stale aliases after either side of the spec changes.
     */
    public function testEveryAliasHasARealSourceAndTargetProperty(): void
    {
        $sourceKeys    = $this->specOptionKeys();
        $targetSetters = CapabilitiesOptionsV2::setters();

        foreach (ShipmentOptionMapper::aliases() as $source => $target) {
            $this->assertContains(
                $source,
                $sourceKeys,
                sprintf('Alias source %s is not on the concrete v1 capabilities request.', $source)
            );
            $this->assertArrayHasKey(
                $target,
                $targetSetters,
                sprintf('Alias target %s for %s is not on the concrete v2 capabilities request.', $target, $source)
            );
            $this->assertNotContains(
                $source,
                ShipmentOptionMapper::unmappableOptions(),
                sprintf('%s is both aliased and declared unmappable.', $source)
            );
        }
    }

    /**
     * Keeps the hand-written list minimal: if v2 ever renames a concept back to something the
     * naming rule derives, the alias becomes dead weight and should go.
     */
    public function testNoAliasDuplicatesWhatTheNamingRuleAlreadyDerives(): void
    {
        foreach (ShipmentOptionMapper::aliases() as $source => $target) {
            $this->assertNotSame(
                $source,
                $target,
                sprintf('The alias for %s is redundant — v2 already uses the same property name.', $source)
            );
            $this->assertArrayNotHasKey(
                $source,
                CapabilitiesOptionsV2::setters(),
                sprintf('V2 now exposes %s directly. Review whether its alias to %s is still needed.', $source, $target)
            );
        }
    }

    public function testUnmappableOptionsAreRealSpecOptionsUnsupportedByTheConcreteV2Request(): void
    {
        $targetSetters = CapabilitiesOptionsV2::setters();

        foreach (ShipmentOptionMapper::unmappableOptions() as $key) {
            $this->assertContains(
                $key,
                $this->specOptionKeys(),
                sprintf('%s is declared unmappable but is not a v1 option at all.', $key)
            );

            $this->assertArrayNotHasKey(
                $key,
                $targetSetters,
                sprintf('%s is declared unmappable, but the concrete v2 request now supports it.', $key)
            );

            $this->assertArrayNotHasKey(
                $key,
                ShipmentOptionMapper::aliases(),
                sprintf('%s is both aliased and declared unmappable.', $key)
            );
        }
    }

    /**
     * The three aliases that were missing before INT-1441, pinned so they cannot regress.
     */
    public function testPreviouslyDroppedOptionsResolveToTheExpectedTargetProperties(): void
    {
        $expected = [
            'cash_on_delivery'         => 'requires_cash_on_delivery',
            'drop_off_at_postal_point' => 'deliver_at_postal_point',
            'extra_assurance'          => 'additional_insurance',
        ];

        $this->assertSame($expected, array_intersect_key(ShipmentOptionMapper::aliases(), $expected));
    }

    /**
     * Every spec option falls into exactly one of three buckets, and the buckets account for all
     * of them. Restates the completeness guard as arithmetic, and shows the reader how much of
     * the mapping is automatic rather than hand-written.
     */
    public function testEveryOptionFallsIntoExactlyOneBucket(): void
    {
        $automatic = [];
        $aliased   = [];
        $targets   = [];

        foreach (array_diff($this->specOptionKeys(), ShipmentOptionMapper::unmappableOptions()) as $key) {
            $target = $this->resolvedTargetProperty($key);
            $this->assertNotNull($target, $key);
            $this->assertNotContains(
                $target,
                $targets,
                sprintf('More than one v1 option resolves to v2 property %s.', $target)
            );
            $targets[] = $target;

            if (array_key_exists($key, ShipmentOptionMapper::aliases())) {
                $aliased[] = $key;
                continue;
            }

            $automatic[] = $key;
        }

        $this->assertSame(
            count($this->specOptionKeys()),
            count($automatic) + count($aliased) + count(ShipmentOptionMapper::unmappableOptions())
        );

        // At least one mapping must remain derived; insurance proves the concrete request model,
        // rather than its 19-key shared base model, is the source of this completeness check.
        $this->assertGreaterThan(0, count($automatic));
        $this->assertSame([], array_intersect($automatic, $aliased));
        $this->assertContains('insurance', $automatic, 'The concrete 20-key v1 request must guard insurance too.');
    }

    /**
     * The generated v2 property CapabilitiesMapper would actually target, or null if it would be
     * skipped.
     */
    private function resolvedTargetProperty(string $key): ?string
    {
        return (new ShipmentOptionMapper())->v2PropertyFromName($key);
    }

    /**
     * Every option name the v1 spec defines, unfiltered.
     *
     * @return string[]
     */
    private function specOptionKeys(): array
    {
        return array_values(CapabilitiesPostCapabilitiesRequestV1DataCapabilitiesInnerOptions::attributeMap());
    }
}
