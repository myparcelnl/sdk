<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Collection;

use InvalidArgumentException;
use MyParcelNL\Sdk\Model\Shipment\Shipment;
use MyParcelNL\Sdk\Support\Collection;

/**
 * Collection for shipment models.
 *
 * Intended usage:
 * - store and iterate shipments
 * - perform in-memory filtering/lookups
 * - no network side-effects
 *
 * API operations are handled by dedicated services.
 *
 * @property Shipment[] $items
 */
final class ShipmentCollection extends Collection
{
    /**
     * Push shipments onto the collection.
     *
     * @param mixed ...$values
     */
    public function push(...$values): self
    {
        $this->assertShipmentArray($values);

        return parent::push(...$values);
    }

    /**
     * Filter by exact reference identifier.
     */
    public function whereReferenceIdentifier(string $referenceIdentifier): self
    {
        return $this
            ->where('reference_identifier', $referenceIdentifier)
            ->values();
    }

    /**
     * Filter by reference identifier prefix.
     */
    public function whereReferenceIdentifierPrefix(string $referencePrefix): self
    {
        return $this
            ->filter(static function ($shipment) use ($referencePrefix): bool {
                if (! $shipment instanceof Shipment) {
                    return false;
                }

                $referenceIdentifier = $shipment->getReferenceIdentifier();

                return is_string($referenceIdentifier) && 0 === strpos($referenceIdentifier, $referencePrefix);
            })
            ->values();
    }

    /**
     * @param mixed[] $shipments
     */
    private function assertShipmentArray(array $shipments): void
    {
        foreach ($shipments as $shipment) {
            if (! $shipment instanceof Shipment) {
                throw new InvalidArgumentException('All items must be instances of ' . Shipment::class);
            }
        }
    }

    /**
     * @param mixed $items
     * @return array<int, Shipment>
     */
    protected function getArrayableItems($items)
    {
        $array = parent::getArrayableItems($items);
        $this->assertShipmentArray($array);

        return array_values($array);
    }
}
