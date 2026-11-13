<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Bootstrap;

/**
 * Builds the body of a `GET /shipments` response for a mock HTTP client.
 */
trait BuildsShipmentsResponse
{
    /**
     * @param array<int, array<string, mixed>> $shipments
     */
    protected static function shipmentsResponse(array $shipments): string
    {
        return json_encode(['data' => ['shipments' => $shipments, 'results' => count($shipments)]], JSON_THROW_ON_ERROR);
    }
}
