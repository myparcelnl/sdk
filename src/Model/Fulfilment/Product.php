<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model\Fulfilment;

use MyParcelNL\Sdk\src\Model\BaseModel;
use MyParcelNL\Sdk\src\Support\Helpers;

class Product extends BaseModel
{
    /**
     * Additional product description.
     *
     * @var string|null
     */
    private $description;

    /**
     * International Article Number, currently unused.
     *
     * @see https://en.wikipedia.org/wiki/International_Article_Number
     * @var string|null
     */
    private $ean;

    /**
     * The unique identifier of the product in your webshop.
     *
     * @var string|null
     */
    private $external_identifier;

    /**
     * Product height in millimeters
     *
     * @var int|null
     */
    private $height;

    /**
     * Product length in millimeters.
     *
     * @var int|null
     */
    private $length;

    /**
     * Product name. Max 40 characters.
     *
     * @var string|null
     */
    private $name;

    /**
     * Stock Keeping Unit of the product.
     *
     * @see https://en.wikipedia.org/wiki/Stock_keeping_unit
     * @var string|null
     */
    private $sku;

    /**
     * Unique identifier from our API.
     *
     * @var string|null
     */
    private $uuid;

    /**
     * Product weight in grams.
     *
     * @var int|null
     */
    private $weight;

    /**
     * Product width in millimeters.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->uuid                = $data['uuid'] ?? null;
        $this->sku                 = $data['sku'] ?? null;
        $this->ean                 = $data['ean'] ?? null;
        $this->external_identifier = $data['external_identifier'] ?? null;
        $this->name                = $data['name'] ?? null;
        $this->description         = $data['description'] ?? null;
        $this->width               = Helpers::intOrNull($data['width'] ?? null);
        $this->height              = Helpers::intOrNull($data['height'] ?? null);
        $this->length              = Helpers::intOrNull($data['length'] ?? null);
        $this->weight              = Helpers::intOrNull($data['weight'] ?? null);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getEan(): ?string
    {
        return $this->ean;
    }

    /**
     * @return string|null
     */
    public function getExternalIdentifier(): ?string
    {
        return $this->external_identifier;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
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
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param  string|null $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param  string|null $ean
     *
     * @return self
     */
    public function setEan(?string $ean): self
    {
        $this->ean = $ean;
        return $this;
    }

    /**
     * @param  string|null $external_identifier
     *
     * @return self
     */
    public function setExternalIdentifier(?string $external_identifier): self
    {
        $this->external_identifier = $external_identifier;
        return $this;
    }

    /**
     * @param  int|null $height
     *
     * @return self
     */
    public function setHeight(?int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param  int|null $length
     *
     * @return self
     */
    public function setLength(?int $length): self
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param  string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param  string|null $sku
     *
     * @return self
     */
    public function setSku(?string $sku): self
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @param  string|null $uuid
     *
     * @return self
     */
    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @param  int|null $weight
     *
     * @return self
     */
    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param  int|null $width
     *
     * @return self
     */
    public function setWidth(?int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'description'         => $this->getDescription(),
            'ean'                 => $this->getEan(),
            'external_identifier' => $this->getExternalIdentifier(),
            'height'              => $this->getHeight(),
            'length'              => $this->getLength(),
            'name'                => $this->getName(),
            'sku'                 => $this->getSku(),
            'uuid'                => $this->getUuid(),
            'weight'              => $this->getWeight(),
            'width'               => $this->getWidth(),
        ];
    }
}
