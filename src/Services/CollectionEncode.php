<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v1.1.7
 */

namespace MyParcelNL\Sdk\Services;

use MyParcelNL\Sdk\Helper\MyParcelCollection;
use MyParcelNL\Sdk\Model\Consignment\AbstractConsignment;

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
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function encode()
    {
        $data = [];

        $groupedConsignments = $this->groupMultiColloConsignments();

        foreach ($groupedConsignments as $consignments) {
            $data['data']['shipments'][] = (new ConsignmentEncode($consignments))->apiEncode();
        }

        // Remove \\n because json_encode encode \\n for \s
        return str_replace('\\n', " ", json_encode($data));
    }

    /**
     * @return array
     */
    private function groupMultiColloConsignments()
    {
        return $this->consignments->groupBy(function (AbstractConsignment $consignment) {
            if ($consignment->isPartOfMultiCollo()) {
                return $consignment->getReferenceId();
            }

            return 'random_to_prevent_multi_collo_' . uniqid();
        })->toArray();
    }
}
