<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;

class PrinterlessReturnRequest extends MyParcelRequest
{
    private string $apiKey;
    private int $consignmentId;

    public function __construct(string $apiKey, int $consignmentId)
    {
        $this->apiKey = $apiKey;
        $this->consignmentId = $consignmentId;
    }

    /**
     * @return mixed response can be null, an array (when payment is required), or a string that is a png
     * @throws ApiException
     * @throws MissingFieldException
     * @throws AccountNotActiveException
     */
    public function send()
    {
        $this->setApiKey($this->apiKey)
             ->setHeaders(
                 MyParcelRequest::HEADER_ACCEPT_IMAGE_PNG + MyParcelRequest::HEADER_CONTENT_TYPE_UNRELATED_RETURN_SHIPMENT
             )
             ->sendRequest('GET', "printerless_return_label/{$this->consignmentId}")
        ;

        return $this->getResult();
    }
}
