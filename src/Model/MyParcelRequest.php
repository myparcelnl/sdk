<?php
/**
 * This model represents one request
 *
 * LICENSE: This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2016 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release 0.1.0
 */

namespace myparcelnl\sdk\Model;

use myparcelnl\sdk\Helper\MyParcelCurl;

class MyParcelRequest
{
    /**
     * @var string
     */
    private $api_key = '';
    private $header = '';
    private $body = '';
    private $url = '';
    private $type = '';
    private $error = null;
    private $result = null;

    /**
     * @return null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Sets the parameters for an API call based on a string with all required request parameters and the requested API
     * method.
     *
     * @param string $body
     * @param string $apiKey
     * @param string $requestType
     * @param string $requestHeader
     *
     * @return $this
     */
    public function setRequestParameters($body, $apiKey, $requestType = 'shipment', $requestHeader = '')
    {
        $this->api_key = $apiKey;
        $this->body = $body;
        $this->type = $requestType;

        $header[] = $requestHeader . 'charset=utf-8';
        $header[] = 'Authorization: basic ' . base64_encode($this->api_key);

        $this->header = $header;

        return $this;
    }

    /**
     * send the created request to MyParcel
     *
     * @param string $method
     * @param string $uri
     *
     * @return $this|false|array|string
     */
    public function sendRequest($uri = 'shipments', $method = 'POST')
    {
        if (!$this->checkConfigForRequest()) {
            return false;
        }

        //curl options
        $options = array(
            CURLOPT_POST           => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER    => true,
        );

        $config = array(
            'header'  => 0,
            'timeout' => 60,
        );

        //instantiate the curl adapter
        $request = new MyParcelCurl();
        //add the options
        foreach($options as $option => $value)
        {
            $request->addOption($option, $value);
        }

        $header = $this->header;

        //do the curl request
        if($method == 'POST'){

            //curl request string
            $body = $this->body;

            //complete request url
            $url = 'https://api.myparcel.nl/' . $uri;

            $request->setConfig($config)
                ->write('POST', $url, '1.1', $header, $body);
        } else {

            //complete request url
            $url  = 'https://api.myparcel.nl/';
            $url .= $uri;
            $url .= $this->body;

            $request->setConfig($config)
                ->write('GET', $url, '1.1', $header);
        }

        //read the response
        $response = $request->read();

        $aResult = json_decode($response, true);

        if(is_array($aResult)){

            //check if there are curl-errors
            if ($response === false) {
                $error              = $request->getError();
                $this->error = $error;
                return $this;
            }

            //check if the response has errors codes
            if(isset($aResult['errors'][0]['code'])){
                var_dump($aResult);
                if(key_exists('message', $aResult)){
                    $message = $aResult['message'];
                } else {
                    $message = $aResult['errors'][0]['message'];
                }
                $this->error = $aResult['errors'][0]['code'] . ' - ' . $message;
                $request->close();

                return $this;
            }
        }

        $this->result = $response;

        //close the server connection with MyParcel
        $request->close();

        return $this;
    }

    /**
     * Checks if all the requirements are set to send a request to MyParcel
     *
     * @return bool
     * @throws \Exception
     */
    private function checkConfigForRequest()
    {
        if(empty($this->api_key)){
            throw new \Exception('api_key not found');
        }

        if(empty($this->type)){
            throw new \Exception('requestType not found');
        }

        if(empty($this->body)){
            throw new \Exception('requestString not found');
        }

        return true;
    }
}