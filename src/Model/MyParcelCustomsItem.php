<?php
/**
 * This object is embedded in the MyParcelConsignment object for global shipments and is
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v0.1.0
 */

namespace MyParcelNL\Sdk\src\Model;


/**
 * This object is embedded in the MyParcelConsignment object for global shipments and is
 * mandatory for non-EU shipments.
 *
 * Class MyParcelCustomsItem
 * @package MyParcelNL\Sdk\Model\Repository
 */
class MyParcelCustomsItem
{
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
        $this->description = $description;

        return $this;
    }

    /**
     * @return int
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
     * @return int
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
     * @return $this
     */
    public function setWeight($weight)
    {
        if ($weight == 0) {
            throw new \Exception('Weight must be set for a MyParcel product');
        }

        $this->weight = (int) $weight;

        return $this;
    }

    /**
     * Item value.
     *
     * @return int
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
     * Pattern: {"amount": integer, "currency": currency }
     * Example {"amount": 5000, "currency": "EUR"}
     * Required: Yes
     *
     * @param int $item_value
     * @return $this
     */
    public function setItemValue($item_value)
    {
        $this->item_value = (int) $item_value;

        return $this;
    }

    /**
     * @return int
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
     * @link https://www.cbs.nl/-/media/cbsvooruwbedrijf/internationale%20handel%20in%20goederen/goederencodes%20in%20excel.xlsx?la=nl-nl
     *
     * @param int $classification
     * @return $this
     */
    public function setClassification($classification)
    {
        $this->classification = substr($classification, 0, 4);

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * The country of origin for this item
     *
     * ISO3166-1 alpha2 country code
     * Pattern: [A-Z]{2,2}
     * Example: NL, BE, CW
     * Required: Yes
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
     * @return bool
     * @throws \Exception
     */
    public function isFullyFilledItem()
    {
        if ($this->getDescription() === null) {
            throw new \Exception('setDescription() must be set');
        }

        if ($this->getAmount() === null) {
            throw new \Exception('setAmount() must be set');
        }

        if ($this->getWeight() === null) {
            throw new \Exception('setWeight() must be set');
        }

        if ($this->getItemValue() === null) {
            throw new \Exception('setItemValue() must be set');
        }

        if ($this->getClassification() === null) {
            throw new \Exception('setClassification() must be set');
        }

        if ($this->getCountry() === null) {
            throw new \Exception('setCountry() must be set');
        }

        return true;
    }
}