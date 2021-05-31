<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use MyParcelNL\Sdk\src\Model\BaseModel;

class OrderLine extends BaseModel
{
    /**
     * @var array
     */
    private $instructions;

    /**
     * @var int|null
     */
    private $price;

    /**
     * @var int|null
     */
    private $price_after_vat;

    /**
     * @var \MyParcelNL\Sdk\src\Model\Fulfilment\Product
     */
    private $product;

    /**
     * @var int|null
     */
    private $quantity;

    /**
     * @var string|null
     */
    private $uuid;

    /**
     * @var int|null
     */
    private $vat;

    /**
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->uuid            = $data['uuid'] ?? null;
        $this->price           = $this->intOrNull($data['price'] ?? null);
        $this->vat             = $this->intOrNull($data['vat'] ?? null);
        $this->price_after_vat = $this->intOrNull($data['price_after_vat'] ?? null);
        $this->quantity        = $this->intOrNull($data['quantity'] ?? null);
        $this->instructions    = $data['instructions'] ?? null;

        $this->product = new Product($data['product'] ?? []);
    }

    /**
     * @return array
     */
    public function getInstructions(): ?array
    {
        return $this->instructions;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @return int|null
     */
    public function getPriceAfterVat(): ?int
    {
        return $this->price_after_vat;
    }

    /**
     * @return \MyParcelNL\Sdk\src\Model\Fulfilment\Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return int|null
     */
    public function getVat(): ?int
    {
        return $this->vat;
    }

    /**
     * @param  array $instructions
     *
     * @return self
     */
    public function setInstructions(array $instructions): self
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * @param  int|null $price
     *
     * @return self
     */
    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param  int|null $price_after_vat
     *
     * @return self
     */
    public function setPriceAfterVat(?int $price_after_vat): self
    {
        $this->price_after_vat = $price_after_vat;
        return $this;
    }

    /**
     * @param  \MyParcelNL\Sdk\src\Model\Fulfilment\Product $product
     *
     * @return self
     */
    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @param  int|null $quantity
     *
     * @return self
     */
    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param  string $uuid
     *
     * @return self
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @param  int|null $vat
     *
     * @return self
     */
    public function setVat(?int $vat): self
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'uuid'            => $this->getUuid(),
            'product'         => $this->getProduct()->toArray(),
            'quantity'        => $this->getQuantity(),
            'price'           => $this->getPrice(),
            'vat'             => $this->getVat(),
            'price_after_vat' => $this->getPriceAfterVat(),
            'instructions'    => $this->getInstructions(),
        ];
    }
}
