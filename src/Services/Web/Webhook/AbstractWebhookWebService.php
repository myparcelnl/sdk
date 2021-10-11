<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Services\Web\Webhook;

use MyParcelNL\Sdk\src\Model\RequestBody;
use MyParcelNL\Sdk\src\Services\Web\AbstractWebService;

abstract class AbstractWebhookWebService extends AbstractWebService
{
    /**
     * The webhook this class adds/removes.
     *
     * @return string
     */
    abstract public function getHook(): string;

    /**
     * Create a new webhook subscription by hook name, returning its ID.
     *
     * @param  string      $url
     * @param  null|string $hook - Falls back to $this->getHook()
     *
     * @return int
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function subscribe(string $url, ?string $hook = null): int
    {
        $request = $this->createRequest()
            ->setRequestBody(
                new RequestBody(
                    'webhook_subscriptions',
                    $this->createRequestBody($hook, $url)
                )
            )
            ->sendRequest('POST', 'webhook_subscriptions');

        return $request->getResult('data.ids.0.id');
    }

    /**
     * Deletes a webhook subscription by ID.
     *
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function unsubscribe(int $subscriptionId): void
    {
        $this->createRequest()
            ->sendRequest(
                'DELETE',
                strtr('webhook_subscriptions/:id', [
                        ':id' => $subscriptionId,
                    ]
                )
            );
    }

    /**
     * @param  null|string $hook
     * @param  string      $url
     *
     * @return array[]
     */
    private function createRequestBody(?string $hook, string $url): array
    {
        return [
            [
                'hook' => $hook ?? $this->getHook(),
                'url'  => $url,
            ],
        ];
    }
}
