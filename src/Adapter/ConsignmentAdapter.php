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
namespace MyParcelNL\Sdk\src\Adapter;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\Repository\MyParcelConsignmentRepository;

class ConsignmentAdapter
{
    private $data;
    private $recipient;
    private $options;

    /**
     * @var MyParcelConsignment
     */
    private $consignment;

    /**
     * ConsignmentDecode constructor.
     * @param array $data
     * @param string $apiKey
     * @throws \Exception
     */
    public function __construct($data, $apiKey)
    {
        $this->data      = $data;
        $this->recipient = $data['recipient'];
        $this->options   = $data['options'];
        $this->consignment = (new MyParcelConsignment())->setApiKey($apiKey);

        $this
            ->baseOptions()
            ->extraOptions()
            ->recipient()
            ->pickup();
    }

    /**
     * Decode all the data after the request with the API
     *
     * @return MyParcelConsignment
     */
    public function getConsignment()
    {
        return $this->consignment;
    }

    /**
     * @return $this
     */
    private function baseOptions()
    {
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->consignment
            ->setMyParcelConsignmentId($this->data['id'])
            ->setReferenceId($this->data['reference_identifier'])
            ->setBarcode($this->data['barcode'])
            ->setStatus($this->data['status'])
            ->setCountry($this->recipient['cc'])
            ->setPerson($this->recipient['person'])
            ->setPostalCode($this->recipient['postal_code'])
            ->setStreet($this->recipient['street'])
            ->setCity($this->recipient['city'])
            ->setEmail($this->recipient['email'])
            ->setPhone($this->recipient['phone'])
            ->setPackageType($this->options['package_type'])
            ->setLabelDescription(isset($this->options['label_description']) ? $this->options['label_description'] : '')
        ;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function extraOptions()
    {
        $fields = [
            'only_recipient' => false,
            'large_format' => false,
            'signature' => false,
            'return' => false,
            'delivery_date' => null,
            'delivery_type' => MyParcelConsignmentRepository::DEFAULT_DELIVERY_TYPE,
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        $methods = [
            'OnlyRecipient' => 'only_recipient',
            'LargeFormat' => 'large_format',
            'Signature' => 'signature',
            'Return' => 'return',
            'DeliveryDate' => 'delivery_date',
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->setByMethods($this->options, $methods);

        if (key_exists('insurance', $this->options)) {
            $insuranceAmount = $this->options['insurance']['amount'];
            $this->consignment->setInsurance($insuranceAmount / 100);
        }

        if (isset($this->options['delivery_type'])) {
            $this->consignment->setDeliveryType($this->options['delivery_type'], false);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function recipient()
    {
        $fields = [
            'company' => '',
            'number' => null,
            'number_suffix' => '',

        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        $methods = [
            'Company' => 'company',
            'Number' => 'number',
            'NumberSuffix' => 'number_suffix',
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->setByMethods($this->recipient, $methods);

        return $this;
    }

    /**
     * @return $this
     */
    private function pickup()
    {
        // Set pickup
        if (key_exists('pickup', $this->data) && $this->data['pickup'] !== null) {
            $methods = [
                'PickupPostalCode' => 'pickup_postal_code',
                'PickupStreet' => 'pickup_street',
                'PickupCity' => 'pickup_city',
                'PickupNumber' => 'pickup_number',
                'PickupLocationName' => 'pickup_location_name',
                'PickupLocationCode' => 'pickup_location_code',
                'PickupNetworkId' => 'pickup_network_id',
            ];
            /** @noinspection PhpInternalEntityUsedInspection */
            $this->setByMethods($this->data['pickup'], $methods);
        } else {

            $fields = [
                'pickup_postal_code' => null,
                'pickup_street' => null,
                'pickup_city' => null,
                'pickup_number' => null,
                'pickup_location_name' => null,
                'pickup_location_code' => '',
                'pickup_network_id' => '',

            ];
            /** @noinspection PhpInternalEntityUsedInspection */
            $this->clearFields($fields);
        }

        return $this;
    }

    /**
     * @param array $data
     * @param array $methods
     *
     * @return $this
     */
    private function setByMethods($data, $methods) {
        foreach ($methods as $method => $value) {
            if (!empty($data[$value])) {
                $this->consignment->{'set' . $method}($data[$value]);
            }
        }

        return $this;
    }

    /**
     * @param $fields
     *
     * @return $this
     */
    private function clearFields($fields) {
        foreach ($fields as $field => $default) {
            $this->consignment->{$field} = $default;
        }

        return $this;
    }
}
