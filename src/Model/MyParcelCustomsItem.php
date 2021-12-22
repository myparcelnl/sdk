<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\src\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Support\Str;

/**
 * This object is embedded in a Consignment for global shipments and is mandatory for non-EU shipments.
 */
class MyParcelCustomsItem
{
    /**
     * @var null|string
     */
    public $description;

    /**
     * @var null|int
     */
    public $amount;

    /**
     * @var null|int
     */
    public $weight;

    /**
     * @var null|int
     */
    public $item_value;

    /**
     * @var null|int
     */
    public $classification;

    /**
     * @var null|string
     */
    public $country;

    /**
     * Encode product for the request
     *
     * @param  string $currency
     *
     * @return array
     */
    public function encode(string $currency): array
    {
        return [
            'description'    => $this->getDescription(),
            'amount'         => $this->getAmount(),
            'weight'         => $this->getWeight(),
            'classification' => $this->getClassification(),
            'country'        => $this->getCountry(),
            'item_value'     => [
                'amount'   => $this->getItemValue(),
                'currency' => $currency,
            ],
        ];
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * The description of the item
     *
     * Required: Yes
     *
     * @param mixed $description
     * @param string|int|AbstractCarrier $carrier
     * @return $this
     *
     * @throws \Exception
     */
    public function setDescription($description, $carrier = null): self
    {
        $maxLength = AbstractConsignment::DESCRIPTION_MAX_LENGTH;

        if ($carrier) {
            $consignment = ConsignmentFactory::createFromCarrier(CarrierFactory::create($carrier));
            $maxLength   = $consignment::DESCRIPTION_MAX_LENGTH;
        }

        $this->description = Str::limit((string) $description, $maxLength - 3);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * The amount of this item in the package. The minimum amount is 1.
     *
     * Required: Yes
     *
     * @param int|string $amount
     * @return $this
     */
    public function setAmount($amount): self
    {
        $this->amount = (int) $amount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * The total weight for these items in whole grams. Between 0 and 20000 grams.
     * Required: Yes
     *
     * @param  int|string $weight
     *
     * @return $this
     */
    public function setWeight($weight): self
    {
        $this->weight = (int) $weight;

        return $this;
    }

    /**
     * Item value.
     *
     * @return int|null
     */
    public function getItemValue(): ?int
    {
        return $this->item_value;
    }

    /**
     * Item value
     *
     * Composite type containing integer and currency. The amount is without decimal
     * separators (in cents).
     * Required: Yes
     *
     * @param int|string $item_value
     *
     * @return $this
     */
    public function setItemValue($item_value): self
    {
        $this->item_value = (int) $item_value;
        return $this;
    }

    /**
     * @param array $item_value
     *
     * @return $this
     */
    public function setItemValueArray(array $item_value): self
    {
        $this->item_value = $item_value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getClassification(): ?int
    {
        return $this->classification;
    }

    /**
     * International Standard Industry Classification
     *
     * Pattern: [0-9]{1,4}
     * Example: 0111 (Growing of cereals (except rice), leguminous crops and oil seeds)
     * Required: Yes
     *
     * @link https://www.cbs.nl/nl-nl/deelnemers-enquetes/deelnemers-enquetes/bedrijven/onderzoek/internationale-handel-in-goederen/idep-codelijsten
     *
     * @param null|int $classification
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setClassification(?int $classification): self
    {
        if (! $classification) {
            throw new MissingFieldException('Classification must be set for a MyParcel product');
        }

        $this->classification = (int) Str::limit((string) $classification, 5, '');

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * The country of origin for this item
     *
     * ISO 3166-1 alpha-2 code
     * Pattern: [A-Z]{2,2}
     * Example: NL, BE, CW
     * Required: Yes
     *
     * @link https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
     *
     * @param string|null $country
     * @return $this
     */
    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Check if object is fully filled
     *
     * @return void
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function ensureFilled(): void
    {
        $required = [
            'Description',
            'Amount',
            'Weight',
            'ItemValue',
            'Classification',
            'Country',
        ];
        foreach ($required as $methodAlias) {
            if (null === $this->{'get' . $methodAlias}()) {
                throw new MissingFieldException("set$methodAlias() must be set");
            }
        }
    }
}
