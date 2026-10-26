<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\OrderApi\Runtime\AdditionalPropertiesInterface;
class AssignToUserPostBodyItem implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * The unique identifier of the order.
     *
     * @var string
     */
    public string $id;
    /**
     * User to assign to these orders.
     *
     * @var string
     */
    public string $userId;
    public function definedProperties(): array
    {
        return ['id' => 'id', 'userId' => 'userId'];
    }
}