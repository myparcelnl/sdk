<?php

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;

class PrinterlessReturnRequest extends MyParcelRequest
{
    private AbstractConsignment $consignment;

    public function __construct(AbstractConsignment $consignment)
    {
        $this->consignment = $consignment;
    }

    /**
     * @return mixed response can be null, an array (when payment is required), or a string that is a png
     * @throws ApiException
     * @throws MissingFieldException
     * @throws AccountNotActiveException
     */
    public function send()
    {
        $this->setApiKey($this->consignment->getApiKey())
             ->setHeaders(
                 MyParcelRequest::HEADER_ACCEPT_IMAGE_PNG + MyParcelRequest::HEADER_CONTENT_TYPE_UNRELATED_RETURN_SHIPMENT
             )
             ->sendRequest('GET', "printerless_return_label/{$this->consignment->getConsignmentId()}")
        ;

        return $this->getResult();
    }
}
