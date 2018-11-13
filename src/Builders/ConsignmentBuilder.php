<?php
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\src\Builders;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;

class ConsignmentBuilder extends MyParcelConsignment
{
    /**
     * The total weight for all items in whole grams
     *
     * @return int
     */
    public function getTotalWeight()
    {
        $weight = 0;

        foreach ($this->getItems() as $item) {
            $weight += ($item->getWeight());
        }

        if ($weight == 0) {
            $weight = 1;
        }

        return $weight;
    }

    /**
     * Get ReturnShipment Object to send to MyParcel
     *
     * @return array
     */
    public function encodeReturnShipment() {
        $data = [
            'parent' => $this->getMyParcelConsignmentId(),
            'carrier' => 1,
            'email' => $this->getEmail(),
            'name' => $this->getPerson(),
        ];

        return $data;
    }
}