<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\IamApi\Model;

/**
 * Temporary override for Principal.
 *
 * The IAM spec defines Principal as a discriminator-based union:
 * - type=SHOP -> PrincipalShop -> role=RoleShop -> shopIds max 1
 * - type=USER -> PrincipalUser -> role=RoleUser -> shopIds max 500
 *
 * The generated PHP parent incorrectly hardcodes `role` to RoleUser, which
 * causes valid SHOP responses (for example SHOP_DEFAULT) to fail during
 * deserialization.
 *
 * Registered via typeMappings in openapi/iam.yaml so that ObjectSerializer
 * deserializes into this class instead of the broken generated parent.
 *
 * @todo remove once the upstream spec/codegen output for Principal is fixed
 *       and the IAM client is regenerated.
 */
final class FixedPrincipal extends Principal
{
    /**
     * Re-apply the discriminator-dependent fields through the override setters.
     *
     * The generated parent constructor writes raw values into the container and
     * then overwrites `type` with the model name. Re-applying the input keeps
     * manual construction aligned with deserialization behaviour.
     *
     * @param mixed[]|null $data
     */
    public function __construct(?array $data = null)
    {
        parent::__construct($data);

        if (null === $data) {
            return;
        }

        if (array_key_exists('type', $data)) {
            $this->setType($data['type']);
        }

        if (array_key_exists('role', $data)) {
            $this->setRole($data['role']);
        }

        if (array_key_exists('shop_ids', $data)) {
            $this->setShopIds($data['shop_ids']);
        }
    }

    /**
     * Override the generated parent type map: on the shared Principal parent,
     * `role` must remain a plain string until we know whether the principal is
     * a SHOP or USER.
     *
     * @return array<string, string>
     */
    public static function openAPITypes()
    {
        $types = parent::openAPITypes();
        $types['role'] = 'string';

        return $types;
    }

    /**
     * Gets role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->container['role'];
    }

    /**
     * Sets role.
     *
     * @param string $role
     *
     * @return self
     */
    public function setRole($role)
    {
        if (is_null($role)) {
            throw new \InvalidArgumentException('non-nullable role cannot be null');
        }

        $role = (string) $role;
        $type = $this->container['type'] ?? null;

        $this->assertRoleMatchesType($role, is_string($type) ? $type : null);

        $this->container['role'] = $role;

        return $this;
    }

    /**
     * Sets type.
     *
     * Re-validates any existing role/shopIds against the selected branch.
     *
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        parent::setType($type);

        $role = $this->container['role'] ?? null;
        if (is_string($role)) {
            $this->assertRoleMatchesType($role, (string) $type);
        }

        $shopIds = $this->container['shop_ids'] ?? null;
        if (is_array($shopIds)) {
            $this->assertShopIdsMatchType($shopIds, (string) $type);
        }

        return $this;
    }

    /**
     * Sets shop_ids.
     *
     * @param string[] $shop_ids
     *
     * @return self
     */
    public function setShopIds($shop_ids)
    {
        if (is_null($shop_ids)) {
            throw new \InvalidArgumentException('non-nullable shop_ids cannot be null');
        }

        if ((count($shop_ids) > 500)) {
            throw new \InvalidArgumentException('invalid value for $shop_ids when calling Principal., number of items must be less than or equal to 500.');
        }
        if ((count($shop_ids) < 1)) {
            throw new \InvalidArgumentException('invalid length for $shop_ids when calling Principal., number of items must be greater than or equal to 1.');
        }

        $type = $this->container['type'] ?? null;
        $this->assertShopIdsMatchType($shop_ids, is_string($type) ? $type : null);

        $this->container['shop_ids'] = $shop_ids;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * Adds the missing discriminator-dependent role/shopIds validation that the
     * generated parent cannot express because it incorrectly treats role as
     * RoleUser only and applies USER shopIds limits to both branches.
     *
     * @return string[]
     */
    public function listInvalidProperties()
    {
        $invalidProperties = parent::listInvalidProperties();

        $type = $this->container['type'] ?? null;
        $role = $this->container['role'] ?? null;
        $shopIds = $this->container['shop_ids'] ?? null;

        if (is_string($type) && is_string($role) && ! $this->isRoleAllowedForType($role, $type)) {
            $invalidProperties[] = sprintf(
                "invalid value '%s' for 'role' when type is '%s'",
                $role,
                $type
            );
        }

        if (is_string($type) && is_array($shopIds) && self::TYPE_SHOP === $type && count($shopIds) > 1) {
            $invalidProperties[] = "invalid value for 'shop_ids', number of items must be less than or equal to 1 when type is 'SHOP'.";
        }

        return array_values(array_unique($invalidProperties));
    }

    private function assertRoleMatchesType(string $role, ?string $type): void
    {
        if (null === $type) {
            return;
        }

        if (! $this->isRoleAllowedForType($role, $type)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value '%s' for 'role' when type is '%s'",
                    $role,
                    $type
                )
            );
        }
    }

    private function assertShopIdsMatchType(array $shopIds, ?string $type): void
    {
        if (null === $type) {
            return;
        }

        if (self::TYPE_SHOP === $type && count($shopIds) > 1) {
            throw new \InvalidArgumentException(
                "Invalid value for 'shop_ids' when type is 'SHOP', number of items must be less than or equal to 1."
            );
        }
    }

    private function isRoleAllowedForType(string $role, string $type): bool
    {
        return in_array($role, self::getAllowableRoleValuesForType($type), true);
    }

    /**
     * @return string[]
     */
    private static function getAllowableRoleValuesForType(string $type): array
    {
        switch ($type) {
            case self::TYPE_SHOP:
                return RoleShop::getAllowableEnumValues();

            case self::TYPE_USER:
                return RoleUser::getAllowableEnumValues();

            default:
                return [];
        }
    }
}
