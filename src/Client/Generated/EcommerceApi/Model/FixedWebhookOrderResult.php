<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Client\Generated\EcommerceApi\Model;

/**
 * Temporary override for WebhookOrdersPost202ResponseInner.
 *
 * POST /webhook/orders answers one result per order pushed, as a oneOf of three branches: an
 * object carrying nothing but `status: 202`, a ProblemDetailsClient, or a
 * ProblemDetailsInternalServerError. The generator cannot represent that and flattens all three
 * into one model, taking the union of their fields and their validation. Two things break:
 *
 * - `status` keeps only the last branch's enum, 500, so an accepted 202 and a client 400 both
 *   read as invalid.
 * - `type`, `title`, `detail`, `instance` and `errors` become required, so an accepted item can
 *   never validate: it carries none of them.
 *
 * This restores the branches. The status enum covers all three, and an accepted item is judged on
 * its status alone.
 *
 * Registered via typeMappings in openapi/ecommerce.yaml so that ObjectSerializer deserializes into
 * this class instead of the flattened generated parent.
 *
 * @todo remove once the generator can represent the oneOf, which openapiNormalizer's
 *       SIMPLIFY_ONEOF_ANYOF is meant to do and cannot on 7.12.0 for an OpenAPI 3.1 spec (see
 *       openapi/common.yaml). Also remove the typeMappings entry from openapi/ecommerce.yaml.
 */
final class FixedWebhookOrderResult extends WebhookOrdersPost202ResponseInner
{
    /**
     * MyParcel accepted the order.
     */
    public const STATUS_ACCEPTED = 202;

    /**
     * The statuses the three branches allow, read off the branches themselves so a regenerated
     * spec carries through.
     */
    private const BRANCH_STATUSES = [
        self::STATUS_ACCEPTED,
        ProblemDetailsClient::STATUS_NUMBER_400,
        ProblemDetailsInternalServerError::STATUS_NUMBER_500,
    ];

    /**
     * {@inheritDoc}
     *
     * The parent kept only the last branch's 500. listInvalidProperties() reads this through
     * $this, so widening it here also corrects the parent's own status check.
     */
    public function getStatusAllowableValues()
    {
        return self::BRANCH_STATUSES;
    }

    /**
     * {@inheritDoc}
     *
     * An accepted item is judged on its status alone. Everything else the parent demands belongs
     * to the two problem branches.
     */
    public function listInvalidProperties()
    {
        if (self::STATUS_ACCEPTED === $this->container['status']) {
            return [];
        }

        return parent::listInvalidProperties();
    }
}
