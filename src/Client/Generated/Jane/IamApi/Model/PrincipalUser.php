<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalPropertiesInterface;
class PrincipalUser implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the account.
     *
     * @var string
     */
    public string $accountId;
    /**
     * Whether the account of the principal has MFA enabled.
     *
     * @var bool
     */
    public bool $mfaEnabled;
    /**
     * The operating platform identifier.
     *
     * @var Platform|string
     */
    public Platform|string $platform;
    /**
     * The principal identifier.
     *
     * @var string
     */
    public $principal;
    /**
     * A collection of features.
     *
     * @var list<Feature|string>
     */
    public array $features;
    /**
     * A collection of fulfilment platforms.
     *
     * @var list<FulfilmentPlatform|string>
     */
    public array $fulfilmentPlatforms;
    /**
     * The principal identifier.
     *
     * @var string
     */
    public $impersonator;
    /**
     * The unique identifier of the user.
     *
     * @var string
     */
    public string $id;
    /**
     * The specific role assigned to a user.
     *
     * @var RoleUser|string
     */
    public RoleUser|string $role;
    /**
     * A collection of shop IDs that a user is allowed to access.
     *
     * @var list<string>
     */
    public array $shopIds;
    /**
     * The principal type.
     *
     * @var string
     */
    public string $type;
    public function definedProperties(): array
    {
        return ['accountId' => 'accountId', 'mfaEnabled' => 'mfaEnabled', 'platform' => 'platform', 'principal' => 'principal', 'features' => 'features', 'fulfilmentPlatforms' => 'fulfilmentPlatforms', 'impersonator' => 'impersonator', 'id' => 'id', 'role' => 'role', 'shopIds' => 'shopIds', 'type' => 'type'];
    }
}