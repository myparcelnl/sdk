<?php declare(strict_types=1);
/**
 * A services to check if the API-key is correct
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v1.1.7
 */

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

class CheckApiKeyService
{
    private $api_key;

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param mixed $api_key
     * @return CheckApiKeyService
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;

        return $this;
    }

    public function apiKeyIsCorrect()
    {
        try {
            $request = (new MyParcelRequest());

            $userAgent = $request->getUserAgentFromComposer();
            $request
                ->setUserAgent($userAgent)
                ->setRequestParameters(
                    $this->getApiKey(),
                    null,
                    MyParcelRequest::REQUEST_HEADER_RETRIEVE_SHIPMENT
                )
                ->sendRequest('GET');

            if ($request->getResult() === null) {
                throw new ApiException('Unable to connect to MyParcel.');
            }
        } catch (\Exception $exception) {
            if (strpos($exception->getMessage(), 'Access Denied') > 1) {
                return false;
            }
        }

        return true;
    }
}
