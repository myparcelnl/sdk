<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

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
    private $region;

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
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->cc          = $data['cc'] ?? null;
        $this->city        = $data['city'] ?? null;
        $this->region      = $data['region'] ?? null;
        $this->company     = $data['company'] ?? null;
        $this->email       = $data['email'] ?? null;
        $this->person      = $data['person'] ?? null;
        $this->phone       = $data['phone'] ?? null;
        $this->postal_code = $data['postal_code'] ?? null;
        $this->street      = $data['street'] ?? null;
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
    public function getRegion(): ?string
    {
        return $this->region;
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
     * @param  string|null $cc
     *
     * @return self
     */
    public function setCc(?string $cc): self
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param  string|null $city
     *
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param  string|null $region
     *
     * @return self
     */
    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @param  string|null $company
     *
     * @return self
     */
    public function setCompany(?string $company): self
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @param  string|null $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param  string|null $person
     *
     * @return self
     */
    public function setPerson(?string $person): self
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @param  string|null $phone
     *
     * @return self
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param  string|null $postalCode
     *
     * @return self
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postal_code = $postalCode;
        return $this;
    }

    /**
     * @param  string|null $street
     *
     * @return self
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'cc'          => $this->getCc(),
            'city'        => $this->getCity(),
            'region'      => $this->getRegion(),
            'company'     => $this->getCompany(),
            'email'       => $this->getEmail(),
            'street'      => $this->getStreet(),
            'person'      => $this->getPerson(),
            'phone'       => $this->getPhone(),
            'postal_code' => $this->getPostalCode(),
        ];
    }
}
