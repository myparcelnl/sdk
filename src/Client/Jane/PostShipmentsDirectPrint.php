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
 * The Core API spec documents direct print on POST /shipments, but gives the print media type as the
 * content type of the response. The API answers with `application/json` and the ids of the new
 * shipments. The generated `PostShipments` reads an `application/json` 200 as
 * `ShipmentResponsesPostShipmentsV12`, which has no ids. So this endpoint reads a 200 response as
 * `ShipmentResponsesShipmentIds`. The generated `PostShipments` reads every other response, such as
 * an error.
 *
 * @todo CLOUD-2376: remove this class when the spec gives `application/json` as the content type of
 *       the direct print response, and the generated PostShipments reads the ids.
 *
 * @internal
 */
final class PostShipmentsDirectPrint extends PostShipments
{
    private const ACCEPT = 'application/vnd.shipment_label+json+print;printer-group-id=%s';

    public function __construct(ShipmentPostShipmentsRequestV11 $requestBody, string $printerGroupId)
    {
        // The second Accept value is the one of the generated endpoint, so an error can come as
        // `application/problem+json`. The spec does not say which Accept value the API uses for an error.
        parent::__construct($requestBody, [], [], [
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
