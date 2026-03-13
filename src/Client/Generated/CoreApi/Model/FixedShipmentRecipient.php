<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\CoreApi\Model;

use MyParcelNL\Sdk\Client\Generated\CoreApi\ObjectSerializer;

/**
 * Temporary override for ShipmentDefsShipmentRecipient.
 *
 * Fixes the following bugs in the generated parent class:
 * - setStreet() / listInvalidProperties(): broken preg_match pattern `"\D+"` (missing regex delimiters)
 * - setEmail(): incorrectly restricts email to empty string only (enum with single value `""`)
 * - listInvalidProperties(): same email enum check on read-path
 *
 * Registered via typeMappings in openapi/coreapi.yaml so that ObjectSerializer
 * deserializes into this class instead of the buggy generated parent.
 *
 * @todo remove once the upstream spec/codegen fix for the street pattern is available
 *       and the client is regenerated. Also remove the typeMappings + importMappings
 *       entries from openapi/coreapi.yaml.
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
     * Removes the broken email enum restriction that only allows empty string.
     * The generated spec incorrectly defines email as an enum with `""` as the
     * only allowed value. This override accepts any string (or null).
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
     * Overrides the broken preg_match pattern in the street validation check
     * and removes the incorrect email enum validation.
     */
    public function listInvalidProperties()
    {
        // Suppress E_WARNING from parent's broken preg_match("\D+", ...)
        $invalidProperties = @parent::listInvalidProperties();

        // Remove the broken street pattern validation and email enum validation added by parent
        $invalidProperties = array_filter($invalidProperties, static function (string $msg): bool {
            if (false !== strpos($msg, "must be conform to the pattern \\D+")) {
                return false;
            }
            if (false !== strpos($msg, "for 'email', must be one of")) {
                return false;
            }

            return true;
        });

        // Re-validate street with corrected pattern
        if (! is_null($this->container['street']) && ! preg_match("/\\D+/", $this->container['street'])) {
            $invalidProperties[] = "invalid value for 'street', must be conform to the pattern /\\D+/.";
        }

        return array_values($invalidProperties);
    }
}
