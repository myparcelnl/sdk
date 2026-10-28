<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Jane;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Endpoint\PostShipments;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentPostReturnShipmentsRequest;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Creates return shipments for existing shipments, and tells the API whether to email the recipient.
 *
 * The Core API spec does not document the `send_return_mail` query parameter of `POST /shipments`.
 * The generated `PostShipments` rejects a query parameter that the spec does not have, so this
 * endpoint adds it. The generated `PostShipments` picks the content type from the request body
 * and reads the response.
 *
 * @todo Remove this class when the spec documents `send_return_mail`.
 *
 * @internal
 */
final class PostReturnShipments extends PostShipments
{
    /**
     * @param array<string, string> $headerParameters
     */
    public function __construct(ShipmentPostReturnShipmentsRequest $requestBody, bool $sendReturnMail, array $headerParameters = [])
    {
        parent::__construct($requestBody, ['send_return_mail' => $sendReturnMail], $headerParameters);
    }

    protected function getQueryOptionsResolver(): OptionsResolver
    {
        $optionsResolver = parent::getQueryOptionsResolver();

        $optionsResolver->setDefined(['send_return_mail']);
        $optionsResolver->addAllowedTypes('send_return_mail', ['bool']);

        return $optionsResolver;
    }
}
