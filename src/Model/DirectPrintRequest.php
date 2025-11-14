<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;

class DirectPrintRequest extends MyParcelRequest
{
    /**
     * @var string
     */
    private string $printerGroupId;

    /**
     * @var array
     */
    private array $shipmentIds;

    /**
     * @param string $apiKey
     * @param string $printerGroupId
     * @param array  $shipmentIds
     */
    public function __construct(string $apiKey, string $printerGroupId, array $shipmentIds)
    {
        $this->printerGroupId = $printerGroupId;
        $this->shipmentIds    = $shipmentIds;
        $this->setApiKey($apiKey);
    }

    /**
     * Send the direct print request to MyParcel API
     *
     * @return array
     * @throws ApiException
     * @throws MissingFieldException
     * @throws AccountNotActiveException
     */
    public function send(): array
    {
        $body = [
            'printer_group_id' => $this->printerGroupId,
            'shipment_ids'     => $this->shipmentIds,
        ];

        $this->setRequestBody(json_encode($body))
             ->setHeaders(MyParcelRequest::HEADER_CONTENT_TYPE_SHIPMENT)
             ->sendRequest('POST', MyParcelRequest::REQUEST_TYPE_PRINT_SHIPMENTS);

        return $this->getResult();
    }
}

