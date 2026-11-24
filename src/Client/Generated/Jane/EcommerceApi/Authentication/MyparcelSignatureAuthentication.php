<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Authentication;

class MyparcelSignatureAuthentication implements \Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin
{
    private $apiKey;
    public function __construct(string $apiKey)
    {
        $this->{'apiKey'} = $apiKey;
    }
    public function decorate(string $method, string $url, array &$options): void
    {
        $options['headers']['Signature'] = $this->{'apiKey'};
    }
    public function getScope(): string
    {
        return 'myparcelSignature';
    }
}