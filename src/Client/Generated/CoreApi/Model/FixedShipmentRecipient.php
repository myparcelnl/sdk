<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;

/**
 * Temporary override for ShipmentDefsShipmentRecipient.
 *
 * Fixes the broken street preg_match pattern `"\D+"` (missing regex delimiters) in
 * the generated parent class. The email enum bug is handled generically by the
 * pseudo-enum guards in ObjectSerializer.mustache / model_generic.mustache.
 *
 * Registered via typeMappings in openapi/coreapi.yaml so that ObjectSerializer
 * deserializes into this class instead of the buggy generated parent.
 *
 * @todo remove once the upstream spec/codegen fix for the street pattern is available
 *       and the client is regenerated. Also remove the typeMappings entry from
 *       openapi/coreapi.yaml.
 */
final class FixedShipmentRecipient extends ShipmentDefsShipmentRecipient
{
    /**
     * {@inheritDoc}
     *
     * Overrides the broken preg_match pattern: original uses "\D+" without
     * regex delimiters, this version uses "/\D+/".
     *
     * Note: accesses the protected $openAPINullablesSetToNull property directly
     * because the parent getter/setter are private and not accessible from child classes.
     */
    public function setStreet($street)
    {
        if (is_null($street)) {
            $this->openAPINullablesSetToNull[] = 'street';
        } else {
            $index = array_search('street', $this->openAPINullablesSetToNull);
            if ($index !== false) {
                unset($this->openAPINullablesSetToNull[$index]);
                $this->openAPINullablesSetToNull = array_values($this->openAPINullablesSetToNull);
            }
        }
        if (! is_null($street) && (mb_strlen($street) > 40)) {
            throw new \InvalidArgumentException('invalid length for $street when calling ShipmentDefsShipmentRecipient., must be smaller than or equal to 40.');
        }
        if (! is_null($street) && (mb_strlen($street) < 1)) {
            throw new \InvalidArgumentException('invalid length for $street when calling ShipmentDefsShipmentRecipient., must be bigger than or equal to 1.');
        }
        if (! is_null($street) && (! preg_match("/\\D+/", ObjectSerializer::toString($street)))) {
            throw new \InvalidArgumentException("invalid value for \$street when calling ShipmentDefsShipmentRecipient., must conform to the pattern /\\D+/.");
        }

        $this->container['street'] = $street;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * Overrides the broken preg_match pattern in the street validation check.
     */
    public function listInvalidProperties()
    {
        // Suppress E_WARNING from parent's broken preg_match("\D+", ...)
        $invalidProperties = @parent::listInvalidProperties();

        // Remove the broken street pattern validation added by parent
        $invalidProperties = array_filter($invalidProperties, static function (string $msg): bool {
            return false === strpos($msg, "must be conform to the pattern \\D+");
        });

        // Re-validate street with corrected pattern
        if (! is_null($this->container['street']) && ! preg_match("/\\D+/", $this->container['street'])) {
            $invalidProperties[] = "invalid value for 'street', must be conform to the pattern /\\D+/.";
        }

        return array_values($invalidProperties);
    }
}
