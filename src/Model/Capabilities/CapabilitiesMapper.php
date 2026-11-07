<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model\Capabilities;

use BackedEnum;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\CapabilitiesResponsesCapabilitiesV2 as CoreResponseV2;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefCapabilitiesResponseCapabilityV2 as CoreCapabilityV2;

/**
 * Maps the Jane capabilities response to a CapabilitiesResponse, which merges the results into one
 * list per kind, such as the package types.
 */
class CapabilitiesMapper
{
    /**
     * Map Core API response to the SDK response model.
     *
     * Note: physical properties are treated as input-only for this SDK response.
     */
    public function mapFromCoreApi(CoreResponseV2 $core): CapabilitiesResponse
    {
        $results = $core->results ?? [];

        if (empty($results)) {
            return new CapabilitiesResponse([], [], [], null, [], null);
        }

        $packageTypes = [];
        $deliveryTypes = [];
        $optionKeys = [];
        $transactionTypes = [];
        $carrier = null;
        $carrierInconsistent = false;
        $colloMax = null;

        foreach ($results as $res) {
            $packageTypes = array_values(array_unique(array_merge(
                $packageTypes,
                self::values($res->packageTypes ?? [])
            )));

            $deliveryTypes = array_values(array_unique(array_merge(
                $deliveryTypes,
                self::values($res->deliveryTypes ?? [])
            )));

            $optionKeys = array_values(array_unique(array_merge(
                $optionKeys,
                $this->optionNames($res)
            )));

            if (! $carrierInconsistent) {
                $resCarrier = isset($res->carrier) ? self::value($res->carrier) : null;
                if (null === $carrier) {
                    $carrier = $resCarrier;
                } elseif ($carrier !== $resCarrier) {
                    $carrier = null;
                    $carrierInconsistent = true;
                }
            }

            $transactionTypes = array_values(array_unique(array_merge(
                $transactionTypes,
                self::values($res->transactionTypes ?? [])
            )));

            if (isset($res->collo->max)) {
                $colloMax = max($colloMax ?? 0, $res->collo->max);
            }
        }

        return new CapabilitiesResponse(
            $packageTypes,
            $deliveryTypes,
            $optionKeys,
            $carrier,
            $transactionTypes,
            $colloMax
        );
    }

    /**
     * The API names of the shipment options that the result allows, such as `requiresSignature`.
     *
     * The Jane model sets only the options that the API sent.
     *
     * @return string[]
     */
    private function optionNames(CoreCapabilityV2 $result): array
    {
        if (! isset($result->options)) {
            return [];
        }

        // toArray() gives the API names of the set options, also an option that the spec does not know yet.
        return array_keys($result->options->toArray());
    }

    /**
     * The API value of each item. Jane returns an enum case for a value it knows, and the raw string otherwise.
     *
     * @param array<BackedEnum|string> $items
     *
     * @return string[]
     */
    private static function values(array $items): array
    {
        return array_map([self::class, 'value'], $items);
    }

    private static function value(BackedEnum|string $item): string
    {
        return $item instanceof BackedEnum ? (string) $item->value : $item;
    }
}
