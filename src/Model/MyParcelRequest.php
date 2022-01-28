<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Concerns\HasUserAgent;
use MyParcelNL\Sdk\src\Exception\AccountNotActiveException;
use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Helper\MyParcelCurl;
use MyParcelNL\Sdk\src\Helper\RequestError;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Support\Arr;

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

    public const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;

    /**
     * API headers.
     */
    public const HEADER_CONTENT_TYPE_SHIPMENT        = [
        'Content-Type' => 'application/vnd.shipment+json;charset=utf-8;version=1.1',
    ];
    public const HEADER_ACCEPT_APPLICATION_PDF       = ['Accept' => 'application/pdf'];
    public const HEADER_CONTENT_TYPE_RETURN_SHIPMENT = ['Content-Type' => 'application/vnd.return_shipment+json; charset=utf-8'];

    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_SHIPMENT            = 'Content-Type: application/vnd.shipment+json;charset=utf-8;version=1.1';
    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_RETRIEVE_SHIPMENT   = 'Accept: application/json; charset=utf8';
    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_RETRIEVE_LABEL_LINK = 'Accept: application/json; charset=utf8';
    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_RETRIEVE_LABEL_PDF  = 'Accept: application/pdf';
    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_RETURN              = 'Content-Type: application/vnd.return_shipment+json; charset=utf-8';
    /* @deprecated use HEADER_CONTENT_TYPE_SHIPMENT, HEADER_ACCEPT_APPLICATION_PDF or HEADER_CONTENT_TYPE_RETURN_SHIPMENT */
    public const REQUEST_HEADER_DELETE              = 'Accept: application/json; charset=utf8';

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
     * @param                     $size
     * @param  MyParcelCollection $collection
     * @param                     $key
     *
     * @return string|null
     */
    public function getLatestDataParams($size, MyParcelCollection $collection, &$key): ?string
    {
        $params         = null;
        $consignmentIds = $collection->getConsignmentIds($key);

        if ($consignmentIds !== null) {
            $params = implode(';', $consignmentIds) . '?size=' . $size;
        } else {
            $referenceIds = $this->getConsignmentReferenceIds($collection, $key);
            if (! empty($referenceIds)) {
                $params = '?reference_identifier=' . implode(';', $referenceIds) . '&size=' . $size;
            }
        }

        return $params;
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
     * @param  string|null $key
     * @param  string|null $pluck
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
     * @param  string $method
     * @param  string $uri
     *
     * @return self|bool
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function sendRequest(string $method = 'POST', string $uri = self::REQUEST_TYPE_SHIPMENTS)
    {
        if (! $this->checkConfigForRequest()) {
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
     * @param  string|array $requestHeaders
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
     * @param  array $parameters
     *
     * @return self
     */
    public function setQuery(array $parameters): self
    {
        $this->query = $parameters;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\RequestBody|array|string|null $body
     *
     * @return self
     */
    public function setRequestBody($body): self
    {
        if (is_a($body, RequestBody::class)) {
            $body = $body->toJson();
        }

        if ($body && ! is_string($body)) {
            $body = json_encode($body);
        }

        $this->body = $body;
        return $this;
    }

    /**
     * Sets the parameters for an API call based on a string with all required request parameters and the requested API
     * method.
     *
     * @param  string            $apiKey
     * @param  array|string|null $body
     * @param  string|array      $requestHeaders
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
        if (! is_array($this->response['response']) || empty($this->response['response']['errors'])) {
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
     * @param  string $uri
     * @param  string $method
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
     * Get all consignment ids.
     *
     * @param  MyParcelCollection|AbstractConsignment[] $consignments
     * @param                                           $key
     *
     * @return array
     */
    private function getConsignmentReferenceIds($consignments, &$key): array
    {
        $referenceIds = [];

        foreach ($consignments as $consignment) {
            if ($consignment->getReferenceId()) {
                $referenceIds[] = $consignment->getReferenceId();
            }

            $key = $consignment->getApiKey();
        }

        return $referenceIds;
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
     * @param  string $url
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
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
        return (new MyParcelCurl())->setConfig([
                'header'  => 0,
                'timeout' => 60,
            ])->addOptions([
                CURLOPT_POST           => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER    => true,
            ]);
    }

    /**
     * @param  MyParcelCurl $request
     *
     * @return array
     * @throws \Exception
     */
    private function setResult(MyParcelCurl $request): array
    {
        $response = $request->getResponse();

        if (preg_match('/^%PDF-\d+\.\d+/', $response['response'])) {
            $this->result   = $response['response'];
            $this->response = $response;
        } else {
            $response['response'] = json_decode($response['response'], true);
            $this->response       = $response;
            $this->result         = $response['response'];

            if ($this->result === false) {
                $this->error = $request->getError();
            }

            $this->checkMyParcelErrors();
        }

        return $response;
    }
}
