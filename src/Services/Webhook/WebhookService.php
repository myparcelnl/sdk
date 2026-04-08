<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Webhook;

use MyParcelNL\Sdk\Client\Generated\CoreApi\Api\WebhookApi;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\RefWebhookWebhookV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\CoreApi\Model\WebhooksResponsesWebhookV11;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\WebhookApiFactory;

/**
 * Service for managing webhook subscriptions via the generated WebhookApi.
 *
 * Replaces the legacy AbstractWebhookWebService and its 5 concrete subclasses.
 */
final class WebhookService
{
    use HasUserAgent;

    public const HOOK_SHIPMENT_STATUS_CHANGE             = 'shipment_status_change';
    public const HOOK_SHIPMENT_LABEL_CREATED             = 'shipment_label_created';
    public const HOOK_ORDER_STATUS_CHANGE                = 'order_status_change';
    public const HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED = 'shop_carrier_accessibility_updated';
    public const HOOK_SHOP_UPDATED                       = 'shop_updated';

    private WebhookApi $api;

    public function __construct(string $apiKey, ?WebhookApi $api = null, ?string $host = null)
    {
        $this->api = $api ?? WebhookApiFactory::make($apiKey, $host);
    }

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
        $webhook = new RefWebhookWebhookV11([
            'hook' => $hook,
            'url'  => $url,
        ]);

        $data = new WebhooksPostWebhookSubscriptionsRequestV11Data([
            'webhook_subscriptions' => [$webhook],
        ]);

        $request = new WebhooksPostWebhookSubscriptionsRequestV11([
            'data' => $data,
        ]);

        $response = $this->api->postWebhookSubscriptions(
            $this->getUserAgentHeader(),
            $request
        );

        return $response->getData()->getIds()[0]->getId();
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

        $this->api->deleteWebhookSubscriptions($ids, $this->getUserAgentHeader());
    }

    /**
     * List all webhook subscriptions, optionally filtered by hook type.
     *
     * @param string|null $hook Filter by hook type (one of the HOOK_* constants).
     *
     * @return WebhooksResponsesWebhookV11[]
     */
    public function getAll(?string $hook = null): array
    {
        $response = $this->api->getWebhookSubscriptions(
            $this->getUserAgentHeader(),
            $hook
        );

        return $response->getData()->getWebhookSubscriptions() ?? [];
    }

    /**
     * Get webhook subscriptions by their IDs.
     *
     * @param int ...$subscriptionIds
     *
     * @return WebhooksResponsesWebhookV11[]
     */
    public function getById(int ...$subscriptionIds): array
    {
        if (empty($subscriptionIds)) {
            return [];
        }

        $ids = implode(';', $subscriptionIds);

        $response = $this->api->getWebhookSubscriptionsById(
            $ids,
            $this->getUserAgentHeader()
        );

        return $response->getData()->getWebhookSubscriptions() ?? [];
    }
}
