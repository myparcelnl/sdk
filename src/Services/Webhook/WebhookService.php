<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Webhook;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefWebhookWebhookV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookV11;
use MyParcelNL\Sdk\Concerns\CreatesCoreApiClient;
use UnexpectedValueException;

/**
 * Manages webhook subscriptions through the Core API client.
 *
 * `getAll()` and `getById()` return the generated models, with public properties such as `hook` and `url`.
 */
final class WebhookService
{
    use CreatesCoreApiClient;

    public const HOOK_SHIPMENT_STATUS_CHANGE             = 'shipment_status_change';
    public const HOOK_SHIPMENT_LABEL_CREATED             = 'shipment_label_created';
    public const HOOK_ORDER_STATUS_CHANGE                = 'order_status_change';
    public const HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED = 'shop_carrier_accessibility_updated';
    public const HOOK_SHOP_UPDATED                       = 'shop_updated';

    /**
     * Subscribe to a webhook event.
     *
     * @param string $hook One of the HOOK_* constants.
     * @param string $url  The callback URL to receive events.
     *
     * @return int The created subscription ID.
     */
    public function subscribe(string $hook, string $url): int
    {
        $webhook       = new RefWebhookWebhookV11();
        $webhook->hook = $hook;
        $webhook->url  = $url;

        $data                       = new WebhooksPostWebhookSubscriptionsRequestV11Data();
        $data->webhookSubscriptions = [$webhook];

        $request       = new WebhooksPostWebhookSubscriptionsRequestV11();
        $request->data = $data;

        $response = $this->api()->postWebhookSubscriptions(
            requestBody: $request
        );

        $ids = $response->data->ids ?? [];

        if (empty($ids)) {
            throw new \RuntimeException('Unexpected response: no subscription IDs returned.');
        }

        return $ids[0]->id;
    }

    /**
     * Unsubscribe (delete) one or more webhook subscriptions by ID.
     *
     * @param int ...$subscriptionIds
     */
    public function unsubscribe(int ...$subscriptionIds): void
    {
        if (empty($subscriptionIds)) {
            return;
        }

        $ids = implode(';', $subscriptionIds);

        $this->api()->deleteWebhookSubscriptions(ids: $ids);
    }

    /**
     * List all webhook subscriptions, optionally filtered by hook type.
     *
     * @param string|null $hook Filter by hook type (one of the HOOK_* constants). Null or an empty string gives all subscriptions.
     *
     * @return WebhooksResponsesWebhookV11[]
     *
     * @throws UnexpectedValueException When the generated client returns another model than the v1.1 subscriptions.
     */
    public function getAll(?string $hook = null): array
    {
        $response = $this->api()->getWebhookSubscriptions(
            queryParameters: null !== $hook && '' !== $hook ? ['hook' => $hook] : []
        );

        return self::extractSubscriptions($response);
    }

    /**
     * Get webhook subscriptions by their IDs.
     *
     * @param int ...$subscriptionIds
     *
     * @return WebhooksResponsesWebhookV11[]
     *
     * @throws UnexpectedValueException When the generated client returns another model than the v1.1 subscriptions.
     */
    public function getById(int ...$subscriptionIds): array
    {
        if (empty($subscriptionIds)) {
            return [];
        }

        $ids = implode(';', $subscriptionIds);

        $response = $this->api()->getWebhookSubscriptionsById(
            ids: $ids
        );

        return self::extractSubscriptions($response);
    }

    /**
     * @return WebhooksResponsesWebhookV11[]
     *
     * @throws UnexpectedValueException When the generated client returns another model than the v1.1
     *                                  subscriptions, so a caller does not take it for an empty list.
     */
    private static function extractSubscriptions(mixed $response): array
    {
        if (! $response instanceof WebhooksResponsesWebhookSubscriptionsV11) {
            throw new UnexpectedValueException('The webhook API returned an unexpected response type: ' . get_debug_type($response));
        }

        return $response->data->webhookSubscriptions ?? [];
    }
}
