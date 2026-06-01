<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Web;

use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\MyParcelRequest;

/**
 * @internal Legacy web service — no generated client equivalent yet.
 */
abstract class AbstractWebService
{
    use HasApiKey;
    use HasUserAgent;

    /**
     * @return \MyParcelNL\Sdk\Model\MyParcelRequest
     */
    protected function createRequest(): MyParcelRequest
    {
        return (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setApiKey($this->getApiKey());
    }
}
