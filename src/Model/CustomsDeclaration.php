<?php
declare(strict_types=1);

namespace MyParcelNL\Sdk\Model;

/**
 * @internal Legacy — used by Order v1 (fulfilment) and web services.
 */
class CustomsDeclaration extends BaseModel
{
    public $contents;
    public $items;
    public $invoice;
    public $weight;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @param  MyParcelCustomsItem $myparcelCustomsItem
     */
    public function addCustomsItem(MyParcelCustomsItem $myparcelCustomsItem)
    {
        $this->items[] = $myparcelCustomsItem;
    }

    /**
     * @return int
     */
    public function getContents(): int
    {
        return $this->contents;
    }

    /**
     * @return string
     */
    public function getInvoice(): string
    {
        return $this->invoice;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @param  int $contents
     *
     * @return \MyParcelNL\Sdk\Model\CustomsDeclaration
     */
    public function setContents(int $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * @param  string $invoice
     *
     * @return \MyParcelNL\Sdk\Model\CustomsDeclaration
     */
    public function setInvoice(string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @param  int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * Reads the properties directly, because the typed getters throw when a value is not set.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'contents' => $this->contents,
            'items'    => array_map(static function (MyParcelCustomsItem $item): array {
                return $item->toArray();
            }, $this->items),
            'invoice'  => $this->invoice,
            'weight'   => $this->weight,
        ];
    }
}
