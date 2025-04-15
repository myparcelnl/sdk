<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Concerns;

use Exception;

trait HasApiKey
{
    /**
     * @var string|null
     */
    protected ?string $apiKey = null;

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @throws \Exception
     */
    public function ensureHasApiKey(): string
    {
        if (! isset($this->apiKey) || '' === trim($this->apiKey)) {
            throw new Exception('API key is missing. Please use setApiKey(string) first.');
        }

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
