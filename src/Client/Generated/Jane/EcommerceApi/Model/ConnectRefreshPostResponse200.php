<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectRefreshPostResponse200 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $accessToken;
    /**
     * @var ConnectRefreshPostResponse200TokenType|string
     */
    public ConnectRefreshPostResponse200TokenType|string $tokenType;
    /**
     * @var int
     */
    public int $expiresIn;
    /**
     * @var int
     */
    public int $refreshExpiresIn;
    /**
     * @var string
     */
    public string $scope;
    public function definedProperties(): array
    {
        return ['accessToken' => 'accessToken', 'tokenType' => 'tokenType', 'expiresIn' => 'expiresIn', 'refreshExpiresIn' => 'refreshExpiresIn', 'scope' => 'scope'];
    }
}