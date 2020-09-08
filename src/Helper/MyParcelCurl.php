<?php declare(strict_types=1);
/**
 * Curl to use in the api
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

namespace MyParcelNL\Sdk\src\Helper;

/**
 * Class MyParcelCurl
 */
class MyParcelCurl
{
    /**
     * Parameters array
     *
     * @var array
     */
    protected $_config = [];

    /**
     * Curl handle
     *
     * @var resource
     */
    protected $_resource;

    /**
     * Allow parameters
     *
     * @var array
     */
    protected $_allowedParams = [
        'timeout'      => CURLOPT_TIMEOUT,
        'maxredirects' => CURLOPT_MAXREDIRS,
        'proxy'        => CURLOPT_PROXY,
        'ssl_cert'     => CURLOPT_SSLCERT,
        'userpwd'      => CURLOPT_USERPWD
    ];

    /**
     * Array of CURL options
     *
     * @var array
     */
    protected $_options = [];

    /**
     * Set array of additional cURL options
     *
     * @param array $options
     *
     * @return MyParcelCurl
     */
    public function setOptions(array $options = [])
    {
        $this->_options = $options;

        return $this;
    }

    /**
     * Add additional options list to curl
     *
     * @param array $options
     *
     * @return MyParcelCurl
     */
    public function addOptions(array $options)
    {
        $this->_options = $options + $this->_options;

        return $this;
    }

    /**
     * Set the configuration array for the adapter
     *
     * @param array $config
     *
     * @return MyParcelCurl
     */
    public function setConfig($config = [])
    {
        $this->_config = $config;

        return $this;
    }

    /**
     * Send request to the remote server
     *
     * @param string $method
     * @param string $url
     * @param array  $headers
     * @param string $body
     *
     * @return string Request as text
     */
    public function write($method, $url, $headers = [], $body = '')
    {
        if ($url instanceof Zend_Uri_Http) {
            $url = $url->getUri();
        }
        $this->_applyConfig();

        $header  = isset($this->_config['header']) ? $this->_config['header'] : true;
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => $header
        ];
        if ($method == 'POST') {
            $options[CURLOPT_POST]       = true;
            $options[CURLOPT_POSTFIELDS] = $body;
        } elseif ($method == 'GET') {
            $options[CURLOPT_HTTPGET] = true;
        }
        if (is_array($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($this->_getResource(), $options);

        return $body;
    }

    /**
     * Read response from server
     *
     * @return string
     */
    public function read()
    {
        $resource = $this->_getResource();
        $response = curl_exec($resource);

        // Check the return value of curl_exec()
        if ($response === false) {
            throw new \Exception(curl_error($resource), curl_errno($resource));
        }

        // Remove 100 and 101 responses headers
        while ($this->extractCode($response) == 100 || $this->extractCode($response) == 101) {
            $response = preg_split('/^\r?$/m', $response, 2);
            $response = trim($response[1]);
        }

        if (stripos($response, "Transfer-Encoding: chunked\r\n")) {
            $response = str_ireplace("Transfer-Encoding: chunked\r\n", '', $response);
        }

        return $response;
    }

    /**
     * Close the connection to the server
     */
    public function close()
    {
        curl_close($this->_getResource());
        $this->_resource = null;

        return $this;
    }

    /**
     * Get last error number
     *
     * @return int
     */
    public function getErrno()
    {
        return curl_errno($this->_getResource());
    }

    /**
     * Get string with last error for the current session
     *
     * @return string
     */
    public function getError()
    {
        return curl_error($this->_getResource());
    }

    /**
     * Get information regarding a specific transfer
     *
     * @param int $opt CURLINFO option
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
     * Set User Agent
     *
     * @param string $agent
     *
     * @return bool
     */
    public function setUserAgent($agent)
    {
        return curl_setopt($this->_getResource(), CURLOPT_USERAGENT, (string) $agent);
    }

    /**
     * curl_multi_* requests support
     *
     * @param array $urls
     * @param array $options
     *
     * @return array
     */
    public function multiRequest($urls, $options = [])
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
     * Apply current configuration array to transport resource
     *
     * @return MyParcelCurl
     */
    protected function _applyConfig()
    {
        curl_setopt_array($this->_getResource(), $this->_options);

        if (empty($this->_config)) {
            return $this;
        }

        $verifyPeer = isset($this->_config['verifypeer']) ? $this->_config['verifypeer'] : 0;
        curl_setopt($this->_getResource(), CURLOPT_SSL_VERIFYPEER, $verifyPeer);

        $verifyHost = isset($this->_config['verifyhost']) ? $this->_config['verifyhost'] : 0;
        curl_setopt($this->_getResource(), CURLOPT_SSL_VERIFYHOST, $verifyHost);

        foreach ($this->_config as $param => $curlOption) {
            if (array_key_exists($param, $this->_allowedParams)) {
                curl_setopt($this->_getResource(), $this->_allowedParams[$param], $this->_config[$param]);
            }
        }

        return $this;
    }

    /**
     * Returns a cURL handle on success
     *
     * @return resource
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = curl_init();
        }

        return $this->_resource;
    }

    /**
     * Extract the response code from a response string
     *
     * @param string $response_str
     *
     * @return int
     */
    private static function extractCode($response_str)
    {
        preg_match("|^HTTP/[\d\.x]+ (\d+)|", $response_str, $m);

        if (isset($m[1])) {
            return (int) $m[1];
        } else {
            return false;
        }
    }
}
