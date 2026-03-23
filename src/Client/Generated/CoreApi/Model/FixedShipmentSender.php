<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\CoreApi\Model;

/**
 * Temporary override for ShipmentDefsShipmentSender.
 *
 * Fixes the incorrect email enum restriction that only allows empty string.
 * The generated spec incorrectly defines email as an enum with `""` as the
 * only allowed value.
 *
 * Registered via typeMappings in openapi/coreapi.yaml so that ObjectSerializer
 * deserializes into this class instead of the buggy generated parent.
 *
 * @todo remove once the upstream spec/codegen fix for the email enum is available
 *       and the client is regenerated. Also remove the typeMappings + importMappings
 *       entries from openapi/coreapi.yaml.
 */
final class FixedShipmentSender extends ShipmentDefsShipmentSender
{
    /**
     * {@inheritDoc}
     *
     * Removes the broken email enum restriction that only allows empty string.
     */
    public function setEmail($email)
    {
        if (is_null($email)) {
            $this->openAPINullablesSetToNull[] = 'email';
        } else {
            $index = array_search('email', $this->openAPINullablesSetToNull);
            if ($index !== false) {
                unset($this->openAPINullablesSetToNull[$index]);
                $this->openAPINullablesSetToNull = array_values($this->openAPINullablesSetToNull);
            }
        }

        $this->container['email'] = $email;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * Removes the incorrect email enum validation from the parent.
     */
    public function listInvalidProperties()
    {
        $invalidProperties = parent::listInvalidProperties();

        $invalidProperties = array_filter($invalidProperties, static function (string $msg): bool {
            return false === strpos($msg, "for 'email', must be one of");
        });

        return array_values($invalidProperties);
    }
}
