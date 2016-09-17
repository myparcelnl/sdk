<?php
/**
 * Stores all data to communicate with the MyParcel API
 *
 * LICENSE: This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2016 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release 0.1.0
 */

namespace myparcelnl\sdk\Helper;

use myparcelnl\sdk\Model\MyParcelConsignment;
use myparcelnl\sdk\Model\MyParcelRequest;
use myparcelnl\sdk\Model\Repository\MyParcelConsignmentRepository;


/**
 * Stores all data to communicate with the MyParcel API
 *
 * Class MyParcelAPI
 * @package Model
 */
class MyParcelAPI
{
    /**
     * @var array
     */
    private $consignments = [];

    /**
     * @var string
     */
    private $paper_size = 'A6';

    /**
     * The position of the label on the paper.
     * pattern: [1 - 4]
     * example: 1. (top-left)
     *          2. (top-right)
     *          3. (bottom-left)
     *          4. (bottom-right)
     *
     * @var string
     */
    private $label_position = null;

    /**
     * @var bool
     */
    private $return = false;

    /**
     * @return array
     */
    public function getConsignments()
    {
        return $this->consignments;
    }

    public function getConsignmentById($id)
    {
        return $this->consignments[$id];
    }

    /**
     * @param MyParcelConsignmentRepository $consignment
     *
     * @return $this
     * @throws \Exception
     */
    public function addConsignment(MyParcelConsignmentRepository $consignment)
    {
        if ($consignment->getApiKey() === null)
            throw new \Exception('First set the API key with setApiKey() before running addConsignment()');

        $this->consignments[] = $consignment;
        return $this;
    }

    public function createConcepts()
    {
        $consignmentsSortedByKey = $this->getConsignmentsSortedByKey();

        foreach ($consignmentsSortedByKey as $key => $consignments) {

            $data = $this->apiEncode($consignments);
            $request = new MyParcelRequest();
            $request
                ->setRequestParameters($data, $key, 'shipments', $request::REQUEST_HEADER_SHIPMENT)
                ->sendRequest();

            var_dump($request->getResult());
        }

        return $this;
    }

    public function getLatestData()
    {
        return $this;
    }

    public function isReturn($return = true)
    {
        $this->return = $return;
        return $this;
    }

    /**
     * @param array|int|bool $positions The position of the label on an A4 sheet. You can specify multiple positions by
     *                                  using an array. E.g. [2,3,4]. If you do not specify an array, but specify a
     *                                  number, the following labels will fill the ascending positions. Positioning is
     *                                  only applied on the first page with labels. All subsequent pages will use the
     *                                  default positioning [1,2,3,4].
     *
     * @return $this
     */
    public function setA4($positions = 1)
    {
        /** If $positions is not false, set paper size to A4 */
        if (is_numeric($positions)) {
            /** Generating positions for A4 paper */
            $this->paper_size = 'A4';
            $this->label_position = $this->getPositions($positions);
        } elseif (is_array($positions)) {
            /** Set positions for A4 paper */
            $positions = implode(';', $positions);
            $this->paper_size = 'A4';
            $this->label_position = $positions;
        } else {
            /** Set paper size to A6 */
            $this->paper_size = 'A6';
            $this->label_position = null;
        }

        return $this;
    }

    private function apiEncode($consignments)
    {
        $data = [];
        /** @var $consignment MyParcelConsignmentRepository */
        foreach ($consignments as $consignment) {
            $data['data']['shipments'][] = $consignment->apiEncode();

        }
        return json_encode($data);
    }

    /**
     * Generating positions for A4 paper
     *
     * @param int $start
     *
     * @return string
     */
    private function getPositions($start)
    {
        $aPositions = array();
        switch ($start) {
            case 1:
                $aPositions[] = 1;
            case 2:
                $aPositions[] = 2;
            case 3:
                $aPositions[] = 3;
            case 4:
                $aPositions[] = 4;
                break;
        }

        return implode(';', $aPositions);
    }

    private function getConsignmentsSortedByKey()
    {
        $aConsignments = [];
        /** @var $consignment MyParcelConsignment */
        foreach ($this->getConsignments() as $consignment) {
            $aConsignments[$consignment->getApiKey()][] = $consignment;
        }
        return $aConsignments;
    }
}