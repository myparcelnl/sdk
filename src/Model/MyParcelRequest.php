<?php declare(strict_types=1);
/** @noinspection PhpInternalEntityUsedInspection */

/**
 * This model represents one request
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\Model;

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
    /**
     * API URL
     */
    const REQUEST_URL = 'https://api.myparcel.nl';

    /**
     * Supported request types.
     */
    const REQUEST_TYPE_SHIPMENTS               = 'shipments';
    const REQUEST_TYPE_RETRIEVE_LABEL          = 'shipment_labels';
    const REQUEST_TYPE_RETRIEVE_PREPARED_LABEL = 'v2/shipment_labels';

    const SHIPMENT_LABEL_PREPARE_ACTIVE_FROM = 25;

    /**
     * API headers
     */
    const REQUEST_HEADER_SHIPMENT            = 'Content-Type: application/vnd.shipment+json;charset=utf-8;version=1.1';
    const REQUEST_HEADER_RETRIEVE_SHIPMENT   = 'Accept: application/json; charset=utf8';
    const REQUEST_HEADER_RETRIEVE_LABEL_LINK = 'Accept: application/json; charset=utf8';
    const REQUEST_HEADER_RETRIEVE_LABEL_PDF  = 'Accept: application/pdf';
    const REQUEST_HEADER_RETURN              = 'Content-Type: application/vnd.return_shipment+json; charset=utf-8';
    const REQUEST_HEADER_DELETE              = 'Accept: application/json; charset=utf8';

    /**
     * Error codes
     */
    const ERROR_CODE_ACCOUNT_NOT_ACTIVATED = 3716;

    /**
     * @var string
     */
    private $api_key = '';
    private $header = [];

    /**
     * @var string|null
     */
    private $body       = '';
    private $error      = null;
    private $errorCodes = [];
    private $result     = null;
    private $userAgent  = null;

    /**
     * @var array|null
     */
    private $query;

    /**
     * Get an item from tje result using "dot" notation.
     *
     * @param string $key
     * @param string $pluck
     *
     * @return mixed
     */
    public function getResult($key = null, $pluck = null)
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
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the parameters for an API call based on a string with all required request parameters and the requested API
     * method.
     *
     * @param string      $apiKey
     * @param string|null $body
     * @param string      $requestHeader
     *
     * @return $this
     */
    public function setRequestParameters(string $apiKey, ?string $body, string $requestHeader): MyParcelRequest
    {
        $this->api_key = $apiKey;
        $this->body    = $body;

        $header[] = $requestHeader;
        $header[] = 'Authorization: basic ' . base64_encode($this->api_key);
        $header[] = 'User-Agent: ' . $this->getUserAgent();
        $this->header = $header;

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return \MyParcelNL\Sdk\src\Model\MyParcelRequest
     */
    public function setQuery(array $parameters)
    {
        $this->query = $parameters;

        return $this;
    }

    /**
     * send the created request to MyParcel
     *
     * @param string $method
     * @param string $uri
     *
     * @return MyParcelRequest|array|false|string
     * @throws ApiException
     * @throws MissingFieldException
     */
    public function sendRequest($method = 'POST', $uri = self::REQUEST_TYPE_SHIPMENTS)
    {
        if (! $this->checkConfigForRequest()) {
            return false;
        }

        $request = $this->instantiateCurl();

        $this->setUserAgent();

        $header = $this->header;
        $url    = $this->getRequestUrl($uri);
        if ($method !== 'POST' && $this->body) {
            $url .= '/' . $this->body;
        }

        if ($this->query) {
            $url .= '?' . http_build_query($this->query);
        }

        $request->write($method, $url, $header, $this->body);
        $this->setResult($request);
        $request->close();

        if ($this->getError()) {
            switch (Arr::first($this->errorCodes)) {
                case self::ERROR_CODE_ACCOUNT_NOT_ACTIVATED:
                    throw new AccountNotActiveException('Error ' . Arr::first($this->errorCodes) . ' Your account needs to be activated by MyParcel.');
                default:
                    throw new ApiException('Error in MyParcel API request: ' . $this->getError() . ' Url: ' . $url . ' Request: ' . $this->body);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent = null)
    {
        if ($userAgent) {
            $this->userAgent = $userAgent;
        }
        if ($this->getUserAgent() == null && $this->getUserAgentFromComposer() !== null) {
            $this->userAgent = trim($this->getUserAgent() . ' ' . $this->getUserAgentFromComposer());
        }

        return $this;
    }

    /**
     * Get version of SDK from composer file
     */
    public function getUserAgentFromComposer()
    {
        $composerData = $this->getComposerContents();

        if ($composerData && ! empty($composerData['name'])
            && $composerData['name'] == 'myparcelnl/sdk'
            && ! empty($composerData['version'])
        ) {
            $version = str_replace('v', '', $composerData['version']);
        } else {
            $version = 'unknown';
        }

        return 'MyParcelNL-SDK/' . $version;
    }

    /**
     * @param                    $size
     * @param MyParcelCollection $collection
     * @param                    $key
     *
     * @return string|null
     */
    public function getLatestDataParams($size, $collection, &$key)
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
     * Check if MyParcel gives an error
     *
     * @return $this|void
     */
    private function checkMyParcelErrors()
    {
        if (! is_array($this->result) || empty($this->result['errors'])) {
            return;
        }

        $error = reset($this->result['errors']);
        $this->errorCodes = array_keys($error);
        if ((int) key($error) > 0) {
            $error = current($error);
        }
        $this->error = RequestError::getTotalMessage($error, $this->result);
    }

    /**
     * Get request url
     *
     * @param string $uri
     *
     * @return string
     */
    private function getRequestUrl($uri)
    {
        $url = self::REQUEST_URL . '/' . $uri;

        return $url;
    }

    /**
     * Checks if all the requirements are set to send a request to MyParcel
     *
     * @return bool
     * @throws MissingFieldException
     */
    private function checkConfigForRequest()
    {
        if (empty($this->api_key)) {
            throw new MissingFieldException('api_key not found');
        }

        return true;
    }

    /**
     * Get composer.json
     *
     * @return string|null
     */
    private function getComposerContents()
    {
        $composer_locations = [
            'vendor/myparcelnl/sdk/composer.json',
            './composer.json'
        ];

        foreach ($composer_locations as $composerFile) {
            if (file_exists($composerFile)) {
                return json_decode(file_get_contents($composerFile), true);
            }
        }

        return null;
    }

    /**
     * Get all consignment ids
     *
     * @param MyParcelCollection|AbstractConsignment[] $consignments
     * @param                                          $key
     *
     * @return array
     */
    private function getConsignmentReferenceIds($consignments, &$key)
    {
        $referenceIds = [];
        foreach ($consignments as $consignment) {
            if ($consignment->getReferenceId()) {
                $referenceIds[] = $consignment->getReferenceId();
                $key            = $consignment->getApiKey();
            }
        }

        return $referenceIds;
    }

    /**
     * @param MyParcelCurl $request
     */
    private function setResult($request)
    {
        $response = $request->read();
        if (preg_match("/^%PDF-1./", $response)) {
            $this->result = $response;
        } else {
            $this->result = json_decode($response, true);

            if ($response === false) {
                $this->error = $request->getError();
            }
            $this
                ->checkMyParcelErrors();
        }
    }

    /**
     * @return MyParcelCurl
     */
    private function instantiateCurl()
    {
        return (new MyParcelCurl())
            ->setConfig([
                'header'  => 0,
                'timeout' => 60,
            ])
            ->addOptions([
                CURLOPT_POST           => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_AUTOREFERER    => true,
            ]);
    }
}
