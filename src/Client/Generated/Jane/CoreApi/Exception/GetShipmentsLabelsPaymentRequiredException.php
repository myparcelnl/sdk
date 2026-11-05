<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Exception;

class GetShipmentsLabelsPaymentRequiredException extends PaymentRequiredException
{
    public function __construct(
        /**
         * @var \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequired
         */
        private readonly \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequired $shipmentResponsesBulkShipmentLabelsPaymentRequired,
        /**
         * @var \Symfony\Contracts\HttpClient\ResponseInterface
         */
        private readonly \Symfony\Contracts\HttpClient\ResponseInterface $response
    )
    {
        parent::__construct('Payment Required');
    }
    public function getShipmentResponsesBulkShipmentLabelsPaymentRequired(): \MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\ShipmentResponsesBulkShipmentLabelsPaymentRequired
    {
        return $this->shipmentResponsesBulkShipmentLabelsPaymentRequired;
    }
    public function getResponse(): \Symfony\Contracts\HttpClient\ResponseInterface
    {
        return $this->response;
    }
}