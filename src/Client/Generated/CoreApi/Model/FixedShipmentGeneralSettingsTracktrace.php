<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\CoreApi\Model;

/**
 * Temporary override for RefShipmentGeneralSettingsTracktrace.
 *
 * Fixes the incorrect from_address_email enum restriction that only allows empty
 * string. Live shipment responses can contain the account sender email here.
 *
 * Registered via typeMappings in openapi/coreapi.yaml so that ObjectSerializer
 * deserializes into this class instead of the buggy generated parent.
 *
 * @todo remove once the upstream spec/codegen fix for the from_address_email enum
 *       is available and the client is regenerated. Also remove the typeMappings
 *       entry from openapi/coreapi.yaml.
 */
final class FixedShipmentGeneralSettingsTracktrace extends RefShipmentGeneralSettingsTracktrace
{
    /**
     * {@inheritDoc}
     *
     * Removes the broken from_address_email enum restriction.
     */
    public function setFromAddressEmail($from_address_email)
    {
        if (is_null($from_address_email)) {
            $this->openAPINullablesSetToNull[] = 'from_address_email';
        } else {
            $index = array_search('from_address_email', $this->openAPINullablesSetToNull);
            if ($index !== false) {
                unset($this->openAPINullablesSetToNull[$index]);
                $this->openAPINullablesSetToNull = array_values($this->openAPINullablesSetToNull);
            }
        }

        $this->container['from_address_email'] = $from_address_email;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * Removes the incorrect from_address_email enum validation from the parent.
     */
    public function listInvalidProperties()
    {
        $invalidProperties = parent::listInvalidProperties();

        $invalidProperties = array_filter($invalidProperties, static function (string $msg): bool {
            return false === strpos($msg, "for 'from_address_email', must be one of");
        });

        return array_values($invalidProperties);
    }
}
