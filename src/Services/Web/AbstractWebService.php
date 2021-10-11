<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web;

use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Concerns\HasUserAgent;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;

abstract class AbstractWebService
{
    use HasApiKey;
    use HasUserAgent;

    /**
     * @return \MyParcelNL\Sdk\src\Model\MyParcelRequest
     */
    protected function createRequest(): MyParcelRequest
    {
        return (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setApiKey($this->getApiKey());
    }
}
