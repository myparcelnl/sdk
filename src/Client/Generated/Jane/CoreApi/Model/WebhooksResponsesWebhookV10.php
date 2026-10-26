<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Runtime\AdditionalPropertiesInterface;
class WebhooksResponsesWebhookV10 implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * @var int
     */
    public int $id;
    /**
     * @var int
     */
    public int $accountId;
    /**
     * @var int
     */
    public int $shopId;
    /**
     * @var string
     */
    public string $hook;
    /**
     * @var string
     */
    public string $url;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'accountId' => 'account_id', 'shopId' => 'shop_id', 'hook' => 'hook', 'url' => 'url'];
    }
}