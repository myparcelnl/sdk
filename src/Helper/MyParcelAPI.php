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

namespace MyParcel\sdk\Helper;
use MyParcel\sdk\Model\Repository\MyParcelConsignmentRepository;


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
     * @throws \Exception
     */
    public function addConsignment(MyParcelConsignmentRepository $consignment)
    {
        if ($consignment->getApiKey() === null) {
            throw new \Exception('First set the API key with setCc() before running addConsignment()');
        }

        $this->consignments[][$consignment->getApiKey()] = $consignment;
    }

    public function registerConcept()
    {
        var_dump($this->consignments);
    }

    public function getLabel()
    {
        var_dump($this->consignments);
    }

    public function getReturnLabel()
    {
        var_dump($this->consignments);
    }
}