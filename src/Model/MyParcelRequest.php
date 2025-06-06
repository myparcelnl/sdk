<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Helper\MyParcelCurl;
use MyParcelNL\Sdk\Helper\RequestError;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Support\Arr;

class MyParcelRequest
{
    use HasApiKey;
    use HasUserAgent;

    /**
     * Supported request types.
     */
    public const REQUEST_TYPE_SHIPMENTS               = 'shipments';
    public const REQUEST_TYPE_RETRIEVE_LABEL          = 'shipment_labels';
    public const REQUEST_TYPE_RETRIEVE_PREPARED_LABEL = 'v2/shipment_labels';
    public const REQUEST_TYPE_ORDERS                  = 'fulfilment/orders';
    public const REQUEST_TYPE_ORDER_NOTES             = 'fulfilment/orders/{id}/notes';

    public const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;

    /**
     * API headers.
     */
    public const HEADER_ACCEPT_APPLICATION_PDF                 = ['Accept' => 'application/pdf'];
    public const HEADER_ACCEPT_IMAGE_PNG                       = ['Accept' => 'image/png'];
    public const HEADER_CONTENT_TYPE_SHIPMENT                  = ['Content-Type' => 'application/vnd.shipment+json;charset=utf-8;version=1.1',];
    public const HEADER_CONTENT_TYPE_RETURN_SHIPMENT           = ['Content-Type' => 'application/vnd.return_shipment+json; charset=utf-8'];
    public const HEADER_CONTENT_TYPE_UNRELATED_RETURN_SHIPMENT = ['Content-Type' => 'application/vnd.unrelated_return_shipment+json;version=1.1; charset=utf-8'];
    public const HEADER_SET_CUSTOM_SENDER                      = ['x-dmp-set-custom-sender' => 'true'];

    /**
     * API URL.
     */
    public const REQUEST_URL = 'https://api.myparcel.nl';

    /**
     * Error codes.
     */
    private const ERROR_CODE_ACCOUNT_NOT_ACTIVATED = 3716;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @var string|null
     */
    private $error;

    /**
     * @var array
     */
    private $errorCodes = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array|null
     */
    private $query;

    /**
     * @var array
     */
    private $response;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getHeaders(): array
    {
        return array_merge($this->getDefaultHeaders(), $this->headers);
    }

    /**
     * Will set the by reference $key to the api key of the first consignment in the collection.
     * @param                    $size
     * @param MyParcelCollection $collection
     * @param                    $key
     * @return string|null
     * @deprecated use static getLatestDataParameters instead
     */
    public function getLatestDataParams($size, MyParcelCollection $collection, &$key): ?string
    {
        $key = $collection->first()->getApiKey();

        return self::getLatestDataParameters($collection, $size);
    }

    /**
     * Returns the uri as string to use with the MyParcel api to get the latest data for the consignments in the collection.
     * It uses shipmentIds when available or else the reference identifiers.
     * NOTE: the supplied collection must contain shipments that all have shipmentIds or all have reference identifiers.
     * NOTE: shipments in the collection must all have the same api key (or you cannot send the request, obviously).
     * Returns null when shipmentIds nor reference identifiers are available.
     *
     * @param int                $size
     * @param MyParcelCollection $consignments
     *
     * @return string|null
     */
    public static function getLatestDataParameters(MyParcelCollection $consignments, int $size): ?string
    {
        $consignmentIds = $consignments->reduce(
            static function ($carry, $item) {
                if (($consignmentId = $item->getConsignmentId())) {
                    $carry[] = $consignmentId;
                }

                return $carry;
            },
            []
        );

        if ($consignmentIds) {
            return implode(';', $consignmentIds) . "?size=$size";
        }

        $referenceIds = $consignments->reduce(
            static function ($carry, $item) {
                if (($referenceIdentifier = $item->getReferenceIdentifier())) {
                    $carry[] = $referenceIdentifier;
                }

                return $carry;
            },
            []
        );

        if ($referenceIds) {
            return '?reference_identifier=' . implode(';', $referenceIds) . "&size=$size";
        }

        return null;
    }

    /**
     * Get request url.
     *
     * @return string
     */
    public function getRequestUrl(): string
    {
        return getenv('MYPARCEL_API_BASE_URL') ?: self::REQUEST_URL;
    }

    /**
     * Get an item from the result using dot notation.
     *
     * @param string|null $key
     * @param string|null $pluck
     *
     * @return mixed
     */
    public function getResult(string $key = null, string $pluck = null)
    {
        if (null === $key) {
            return $this->result;
        }

        $result = Arr::get($this->result, $key);

        if ($pluck) {
            $result = Arr::pluck($result, $pluck);
        }

        return $result;
    }

    /**
     * Send the created request to MyParcel.
     *
     * @param string $method
     * @param string $uri
     *
     * @return self|bool
     * @throws AccountNotActiveException
     * @throws ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    public function sendRequest(string $method = 'POST', string $uri = self::REQUEST_TYPE_SHIPMENTS)
    {
        if (!$this->checkConfigForRequest()) {
            return false;
        }

        $url     = $this->createRequestUrl($uri, $method);
        $request = $this->instantiateCurl();

        $request->write($method, $url, $this->getHeaders(), $this->getRequestBody());

        $this->setResult($request);
        $request->close();

        $this->handleErrors($url);

        return $this;
    }

    /**
     * @param string|array $requestHeaders
     *
     * @return self
     */
    public function setHeaders($requestHeaders): self
    {
        if (is_array($requestHeaders)) {
            $this->headers = $requestHeaders;
        } else {
            $this->headers[] = $requestHeaders;
        }

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function setQuery(array $parameters): self
    {
        $this->query = $parameters;
        return $this;
    }

    /**
     * @param \MyParcelNL\Sdk\Model\RequestBody|array|string|null $body
     *
     * @return self
     */
    public function setRequestBody($body): self
    {
        if (is_a($body, RequestBody::class)) {
            $body = $body->toJson();
        }

        if ($body && !is_string($body)) {
            $body = json_encode($body);
        }

        $this->body = $body;
        return $this;
    }

    /**
     * Sets the parameters for an API call based on a string with all required request parameters and the requested API
     * method.
     *
     * @param string            $apiKey
     * @param array|string|null $body
     * @param string|array      $requestHeaders
     *
     * @return self
     */
    public function setRequestParameters(string $apiKey, $body = null, $requestHeaders = []): self
    {
        return $this->setApiKey($apiKey)->setRequestBody($body)->setHeaders($requestHeaders);
    }

    /**
     * Checks if all the requirements are set to send a request to MyParcel.
     *
     * @return bool
     * @throws MissingFieldException
     */
    private function checkConfigForRequest(): bool
    {
        if (empty($this->getApiKey())) {
            throw new MissingFieldException('api_key not found');
        }

        return true;
    }

    /**
     * Check if MyParcel gives an error.
     *
     * @return void
     */
    private function checkMyParcelErrors(): void
    {
        if (!is_array($this->response['response']) || empty($this->response['response']['errors'])) {
            return;
        }

        $error            = reset($this->result['errors']);
        $this->errorCodes = Arr::pluck($this->response['response']['errors'], 'code');

        if ((int) key($error) > 0) {
            $error = current($error);
        }

        $this->error = RequestError::getTotalMessage($error, $this->response);
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return string
     */
    private function createRequestUrl(string $uri, string $method): string
    {
        $url         = $this->getRequestUrl();
        $url         .= "/$uri";
        $requestBody = $this->getRequestBody();

        if ($method !== 'POST' && $requestBody) {
            $url .= '/' . $requestBody;
        }

        if ($this->query) {
            $url .= '?' . http_build_query($this->query);
        }

        return $url;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getDefaultHeaders(): array
    {
        return [
            'Accept'        => 'application/json;charset=utf8',
            'Authorization' => 'basic ' . $this->getEncodedApiKey(),
            'Content-Type'  => 'application/json;charset=utf8',
            'User-Agent'    => $this->getUserAgentHeader(),
        ];
    }

    /**
     * @return string|null
     */
    private function getRequestBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $url
     *
     * @throws AccountNotActiveException
     * @throws ApiException
     */
    private function handleErrors(string $url): void
    {
        if ($this->getError()) {
            switch (Arr::first($this->errorCodes)) {
                case self::ERROR_CODE_ACCOUNT_NOT_ACTIVATED:
                    throw new AccountNotActiveException(
                        sprintf(
                            'Error %s Your account needs to be activated by MyParcel.',
                            Arr::first($this->errorCodes)
                        )
                    );
                default:
                    throw new ApiException(
                        json_encode($this->getError(), JSON_PRETTY_PRINT)
                    );
            }
        }
    }

    /**
     * @return MyParcelCurl
     */
    private function instantiateCurl(): MyParcelCurl
    {
        return (new MyParcelCurl())->setConfig(
            [
                'header'  => 0,
                'timeout' => 60,
            ]
        )->addOptions(
            [
                CURLOPT_POST           => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER    => true,
            ]
        );
    }

    /**
     * @param MyParcelCurl $request
     *
     * @return array
     * @throws \Exception
     */
    private function setResult(MyParcelCurl $request): array
    {
        $response = $request->getResponse();

        /**
         * The response may contain a pdf or a png (for printerless return).
         * So we only upgrade 'response' to array when json_decode is successful.
         */
        $jsonResponse = json_decode($response['response'], true);
        if (null !== $jsonResponse) {
            $response['response'] = $jsonResponse;
        }
        $this->response       = $response;
        $this->result         = $response['response'];


        if (false === $this->result) {
            $this->error = $request->getError();
        }

        $this->checkMyParcelErrors();

        return $response;
    }
}
