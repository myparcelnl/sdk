<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Collection\Fulfilment;

use MyParcelNL\Sdk\Concerns\HasApiKey;
use MyParcelNL\Sdk\Concerns\HasUserAgent;
use MyParcelNL\Sdk\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\Model\MyParcelRequest;
use MyParcelNL\Sdk\Model\RequestBody;
use MyParcelNL\Sdk\Support\Arr;
use MyParcelNL\Sdk\Support\Collection;
use Throwable;

class OrderNotesCollection extends Collection
{
    use HasUserAgent;
    use HasApiKey;

    /**
     * @return self Collection of notes that were saved.
     */
    public function save(): self
    {
        $notes = [];

        $this->getUniqueOrderUuids()->each(function (string $orderUuid) use (&$notes) {
            try {
                $newNotes = $this->saveForOrder($orderUuid);
            } catch (Throwable $e) {
                return;
            }

            $notes = array_merge($notes, $newNotes);
        });

        return (new self($notes));
    }

    /**
     * @return \MyParcelNL\Sdk\Collection\Fulfilment\OrderNotesCollection
     */
    private function getUniqueOrderUuids(): OrderNotesCollection
    {
        return $this->map(function (OrderNote $orderNote) {
            return $orderNote->getOrderUuid();
        })->unique();
    }

    /**
     * @param  string $orderUuid
     *
     * @return array Indexed array holding the saved order notes.
     * @throws \MyParcelNL\Sdk\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\Exception\ApiException
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     * @throws \Exception
     */
    private function saveForOrder(string $orderUuid): array
    {
        $orderNotes = $this
            ->filter(static function (OrderNote $orderNote) use ($orderUuid) {
                return $orderNote->getOrderUuid() === $orderUuid && $orderNote->validate();
            })
            ->map(static function (OrderNote $orderNote) {
                return $orderNote->toApiObject();
            })
            ->values()
            ->toArray();

        $response = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $this->ensureHasApiKey(),
                new RequestBody('order_notes', $orderNotes)
            )
            ->sendRequest('POST', str_replace('{id}', $orderUuid, MyParcelRequest::REQUEST_TYPE_ORDER_NOTES));

        return array_map(static function (array $note) use ($orderUuid) {
            $note['orderUuid'] = $orderUuid;

            return new OrderNote($note);
        }, Arr::get($response->getResult(), 'data.order_notes', []));
    }
}
