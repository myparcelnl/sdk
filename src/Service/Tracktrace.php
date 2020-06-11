<?php

namespace Gett\MyparcelBE\Service;

use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use Gett\MyparcelBE\Model\MyParcelRequest as Request;

class Tracktrace
{
    private $api_key;

    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    public function getTrackTrace(int $id_label)
    {
        $request = (new MyParcelRequest())
            ->setUserAgent()
            ->setRequestParameters(
                $this->api_key,
                '',
                MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
            )
            ->sendRequest('GET', Request::REQUEST_TYPE_TRACKTRACE . "/{$id_label}")
        ;

        return $request->getResult();
    }
}
