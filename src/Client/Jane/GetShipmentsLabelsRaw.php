<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\GetShipmentsLabels;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Fetches labels and returns a 2xx response as it is.
 *
 * The generated GetShipmentsLabels reads a 2xx response only for the JSON content types, not the
 * binary PDF that the spec documents as `application/*`. This endpoint returns the response, so
 * ShipmentLabelsService reads the body itself. An error response throws the generated exceptions,
 * such as GetShipmentsLabelsNotFoundException.
 *
 * @todo Remove this class when the generated endpoint reads the binary response.
 *
 * @internal
 */
final class GetShipmentsLabelsRaw extends GetShipmentsLabels
{
    protected function transformResponseBody(ResponseInterface $response, SerializerInterface $serializer, ?string $contentType = null)
    {
        $status = $response->getStatusCode();

        if ($status >= 200 && $status <= 299) {
            return $response;
        }

        return parent::transformResponseBody($response, $serializer, $contentType);
    }
}
