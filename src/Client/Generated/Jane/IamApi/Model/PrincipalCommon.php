<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Runtime\AdditionalPropertiesInterface;
class PrincipalCommon implements AdditionalPropertiesInterface
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
     * @var list<Feature|string>
     */
    public array $features;
    /**
     * @var list<FulfilmentPlatform|string>
     */
    public array $fulfilmentPlatforms;
    /**
     * A shop principal identifier.
     *
     * @var string
     */
    public $impersonator;
    public function definedProperties(): array
    {
        return ['accountId' => 'accountId', 'mfaEnabled' => 'mfaEnabled', 'platform' => 'platform', 'features' => 'features', 'fulfilmentPlatforms' => 'fulfilmentPlatforms', 'impersonator' => 'impersonator'];
    }
}