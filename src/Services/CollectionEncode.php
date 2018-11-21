<?php
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2017 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v1.1.7
 */

namespace MyParcelNL\Sdk\src\Services;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class CollectionEncode
{
    private $consignments;

    /**
     * CollectionEncode constructor.
     * @param $consignments MyParcelCollection
     */
    public function __construct($consignments)
    {
        $this->consignments = $consignments;
    }

    /**
     * Encode multiple shipments so that the data can be sent to MyParcel.
     *
     * @return string
     * @throws \Exception
     */
    public function encode()
    {
        $data = [];

        foreach ($this->consignments as $consignment) {
            $data['data']['shipments'][] = (new ConsignmentEncode($consignment))->apiEncode();
        }

        // Remove \\n because json_encode encode \\n for \s
        return str_replace('\\n', " ", json_encode($data));
    }
}