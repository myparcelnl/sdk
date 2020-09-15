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

namespace MyParcelNL\Sdk\src\Model;

use MyParcelNL\Sdk\src\Exception\MissingFieldException;
use MyParcelNL\Sdk\src\Support\Str;

/**
 * This object is embedded in the MyParcelConsignment object for global shipments and is
 * mandatory for non-EU shipments.
 *
 * Class MyParcelCustomsItem
 */
class MyParcelCustomsItem
{
    const DESCRIPTION_MAX_LENGTH = 47;

    private $description;
    private $amount;
    private $weight;
    private $item_value;
    private $classification;
    private $country;

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
     * @param mixed $description
     * @return $this
     */
    public function setDescription($description)
    {
        /**
         * Description cut after 47 chars
         */
        $this->description = Str::limit($description, self::DESCRIPTION_MAX_LENGTH);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * The amount of this item in the package. The minimum amount is 1.
     *
     * Required: Yes
     *
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = (int) $amount;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * The total weight for these items in whole grams. Between 0 and 20000 grams.
     *
     * Required: Yes
     *
     * @param int $weight
     *
     * @return $this
     * @throws \MyParcelNL\Sdk\src\Exception\MissingFieldException
     */
    public function setWeight($weight)
    {
        if ($weight == 0) {
            throw new MissingFieldException('Weight must be set for a MyParcel product');
        }

        $this->weight = (int) $weight;

        return $this;
    }

    /**
     * Item value.
     *
     * @return int|null
     */
    public function getItemValue()
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
     * @param int $item_value
     *
     * @return $this
     */
    public function setItemValue($item_value)
    {
        $this->item_value = (int) $item_value;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getClassification()
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

        $this->classification = substr("$classification", 0, 5);

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
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
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
    public function ensureFilled()
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
            if ($this->{'get' . $methodAlias}() === null) {
                throw new MissingFieldException("set$methodAlias() must be set");
            }
        }
    }
}
