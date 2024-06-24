<?php
declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

class PhysicalProperties extends BaseModel
{
    private const DEFAULT_WEIGHT = 10;
    private const DEFAULT_LENGTH = 10;
    private const DEFAULT_WIDTH  = 10;
    private const DEFAULT_HEIGHT = 10;


    /**
     * @var int
     */
    private $weight;

    /**
     * @var int
     */
    private $length;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Supply weight, length, width and height. Illegal values will be set to default 10.
     * MyParcel API expects weight in grams and dimensions in centimeters / cm.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setWeight((int) ($data['weight'] ?? self::DEFAULT_WEIGHT));
        $this->setLength((int) ($data['length'] ?? self::DEFAULT_LENGTH));
        $this->setWidth((int) ($data['width'] ?? self::DEFAULT_WIDTH));
        $this->setHeight((int) ($data['height'] ?? self::DEFAULT_HEIGHT));
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @param int $length in cm
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int $width in cm
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height in cm
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'weight' => $this->getWeight(),
            'length' => $this->getLength(),
            'width'  => $this->getWidth(),
            'height' => $this->getHeight(),
        ];
    }
}
