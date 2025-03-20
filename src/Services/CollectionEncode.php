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
     * @param  string $key default 'shipments', the key under 'data' in which the shipments will be returned
     *
     * @return string
     * @throws \MyParcelNL\Sdk\Exception\MissingFieldException
     */
    public function encode(string $key = 'shipments'): string
    {
        $data = [];

        $groupedConsignments = $this->groupMultiColloConsignments();

        foreach ($groupedConsignments as $consignments) {
            $consignment = (new ConsignmentEncode($consignments))->apiEncode();
            // switch original recipient to sender for return shipments
            if ('return_shipments' === $key) {
                $consignment['sender'] = $consignment['recipient'];
                // API does not allow state for sender
                unset($consignment['recipient'], $consignment['sender']['state']);
            }
            $data['data'][$key][] = $consignment;
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
                return $consignment->getReferenceIdentifier();
            }

            return 'random_to_prevent_multi_collo_' . uniqid('', true);
        })->toArray();
    }
}
