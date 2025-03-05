<?php

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

class PrinterlessReturnRequest extends MyParcelRequest
{
    /**
     * @var \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment
     */
    private $consignment;

    public function __construct(AbstractConsignment $consignment)
    {
        $this->consignment = $consignment;
    }

    /**
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @return mixed response can be null, an array (when payment is required), or a string that is a png
     */
    public function send()
    {
        $this->setApiKey($this->consignment->getApiKey())
            ->setHeaders(
                MyParcelRequest::HEADER_ACCEPT_IMAGE_PNG + MyParcelRequest::HEADER_CONTENT_TYPE_UNRELATED_RETURN_SHIPMENT
            )
            ->sendRequest('GET', "printerless_return_label/{$this->consignment->getConsignmentId()}");

        return $this->getResult();
    }
}