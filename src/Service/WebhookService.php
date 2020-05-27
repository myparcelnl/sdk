<?php

namespace Gett\MyParcel\Service;

use Gett\MyParcel\Logger\Logger;
use Gett\MyParcel\Model\Webhook\Subscription;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use Gett\MyParcel\Model\MyParcelRequest as Request;

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
        Logger::addLog(sprintf(
            'WebhookService::AddSubscription function called. Method: POST: Header: %s. Request message: %s',
            (string) Request::REQUEST_HEADER_WEBHOOK,
            (string) Request::REQUEST_TYPE_WEBHOOK
        ), false, true);
        Logger::addLog(sprintf(
            'WebhookService::AddSubscription function called. Result message: %s',
            json_encode($request->getResult())
        ), false, true);

        return $request->getResult();
    }

    public function deleteSubscription(int $id)
    {
        $result = $this->delete(Request::REQUEST_TYPE_WEBHOOK . '/' . $id);
        Logger::addLog('DeleteSubscription function called. Result: ' . $result);
        Logger::addLog(sprintf(
            'WebhookService::DeleteSubscription function called. Method: POST: Header: %s. Request message: %s',
            (string) Request::REQUEST_HEADER_WEBHOOK,
            Request::REQUEST_TYPE_WEBHOOK . '/' . $id
        ), false, true);
        Logger::addLog(sprintf(
            'WebhookService::DeleteSubscription function called. Result message: %s',
            $result
        ), false, true);

        return $result;
    }

    private function delete($url){

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                Request::REQUEST_HEADER_WEBHOOK,
                'Authorization: basic' . base64_encode($this->api_key)
            )
        );

        $result = curl_exec($ch);
        return $result;
    }
}