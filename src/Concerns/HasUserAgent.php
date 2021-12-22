<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Concerns;

trait HasUserAgent
{
    /**
     * @var array
     */
    private $userAgent = [];

    /**
     * @return array
     */
    public function getUserAgent(): array
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    public function getUserAgentHeader(): string
    {
        $userAgentStrings = [];
        $userAgents = array_merge(
            $this->getUserAgent(),
            $this->getUserAgentFromComposer(),
            $this->getUserAgentFromPhp()
        );

        foreach ($userAgents as $key => $value) {
            $userAgentStrings[] = $key.'/'.$value;
        }

        return implode(' ', $userAgentStrings);
    }

    /**
     * @param  string      $platform
     * @param  string|null $version
     *
     * @return self
     */
    public function setUserAgent(string $platform, ?string $version): self
    {
        $this->userAgent[$platform] = $version;

        return $this;
    }

    /**
     * @param  array $userAgentMap
     *
     * @return self
     */
    public function setUserAgents(array $userAgentMap): self
    {
        $this->userAgent = $userAgentMap;

        return $this;
    }

    /**
     * Get version of SDK from composer file.
     *
     * @return array
     */
    public function getUserAgentFromComposer(): array
    {
        $composerData = $this->getComposerContents();

        if ($composerData && ! empty($composerData['name'])
            && 'myparcelnl/sdk' === $composerData['name']
            && ! empty($composerData['version'])) {
            $version = str_replace('v', '', $composerData['version']);
        } else {
            $version = 'unknown';
        }

        return ['MyParcelNL-SDK' => $version];
    }

    /**
     * @return self
     */
    public function resetUserAgent(): self
    {
        $this->userAgent = [];

        return $this;
    }

    /**
     * @return array
     */
    protected function getUserAgentFromPhp(): array
    {
        return ['php' => PHP_VERSION];
    }

    /**
     * Get composer.json.
     *
     * @return array|null
     */
    private function getComposerContents(): ?array
    {
        $composerLocations = [
            __DIR__.'/../../vendor/myparcelnl/sdk/composer.json',
            __DIR__.'/../../composer.json',
        ];

        foreach ($composerLocations as $composerFile) {
            if (file_exists($composerFile)) {
                return json_decode(file_get_contents($composerFile), true);
            }
        }

        return null;
    }
}
