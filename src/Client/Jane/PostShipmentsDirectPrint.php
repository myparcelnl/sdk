<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostShipments;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostShipmentsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesShipmentIds;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Creates shipments and prints their labels on a printer group.
 *
 * The Core API spec does not document direct print. The API answers with the ids of the new
 * shipments, so this endpoint reads a 200 response as `ShipmentResponsesShipmentIds`. The generated
 * `PostShipments` reads every other response, such as an error.
 *
 * @todo Remove this class when the spec documents the direct print Accept header and its response.
 *
 * @internal
 */
final class PostShipmentsDirectPrint extends PostShipments
{
    private const ACCEPT = 'application/vnd.shipment_label+json+print;printer-group-id=%s';

    /**
     * @param array<string, string> $headerParameters
     */
    public function __construct(ShipmentPostShipmentsRequestV11 $requestBody, string $printerGroupId, array $headerParameters = [])
    {
        // The second Accept value makes the API send an error as application/problem+json,
        // see ProblemJsonAcceptHttpClient.
        parent::__construct($requestBody, [], $headerParameters, [
            'Accept' => [sprintf(self::ACCEPT, $printerGroupId), 'application/problem+json'],
        ]);
    }

    protected function transformResponseBody(ResponseInterface $response, SerializerInterface $serializer, ?string $contentType = null)
    {
        if (200 === $response->getStatusCode()) {
            return $serializer->deserialize($response->getContent(false), ShipmentResponsesShipmentIds::class, 'json');
        }

        return parent::transformResponseBody($response, $serializer, $contentType);
    }
}
