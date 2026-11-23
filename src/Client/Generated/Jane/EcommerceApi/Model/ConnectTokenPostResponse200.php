<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class ConnectTokenPostResponse200 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var string
     */
    public string $accessToken;
    /**
     * A Universally Unique Identifier (UUID).
     *
     * @var string
     */
    public string $connectionId;
    /**
     * @var ConnectTokenPostResponse200TokenType|string
     */
    public ConnectTokenPostResponse200TokenType|string $tokenType;
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
        return ['accessToken' => 'accessToken', 'connectionId' => 'connectionId', 'tokenType' => 'tokenType', 'expiresIn' => 'expiresIn', 'refreshExpiresIn' => 'refreshExpiresIn', 'scope' => 'scope'];
    }
}