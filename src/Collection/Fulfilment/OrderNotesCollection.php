<?php

declare(strict_types=1);

/*
 * POST /fulfilment/orders/{id}/notes
 * {
  "data": {
    "order_notes": [
      {
        "note": "Test",
        "author": "customer|webshop",
      }
    ]
  }
}
 */

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

    private function UuidsInCollection()
    {
        return array_values($this->reduce(
            function (array $uuids, OrderNote $orderNote) {
                $uuids[] = $orderNote->getUuid();

                return $uuids;
            },
            []
        ));
    }

    public function save(): self
    {
        $notes = [];

        foreach ($this->UuidsInCollection() as $uuid) {
            $requestBody = $this->where('uuid', $uuid)->map(
                function (OrderNote $orderNote) {
                    $orderNote->validate();

                    return $orderNote->toArray();
                }
            )->toArrayWithoutNull();

            $response = (new MyParcelRequest())
                ->setUserAgents($this->getUserAgent())
                ->setRequestParameters(
                    $this->ensureHasApiKey(),
                    json_encode($requestBody)
                )
                ->sendRequest('POST', strtr(MyParcelRequest::REQUEST_TYPE_ORDER_NOTES, '{id}', $uuid));

            $newNotes = Arr::get($response->getResult(), 'data.notes');

            array_map(static function (array $note) use ($uuid) {
                $note['uuid'] = $uuid;

                return $note;
            }, $newNotes);

            $notes += $newNotes;
        }

        return (new self($notes))->mapInto(OrderNote::class);
    }
}
