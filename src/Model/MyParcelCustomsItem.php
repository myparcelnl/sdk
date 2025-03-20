<?php declare(strict_types=1);
/**
 * This object is embedded in the AbstractConsignment object for global shipments and is
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\Model;

use MyParcelNL\Sdk\Exception\MissingFieldException;
use MyParcelNL\Sdk\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\Model\Carrier\AbstractCarrier;
use MyParcelNL\Sdk\Model\Carrier\CarrierFactory;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\Services\ConsignmentEncode;
use MyParcelNL\Sdk\Support\Str;

/**
 * This object is embedded in the MyParcelConsignment object for global shipments and is
 * mandatory for non-EU shipments.
 *
 * Class MyParcelCustomsItem
 */
class MyParcelCustomsItem
{
    public $description;
    public $amount;
    public $weight;
    public $item_value = [];
    public $classification;
    public $country;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * The description of the item
     *
     * Required: Yes
     *
     * @param mixed                      $description
     * @param string|int|AbstractCarrier $carrier
     * @return $this
     *
     * @throws \Exception
     */
    public function setDescription($description, $carrier = null): self
    {
        $maxLength = AbstractConsignment::CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH;

        if ($carrier) {
            $consignment = ConsignmentFactory::createFromCarrier(CarrierFactory::create($carrier));
            $maxLength   = $consignment::CUSTOMS_DECLARATION_DESCRIPTION_MAX_LENGTH;
        }

        $this->description = Str::limit((string) $description, $maxLength);

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
     * The amount of this item in the package.
     *
     * @param int $amount
     * @return $this
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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
     *
     * @param int $weight
     *
     * @return $this
     * @throws MissingFieldException
     */
    public function setWeight(int $weight): self
    {
        if (0 === $weight) {
            throw new MissingFieldException('Weight must be set for a MyParcel product');
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * Item value.
     *
     * @return array
     */
    public function getItemValue()
    {
        return $this->item_value;
    }

    /**
     * Item value in cents in ConsignmentEncode::DEFAULT_CURRENCY.
     *
     * @param int|float|string $item_value
     *
     * @return $this
     */
    public function setItemValue($item_value): self
    {
        return $this->setItemValueArray(
            [
                'amount'   => (int) $item_value,
                'currency' => ConsignmentEncode::DEFAULT_CURRENCY,
            ]
        );
    }

    /**
     * @param array $amount
     * Array containing (int) amount and (string) currency. The amount is without decimal
     * separators (in cents).
     *
     * @return $this
     * @throws MissingFieldException
     */
    public function setItemValueArray(array $amount): self
    {
        if (!isset($amount['amount'], $amount['currency'])) {
            throw new MissingFieldException('Amount and currency must be set.');
        }
        $this->item_value = $amount;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getClassification(): ?string
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
     * @param string $classification
     *
     * @return $this
     */
    public function setClassification(string $classification): self
    {
        $this->classification = substr($classification, 0, 10);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
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
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Check if object is fully filled
     *
     * @return void
     * @throws MissingFieldException
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
            if ($this->{"get$methodAlias"}() === null) {
                throw new MissingFieldException("set$methodAlias() must be set");
            }
        }
    }
}
