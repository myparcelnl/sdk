<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Collection\Fulfilment;

use MyParcelNL\Sdk\src\Concerns\HasApiKey;
use MyParcelNL\Sdk\src\Concerns\HasUserAgent;
use MyParcelNL\Sdk\src\Model\Fulfilment\OrderNote;
use MyParcelNL\Sdk\src\Model\MyParcelRequest;
use MyParcelNL\Sdk\src\Model\RequestBody;
use MyParcelNL\Sdk\src\Support\Arr;
use MyParcelNL\Sdk\src\Support\Collection;

class OrderNotesCollection extends Collection
{
    use HasUserAgent;
    use HasApiKey;

    /**
     * @return self Collection of notes that were saved.
     *
     * @throws \Exception
     */
    public function save(): self
    {
        $notes = [];

        $this->orderUuidsInCollection()->map(function (string $orderUuid) use (&$notes) {
            $newNotes = $this->saveForOrder($orderUuid);

            $notes = array_reduce($newNotes, static function (array $notes, OrderNote $note) {
                $notes[] = $note;

                return $notes;
            }, $notes);
        });

        return (new self($notes));
    }

    /**
     * @return \MyParcelNL\Sdk\src\Collection\Fulfilment\OrderNotesCollection
     */
    private function orderUuidsInCollection(): OrderNotesCollection
    {
        return $this->map(function (OrderNote $orderNote) {
            return $orderNote->getOrderUuid();
        })->unique();
    }

    /**
     * @param  string $orderUuid
     *
     * @return array Indexed array holding the saved order notes.
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    private function saveForOrder(string $orderUuid): array
    {
        $orderNotes = $this->where('orderUuid', '=', $orderUuid)->map(
            function (OrderNote $orderNote) {
                $orderNote->validate();

                return $orderNote->toApiObject();
            }
        )->toArrayWithoutNull();

        $response = (new MyParcelRequest())
            ->setUserAgents($this->getUserAgent())
            ->setRequestParameters(
                $this->ensureHasApiKey(),
                new RequestBody('order_notes', array_values($orderNotes))
            )
            ->sendRequest('POST', str_replace('{id}', $orderUuid, MyParcelRequest::REQUEST_TYPE_ORDER_NOTES));

        return array_map(static function (array $note) use ($orderUuid) {
            $note['orderUuid'] = $orderUuid;

            return new OrderNote($note);
        }, Arr::get($response->getResult(), 'data.order_notes', []));
    }
}
