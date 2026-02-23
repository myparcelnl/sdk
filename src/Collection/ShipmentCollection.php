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
     * @param Shipment[] $shipments
     */
    public function __construct(array $shipments = [])
    {
        parent::__construct();
        $this->setShipments($shipments);
    }

    /**
     * Replace all items in the collection.
     *
     * @param Shipment[] $shipments
     */
    public function setShipments(array $shipments): self
    {
        $this->assertShipmentArray($shipments);
        $this->items = array_values($shipments);

        return $this;
    }

    /**
     * Add a single shipment to the collection.
     */
    public function add(Shipment $shipment): self
    {
        $this->push($shipment);

        return $this;
    }

    /**
     * Add multiple shipments to the collection.
     *
     * @param Shipment[] $shipments
     */
    public function addMany(array $shipments): self
    {
        $this->assertShipmentArray($shipments);

        foreach ($shipments as $shipment) {
            $this->push($shipment);
        }

        return $this;
    }

    /**
     * Get collection items.
     *
     * @return Shipment[]
     */
    public function getShipments(bool $keepKeys = true): array
    {
        return $keepKeys ? $this->all() : array_values($this->all());
    }

    /**
     * Get the first shipment or null when the collection is empty.
     */
    public function firstShipment(): ?Shipment
    {
        $shipment = $this->first();

        return $shipment instanceof Shipment ? $shipment : null;
    }

    /**
     * Get the last shipment or null when the collection is empty.
     */
    public function lastShipment(): ?Shipment
    {
        $shipment = $this->last();

        return $shipment instanceof Shipment ? $shipment : null;
    }

    /**
     * Filter by exact reference identifier.
     */
    public function filterByReferenceId(string $referenceIdentifier): self
    {
        return new self(array_values(array_filter(
            $this->items,
            static function ($shipment) use ($referenceIdentifier): bool {
                return $shipment instanceof Shipment
                    && $shipment->getReferenceIdentifier() === $referenceIdentifier;
            }
        )));
    }

    /**
     * Filter by reference identifier prefix.
     */
    public function filterByReferenceIdPrefix(string $referencePrefix): self
    {
        return new self(array_values(array_filter(
            $this->items,
            static function ($shipment) use ($referencePrefix): bool {
                if (! $shipment instanceof Shipment) {
                    return false;
                }

                $referenceIdentifier = $shipment->getReferenceIdentifier();
                if (! is_string($referenceIdentifier) || '' === $referenceIdentifier) {
                    return false;
                }

                return 0 === strpos($referenceIdentifier, $referencePrefix);
            }
        )));
    }

    /**
     * Clear all items from the collection.
     */
    public function clear(): self
    {
        $this->items = [];

        return $this;
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
}
