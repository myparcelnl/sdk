<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Exception\ApiException;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

/**
 * A services to check if the API-key is correct.
 *
 * @since v1.1.7
 */
class CheckApiKeyService
{
    use HasApiKey;

    /**
     * @return bool
     */
    public function apiKeyIsCorrect(): bool
    {
        try {
            $request   = (new MyParcelRequest());
            $userAgent = $request->getUserAgentFromComposer();
            $request
                ->setUserAgents($userAgent)
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
