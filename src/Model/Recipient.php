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
    private $postalCode;

    /**
     * @var string|null
     */
    private $region;

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
    private $fullStreet;

    /**
     * @var string|null
     */
    private $streetAdditionalInfo;

    /**
     * @var string|null
     */
    private $boxNumber;

    /**
     * @var string|null
     */
    private $numberSuffix;

    /**
     * @param  array       $data
     * @param  string|null $originCountry
     *
     * @throws \Exception
     */
    public function __construct(array $data = [], ?string $originCountry = null)
    {
        $this->cc                   = $data['cc'] ?? null;
        $this->city                 = $data['city'] ?? null;
        $this->company              = $data['company'] ?? null;
        $this->email                = $data['email'] ?? null;
        $this->person               = $data['person'] ?? null;
        $this->phone                = $data['phone'] ?? null;
        $this->postalCode           = $data['postal_code'] ?? null;
        $this->region               = $data['region'] ?? null;
        $this->streetAdditionalInfo = $data['street_additional_info'] ?? null;
        $this->street               = $data['street'] ?? null;
        $this->fullStreet           = $data['full_street'] ?? null;
        $this->number               = $data['number'] ?? null;
        $this->numberSuffix         = $data['number_suffix'] ?? null;
        $this->boxNumber            = $data['box_number'] ?? null;

        if ($this->fullStreet && $originCountry) {
            $this->setFullStreet($originCountry);
        }
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
        return $this->postalCode;
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
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function getStreetAdditionalInfo(): ?string
    {
        return $this->streetAdditionalInfo;
    }

    /**
     * @return string|null
     */
    public function getBoxNumber(): ?string
    {
        return $this->boxNumber;
    }

    /**
     * @return string|null
     */
    public function getNumberSuffix(): ?string
    {
        return $this->numberSuffix;
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
        $this->postalCode = $postalCode;
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
     * @param  string|null $number
     *
     * @return self
     */
    public function setNumber(?string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param  string|null $boxNumber
     *
     * @return self
     */
    public function setBoxNumber(?string $boxNumber): self
    {
        $this->boxNumber = $boxNumber;
        return $this;
    }

    /**
     * @param  string|null $numberSuffix
     *
     * @return self
     */
    public function setNumberSuffix(?string $numberSuffix): self
    {
        $this->numberSuffix = $numberSuffix;
        return $this;
    }

    /**
     * @param  string $originCountry
     *
     * @return self
     * @throws \Exception
     */
    public function setFullStreet(string $originCountry): self
    {
        $splitStreet = SplitStreet::splitStreet($this->fullStreet, $originCountry, $this->getCc());
        $this->setStreet($splitStreet->getStreet());
        $this->setNumber((string) $splitStreet->getNumber());
        $this->setBoxNumber($splitStreet->getBoxNumber());
        $this->setNumberSuffix($splitStreet->getNumberSuffix());

        return $this;
    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'box_number'             => $this->getBoxNumber(),
            'cc'                     => $this->getCc(),
            'city'                   => $this->getCity(),
            'company'                => $this->getCompany(),
            'email'                  => $this->getEmail(),
            'number'                 => $this->getNumber(),
            'number_suffix'          => $this->getNumberSuffix(),
            'person'                 => $this->getPerson(),
            'phone'                  => $this->getPhone(),
            'postal_code'            => $this->getPostalCode(),
            'region'                 => $this->getRegion(),
            'street'                 => $this->getStreet(),
            'street_additional_info' => $this->getStreetAdditionalInfo(),
        ];
    }
}
