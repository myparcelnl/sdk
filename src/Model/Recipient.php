<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Helper\SplitStreet;

class Recipient extends BaseModel
{
    /**
     * @var string|null
     */
    private $cc;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $company;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $person;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var string|null
     */
    private $postal_code;

    /**
     * @var string|null
     */
    private $street;

    /**
     * @var string|null
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
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        $this->cc            = $data['cc'] ?? null;
        $this->city          = $data['city'] ?? null;
        $this->company       = $data['company'] ?? null;
        $this->email         = $data['email'] ?? null;
        $this->person        = $data['person'] ?? null;
        $this->phone         = $data['phone'] ?? null;
        $this->postal_code   = $data['postal_code'] ?? null;
        $this->street        = $data['street'] ?? null;
        $this->number        = $data['number'] ?? null;
        $this->number_suffix = $data['number_suffix'] ?? null;
        $this->box_number    = $data['box_number'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getCc(): ?string
    {
        return $this->cc;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPerson(): ?string
    {
        return $this->person;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return null|string
     */
    public function getNumber(): ?string
    {
        return (string) $this->number;
    }

    /**
     * @return null|string
     */
    public function getNumberSuffix(): ?string
    {
        return $this->number_suffix;
    }

    /**
     * @return string|null
     */
    public function getBoxNumber(): ?string
    {
        return $this->box_number;
    }

    /**
     * @param string|null $box_number
     */
    public function setBoxNumber(?string $box_number): void
    {
        $this->box_number = $box_number;
    }

    /**
     * @param  string|null  $cc
     *
     * @return self
     */
    public function setCc(?string $cc): self
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param  string|null  $city
     *
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param  string|null  $company
     *
     * @return self
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param  string|null  $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param  string|null  $person
     *
     * @return self
     */
    public function setPerson(?string $person): self
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @param  string|null  $phone
     *
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param  string|null  $postalCode
     *
     * @return self
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param  string|null  $street
     *
     * @return self
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @param  string|null  $number
     *
     * @return self
     */
    public function setNumber(?string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param  string|null  $numberSuffix
     *
     * @return self
     */
    public function setNumberSuffix(?string $numberSuffix): self
    {
        $this->number_suffix = $numberSuffix;
        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'cc'            => $this->getCc(),
            'city'          => $this->getCity(),
            'company'       => $this->getCompany(),
            'email'         => $this->getEmail(),
            'street'        => $this->getStreet(),
            'number'        => $this->getNumber(),
            'number_suffix' => $this->getNumberSuffix(),
            'box_number'    => $this->getBoxNumber(),
            'person'        => $this->getPerson(),
            'phone'         => $this->getPhone(),
            'postal_code'   => $this->getPostalCode(),
        ];
    }
}
