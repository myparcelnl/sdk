<?php

namespace Gett\MyparcelBE\Service;

use Gett\MyparcelBE\Logger\Logger;
use Gett\MyparcelBE\Model\Webhook\Subscription;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use Gett\MyparcelBE\Model\MyParcelRequest as Request;

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
            ->setUserAgent('prestashop' . '/' . _PS_VERSION_)
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
        Logger::addLog('DeleteSubscription function called. Result: ' . $result['result']);
        Logger::addLog(sprintf(
            'WebhookService::DeleteSubscription function called. Method: POST: Header: %s. Request message: %s',
            (string) Request::REQUEST_HEADER_WEBHOOK,
            Request::REQUEST_TYPE_WEBHOOK . '/' . $id
        ), false, true);
        Logger::addLog(sprintf(
            'WebhookService::DeleteSubscription function called. Result message: %s. Http status: %s. Error: %s',
            $result['result'],
            $result['httpCode'],
            $result['error']
        ), false, true);

        if ($result['httpCode'] == 204) {
            return true;
        }

        return false;
    }

    private function delete(string $url): array
    {

        $ch = curl_init();
        $options = [
            CURLOPT_URL => MyParcelRequest::REQUEST_URL . '/' . $url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_FAILONERROR => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: basic ' . base64_encode($this->api_key),
                'User-Agent: CustomApiCall/2',
                Request::REQUEST_HEADER_WEBHOOK
            ]
        ];
        curl_setopt_array($ch, $options);

        $result = curl_exec($ch);

        return [
            'result' => $result,
            'httpCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
            'error' => curl_error($ch)
        ];
    }
}
