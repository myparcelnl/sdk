<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11DataShipmentsItem as ShipmentItem;
use MyParcelNL\Sdk\Client\Jane\CoreApiModels;

/**
 * Builds a shipment of a `POST /shipments` request, with the fields that the spec requires.
 */
trait BuildsShipmentItem
{
    protected static function shipment(?string $referenceIdentifier = null): ShipmentItem
    {
        return CoreApiModels::toModel(array_filter([
            'reference_identifier' => $referenceIdentifier,
            'carrier'              => 1,
            'options'              => ['package_type' => 1],
            'recipient'            => [
                'cc'          => 'NL',
                'postal_code' => '2132JE',
                'city'        => 'Hoofddorp',
                'street'      => 'Antareslaan',
                'number'      => '31',
                'person'      => 'Test Person',
            ],
        ], static fn (mixed $value): bool => null !== $value), ShipmentItem::class);
    }
}
