<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Exception\ApiException;
use MyParcelNL\Sdk\Model\MyParcelRequest;

/**
 * A service to check if the API key is correct.
 */
class CheckApiKeyWebService extends AbstractWebService
{
    /**
     * @return bool
     */
    public function apiKeyIsCorrect(): bool
    {
        try {
            $request   = (new MyParcelRequest());
            $userAgent = $request->getUserAgentFromComposer();
            $request->setUserAgents($userAgent)
                ->setRequestParameters($this->getApiKey())
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
