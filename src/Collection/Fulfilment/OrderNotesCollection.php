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
     * @return array Indexed array of order uuids present in this collection.
     */
    private function orderUuidsInCollection(): array
    {
        return array_unique($this->reduce(
            function (array $uuids, OrderNote $orderNote) {
                $uuids[] = $orderNote->getOrderUuid();

                return $uuids;
            },
            []
        ));
    }

    /**
     * @return self Collection of notes that were saved.
     *
     * @throws \MyParcelNL\Sdk\src\Exception\AccountNotActiveException
     * @throws \MyParcelNL\Sdk\src\Exception\ApiException
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     * @throws \Exception
     */
    public function save(): self
    {
        $notes = [];

        foreach ($this->orderUuidsInCollection() as $orderUuid) {
            $orderNotes = $this->where('orderUuid', '=', $orderUuid)->map(
                function (OrderNote $orderNote) {
                    $orderNote->validate();

                    return $orderNote->toApiObject();
                }
            )->toArrayWithoutNull();

            $requestBody = new RequestBody('order_notes', array_values($orderNotes));

            $response = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $this->ensureHasApiKey(),
                    $requestBody
                )
                ->sendRequest('POST', str_replace('{id}', $orderUuid, MyParcelRequest::REQUEST_TYPE_ORDER_NOTES));

            $newNotes = array_map(static function (array $note) use ($orderUuid) {
                $note['orderUuid'] = $orderUuid;

                return new OrderNote($note);
            }, Arr::get($response->getResult(), 'data.order_notes') ?? []);

            $notes = array_reduce($newNotes, static function (array $notes, OrderNote $note) {
                $notes[] = $note;

                return $notes;
            }, $notes);
        }

        return (new self($notes));
    }
}
