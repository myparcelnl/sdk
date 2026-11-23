<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

/**
 * Reads the answer of an order push or a product push into one result per item.
 *
 * The spec describes an item as one of four shapes and gives a generator no field to tell them
 * apart. The generated client therefore returns the decoded JSON, and this class reads it.
 *
 *     $response = $api->postWebhookOrder(requestBody: [$order]);
 *     $results  = (new PushResultReader())->read($response);
 */
final class PushResultReader
{
    /**
     * @param  array<int, mixed> $response What `postWebhookOrder()` or `postWebhookProduct()` returned.
     * @return \MyParcelNL\Sdk\Services\Ecommerce\PushResult[] One result per item, in the order of the response.
     */
    public function read(array $response): array
    {
        $results = [];

        foreach ($response as $item) {
            // The generated client gives each item as an object. An array is simpler to read.
            $results[] = $this->result((array) json_decode((string) json_encode($item), true));
        }

        return $results;
    }

    /**
     * Decide for one item if MyParcel accepted it.
     *
     * @param array<string, mixed> $item One item of the answer, such as `['status' => 202]`.
     */
    private function result(array $item): PushResult
    {
        if (PushResult::STATUS_ACCEPTED === ($item['status'] ?? null)) {
            return PushResult::accepted();
        }

        return PushResult::refused($item);
    }
}
