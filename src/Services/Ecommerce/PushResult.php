<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Ecommerce;

/**
 * What MyParcel answered for one order or one product of a push.
 *
 * A push answers with one item per order or product. An item is the object `{"status": 202}` when
 * MyParcel accepted it, or a problem with a `type`, a `title`, a `detail` and, for most problems,
 * a list of `errors`.
 *
 *     foreach ((new PushResultReader())->read($response) as $result) {
 *         if (!$result->isAccepted()) {
 *             // log $result->getTitle() and $result->getDetail()
 *         }
 *     }
 */
final class PushResult
{
    /**
     * The status of an item that MyParcel accepted.
     */
    public const STATUS_ACCEPTED = 202;

    /**
     * @var bool
     */
    private $accepted;

    /**
     * @var array<string, mixed>
     */
    private $item;

    /**
     * @param array<string, mixed> $item
     */
    private function __construct(bool $accepted, array $item)
    {
        $this->accepted = $accepted;
        $this->item     = $item;
    }

    /**
     * The result for an item that MyParcel accepted.
     */
    public static function accepted(): self
    {
        return new self(true, ['status' => self::STATUS_ACCEPTED]);
    }

    /**
     * The result for an item that MyParcel did not accept.
     *
     * @param array<string, mixed> $item The item as MyParcel sent it.
     */
    public static function refused(array $item): self
    {
        return new self(false, $item);
    }

    /**
     * MyParcel accepted the order or the product. It handles it later: an accepted item is in the
     * queue, it is not processed yet.
     */
    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    /**
     * The status of the item, such as 202, 400 or 500. Null when MyParcel sent none.
     */
    public function getStatus(): ?int
    {
        $status = $this->item['status'] ?? null;

        return is_int($status) ? $status : null;
    }

    /**
     * The kind of problem, such as `urn:problem:client`. Null for an accepted item.
     */
    public function getType(): ?string
    {
        return $this->text('type');
    }

    /**
     * A short summary of the problem. Null for an accepted item.
     */
    public function getTitle(): ?string
    {
        return $this->text('title');
    }

    /**
     * What is wrong with this order or product. Null for an accepted item.
     */
    public function getDetail(): ?string
    {
        return $this->text('detail');
    }

    /**
     * The fields that MyParcel refused. Each error has a `detail` and a `pointer`, a JSON pointer
     * into the request such as `/body/0`.
     *
     * @return array<int, array{detail?: string, pointer?: string}>
     */
    public function getErrors(): array
    {
        $errors = $this->item['errors'] ?? [];

        return is_array($errors) ? array_values(array_filter($errors, 'is_array')) : [];
    }

    private function text(string $key): ?string
    {
        $value = $this->item[$key] ?? null;

        return is_string($value) ? $value : null;
    }
}
