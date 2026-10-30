<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Webhook;

use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Client;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\RefWebhookWebhookV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksPostWebhookSubscriptionsRequestV11Data;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesPostWebhookSubscriptions;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookSubscriptionsV11;
use MyParcelNL\Sdk\Client\Generated\Jane\CoreApi\Model\WebhooksResponsesWebhookV11;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Services\CoreApi\WebhookApiFactory;

/**
 * Service for managing webhook subscriptions via the Jane Core API client.
 *
 * Replaces the legacy AbstractWebhookWebService and its 5 concrete subclasses.
 *
 * The service adds little to the Jane client, but it keeps the API of v11. So v11 code that calls
 * subscribe() and unsubscribe() keeps working.
 *
 * getAll() and getById() return the Jane models, without v11 getters: the v11 guide, the PDK and the
 * Magento module call only subscribe() and unsubscribe(). An own class with getters can only come
 * in a major release, because it changes the return type.
 */
final class WebhookService
{
    use HasUserAgent;

    public const HOOK_SHIPMENT_STATUS_CHANGE             = 'shipment_status_change';
    public const HOOK_SHIPMENT_LABEL_CREATED             = 'shipment_label_created';
    public const HOOK_ORDER_STATUS_CHANGE                = 'order_status_change';
    public const HOOK_SHOP_CARRIER_ACCESSIBILITY_UPDATED = 'shop_carrier_accessibility_updated';
    public const HOOK_SHOP_UPDATED                       = 'shop_updated';

    private Client $api;

    public function __construct(string $apiKey, ?Client $api = null, ?string $host = null)
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
        $webhook       = new RefWebhookWebhookV11();
        $webhook->hook = $hook;
        $webhook->url  = $url;

        $data                       = new WebhooksPostWebhookSubscriptionsRequestV11Data();
        $data->webhookSubscriptions = [$webhook];

        $request       = new WebhooksPostWebhookSubscriptionsRequestV11();
        $request->data = $data;

        $response = $this->api->postWebhookSubscriptions(
            $request,
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        $ids = $response instanceof WebhooksResponsesPostWebhookSubscriptions ? ($response->data->ids ?? []) : [];

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

        $this->api->deleteWebhookSubscriptions($ids, ['User-Agent' => $this->getUserAgentHeader()]);
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
            null !== $hook ? ['hook' => $hook] : [],
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        return self::subscriptions($response);
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
            ['User-Agent' => $this->getUserAgentHeader()]
        );

        return self::subscriptions($response);
    }

    /**
     * @return WebhooksResponsesWebhookV11[]
     */
    private static function subscriptions(mixed $response): array
    {
        return $response instanceof WebhooksResponsesWebhookSubscriptionsV11
            ? ($response->data->webhookSubscriptions ?? [])
            : [];
    }
}
