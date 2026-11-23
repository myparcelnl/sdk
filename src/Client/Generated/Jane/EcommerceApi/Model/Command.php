<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Model;

use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalAndPatternProperties;
use MyParcelNL\Sdk\Client\Generated\Jane\EcommerceApi\Runtime\AdditionalPropertiesInterface;
class Command implements AdditionalPropertiesInterface
{
    use AdditionalAndPatternProperties;
    /**
     * Identifies one command. An opaque string; do not parse it. The same value on every resend. Drop duplicates per (connectionId, commandId) for at least 14 days; it is also the replay protection.
     *
     * @var string
     */
    public string $commandId;
    /**
     * Must equal the connection id from /connect/token. Changes on reconnect.
     *
     * @var string
     */
    public string $connectionId;
    /**
     * The command name. Reply 404 to a name the plugin does not know.
     *
     * @var string
     */
    public string $type;
    public function definedProperties(): array
    {
        return ['commandId' => 'commandId', 'connectionId' => 'connectionId', 'type' => 'type'];
    }
}