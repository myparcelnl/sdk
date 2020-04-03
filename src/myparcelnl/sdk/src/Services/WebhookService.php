<?php

namespace Gett\MyParcel\Sdk\src\Services;

use Gett\MyParcel\Sdk\src\Model\MyParcelRequest as Request;
use Gett\MyParcel\Sdk\src\Model\Webhook\Subscription;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

class WebhookService
{
    private $api_key;

    public function __construct(string $api_key)
    {
        $this->api_key = $api_key;
    }

    public function addSubscription(Subscription $subscription)
    {
        $request = (new MyParcelRequest())
            ->setUserAgent()
            ->setRequestParameters(
                $this->api_key,
                $subscription->encode(),
                Request::REQUEST_HEADER_WEBHOOK
            )
            ->sendRequest('POST', Request::REQUEST_TYPE_WEBHOOK);

        return json_decode($request->getResult(), true);
    }

    public function deleteSubscription(int $id)
    {
        $request = (new MyParcelRequest())
            ->setUserAgent()
            ->setRequestParameters(
                $this->api_key,
                null,
                Request::REQUEST_HEADER_WEBHOOK
            )
            ->sendRequest('DELETE', Request::REQUEST_TYPE_WEBHOOK . "/$id");
    }
}
