<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: richardperdaan
 * Date: 2019-06-18
 * Time: 15:49
 */

namespace MyParcelNL\Sdk\src\Model;

class FullStreet
{
    /**
     * @var string
     */

    private $street;
    /**
     * @var int|null
     */

    private $number;
    /**
     * @var string|null
     */

    private $number_suffix;
    /**
     * @var string|null
     */
    private $box_number;

    /**
     * @param string $street
     * @param int    $number
     * @param string $suffix
     * @param string $boxNumber
     */
    public function __construct(string $street, ?int $number, ?string $suffix, ?string $boxNumber)
    {
        $this->street        = $street;
        $this->number        = $number;
        $this->number_suffix = $suffix;
        $this->box_number    = $boxNumber;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return int
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getNumberSuffix(): ?string
    {
        return $this->number_suffix;
    }

    /**
     * @return string
     */
    public function getBoxNumber(): ?string
    {
        return $this->box_number;
    }
}
