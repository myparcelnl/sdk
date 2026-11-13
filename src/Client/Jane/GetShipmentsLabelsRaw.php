<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipmentsLabels;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Fetches the label link and returns a 2xx response as it is. An error response throws the
 * generated exceptions, such as `GetShipmentsLabelsNotFoundException`.
 *
 * @internal
 */
final class GetShipmentsLabelsRaw extends GetShipmentsLabels
{
    protected function transformResponseBody(ResponseInterface $response, SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();

        // The API can send the link as a path, such as `/pdfs/download/1`, relative to the host
        // that the request went to. Only the response knows that host, so `ShipmentLabelsService`
        // reads the body and the URL of the response itself. The PDF goes through the generated
        // `getShipmentsLabels()`.
        if ($status >= 200 && $status <= 299) {
            return $response;
        }

        return parent::transformResponseBody($response, $serializer, $contentType);
    }
}
