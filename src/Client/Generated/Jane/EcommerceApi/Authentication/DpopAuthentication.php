<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Authentication;

class DpopAuthentication implements \Jane\Component\OpenApiRuntime\Client\AuthenticationPlugin
{
    public function __construct()
    {
    }
    public function decorate(string $method, string $url, array &$options): void
    {
    }
    public function getScope(): string
    {
        return 'dpop';
    }
}