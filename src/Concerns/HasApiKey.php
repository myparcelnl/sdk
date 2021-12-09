<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

use Exception;

/**
 * @property string|null $apiKey
 */
trait HasApiKey
{
    /**
     * @var string|null
     */
    public $apiKey;

    /**
     * @throws \Exception
     */
    public function ensureHasApiKey(): string
    {
        $apiKey = $this->getApiKey();

        if (! $apiKey) {
            throw new Exception('API key is missing. Please use setApiKey(string) first.');
        }

        return $apiKey;
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEncodedApiKey(): string
    {
        return base64_encode($this->ensureHasApiKey());
    }

    /**
     * @param  string $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
