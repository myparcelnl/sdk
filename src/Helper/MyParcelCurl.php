<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

use Exception;

/**
 * Class MyParcelCurl.
 */
class MyParcelCurl
{
    /**
     * Allow parameters.
     *
     * @var array
     */
    protected $_allowedParams = [
        'timeout'      => CURLOPT_TIMEOUT,
        'maxredirects' => CURLOPT_MAXREDIRS,
        'proxy'        => CURLOPT_PROXY,
        'ssl_cert'     => CURLOPT_SSLCERT,
        'userpwd'      => CURLOPT_USERPWD,
    ];

    /**
     * Parameters array.
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Array of CURL options.
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Curl handle.
     *
     * @var resource
     */
    protected $_resource;

    private   $response;

    /**
     * Add additional options list to curl.
     *
     * @param  array $options
     *
     * @return self
     */
    public function addOptions(array $options): self
    {
        $this->_options = $options + $this->_options;

        return $this;
    }

    /**
     * Close the connection to the server.
     *
     * @return self
     */
    public function close(): self
    {
        curl_close($this->_getResource());
        $this->_resource = null;

        return $this;
    }

    /**
     * Get last error number.
     *
     * @return int
     */
    public function getErrno(): int
    {
        return curl_errno($this->_getResource());
    }

    /**
     * Get string with last error for the current session.
     *
     * @return string
     */
    public function getError(): string
    {
        return curl_error($this->_getResource());
    }

    /**
     * Get information regarding a specific transfer.
     *
     * @param  int $opt CURLINFO option
     *
     * @return mixed
     */
    public function getInfo($opt = 0)
    {
        if (! $opt) {
            return curl_getinfo($this->_getResource());
        }

        return curl_getinfo($this->_getResource(), $opt);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getResponse(): array
    {
        if ($this->response) {
            return $this->response;
        }

        $resource = $this->_getResource();

        $headers  = [];
        curl_setopt($resource, CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) use (&$headers) {
            return $this->handleHeaderLine($curl, $headerLine, $headers);
        });

        $response = curl_exec($resource);

        // Check the return value of curl_exec()
        if ($response === false) {
            $resource = $this->_getResource();
            throw new Exception(curl_error($resource), curl_errno($resource));
        }

        $code = self::extractCode($response);

        // Remove 100 and 101 responses headers
        if (in_array($code, [100, 101])) {
            $response = preg_split('/^\r?$/m', $response, 2);
            $response = trim($response[1]);
        }

        if (stripos($response, "Transfer-Encoding: chunked\r\n")) {
            $response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $response);
        }

        $this->response = [
            'response' => $response,
            'headers'  => $headers,
            'code'     => curl_getinfo($resource, CURLINFO_RESPONSE_CODE),
        ];

        return $this->response;
    }

    /**
     * curl_multi_* requests support.
     *
     * @param  array $urls
     * @param  array $options
     *
     * @return array
     */
    public function multiRequest($urls, $options = []): array
    {
        $handles = [];
        $result  = [];

        $multihandle = curl_multi_init();

        foreach ($urls as $key => $url) {
            $handles[$key] = curl_init();
            curl_setopt($handles[$key], CURLOPT_URL, $url);
            curl_setopt($handles[$key], CURLOPT_HEADER, 0);
            curl_setopt($handles[$key], CURLOPT_RETURNTRANSFER, 1);
            if (! empty($options)) {
                curl_setopt_array($handles[$key], $options);
            }
            curl_multi_add_handle($multihandle, $handles[$key]);
        }
        $process = null;
        do {
            curl_multi_exec($multihandle, $process);
            usleep(100);
        } while ($process > 0);

        foreach ($handles as $key => $handle) {
            $result[$key] = curl_multi_getcontent($handle);
            curl_multi_remove_handle($multihandle, $handle);
        }
        curl_multi_close($multihandle);

        return $result;
    }

    /**
     * @param $curl
     * @param $header
     * @param $headers
     *
     * @return int
     * @see https://stackoverflow.com/a/41135574/10225966
     */
    public function handleHeaderLine($curl, $header, &$headers): int
    {
        $length = strlen($header);
        $header = explode(':', $header, 2);

        if (count($header) < 2) {
            return $length;
        }

        /** @noinspection OffsetOperationsInspection */
        $headers[strtolower(trim($header[0]))][] = trim($header[1]);

        return $length;
    }

    /**
     * Read response from server.
     *
     * @return string
     * @throws \Exception
     */
    public function read(): string
    {
        $response = $this->getResponse();
        return $response['response'];
    }

    /**
     * Set the configuration array for the adapter.
     *
     * @param  array $config
     *
     * @return self
     */
    public function setConfig($config = []): self
    {
        $this->_config = $config;

        return $this;
    }

    /**
     * Set array of additional cURL options.
     *
     * @param  array $options
     *
     * @return self
     */
    public function setOptions(array $options = []): self
    {
        $this->_options = $options;

        return $this;
    }

    /**
     * Set User Agent.
     *
     * @param  string $agent
     *
     * @return bool
     */
    public function setUserAgent($agent)
    {
        return curl_setopt($this->_getResource(), CURLOPT_USERAGENT, (string) $agent);
    }

    /**
     * Send request to the remote server.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $headers
     * @param  string $body
     *
     * @return string Request as text
     */
    public function write($method, $url, $headers = [], $body = '')
    {
        $method = strtoupper($method);

        if (is_a($url, 'Zend_Uri_Http')) {
            $url = $url->getUri();
        }
        $this->_applyConfig();

        $header  = $this->_config['header'] ?? true;
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER         => $header,
            CURLOPT_FOLLOWLOCATION => 1,
        ];

        switch ($method) {
            case 'POST':
                $options[CURLOPT_POST]       = true;
                $options[CURLOPT_POSTFIELDS] = $body;
                break;
            case 'GET':
                $options[CURLOPT_HTTPGET] = true;
                break;
            default:
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                break;
        }

        if (is_array($headers)) {
            $curlHeaders = [];
            foreach ($headers as $key => $value) {
                $curlHeaders[] = $key . ': ' . $value;
            }

            $options[CURLOPT_HTTPHEADER] = $curlHeaders;
        }

        curl_setopt_array($this->_getResource(), $options);

        return $body;
    }

    /**
     * Apply current configuration array to transport resource.
     *
     * @return self
     */
    protected function _applyConfig(): self
    {
        curl_setopt_array($this->_getResource(), $this->_options);

        if (empty($this->_config)) {
            return $this;
        }

        $verifyPeer = $this->_config['verifypeer'] ?? 0;
        curl_setopt($this->_getResource(), 2, $verifyPeer);

        $verifyHost = $this->_config['verifyhost'] ?? 0;
        curl_setopt($this->_getResource(), 2, $verifyHost);

        foreach ($this->_config as $param => $curlOption) {
            if (array_key_exists($param, $this->_allowedParams)) {
                curl_setopt($this->_getResource(), $this->_allowedParams[$param], $this->_config[$param]);
            }
        }

        return $this;
    }

    /**
     * Returns a cURL handle on success.
     *
     * @return false|resource
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = curl_init();
        }

        return $this->_resource;
    }

    /**
     * Extract the response code from a response string.
     *
     * @param  string $response
     *
     * @return int
     */
    private static function extractCode(string $response)
    {
        preg_match("|^HTTP/[\d\.x]+ (\d+)|", $response, $m);

        if (isset($m[1])) {
            return (int) $m[1];
        }

        return false;
    }
}
