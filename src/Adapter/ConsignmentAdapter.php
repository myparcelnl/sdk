<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\src\Adapter;

use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Support\Arr;

class ConsignmentAdapter
{
    private $data;

    /**
     * @var AbstractConsignment
     */
    private $consignment;

    /**
     * ConsignmentDecode constructor.
     *
     * @param array                                                     $data
     * @param \MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment $consignment
     *
     * @throws \Exception
     */
    public function __construct(array $data, AbstractConsignment $consignment)
    {
        $this->data        = $data;
        $this->consignment = $consignment;

        $this
            ->baseOptions()
            ->extraOptions()
            ->recipient()
            ->pickup();
    }

    /**
     * @return AbstractConsignment
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
        $recipient = $this->data['recipient'];
        $options   = $this->data['options'];

        /** @noinspection PhpInternalEntityUsedInspection */
        $this->consignment
            ->setConsignmentId($this->data['id'])
            ->setShopId($this->data['shop_id'])
            ->setReferenceId($this->data['reference_identifier'])
            ->setBarcode($this->data['barcode'])
            ->setStatus($this->data['status'])
            ->setCountry($recipient['cc'])
            ->setPerson($recipient['person'])
            ->setPostalCode($recipient['postal_code'])
            ->setStreet($recipient['street'])
            ->setCity($recipient['city'])
            ->setEmail(isset($recipient['email']) ? $recipient['email'] : '')
            ->setPhone(isset($recipient['phone']) ? $recipient['phone'] : '')
            ->setPackageType($options['package_type'])
            ->setLabelDescription(isset($options['label_description']) ? $options['label_description'] : '');

        if (Arr::get($this->data, 'physical_properties.weight')) {
            $this->consignment->setTotalWeight($this->data['physical_properties']['weight']);
        }

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    private function extraOptions()
    {
        $options = $this->data['options'];
        $fields  = [
            'only_recipient' => false,
            'large_format'   => false,
            'age_check'      => false,
            'signature'      => false,
            'return'         => false,
            'delivery_date'  => null,
            'delivery_type'  => AbstractConsignment::DEFAULT_DELIVERY_TYPE,
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        if (! empty($options['only_recipient'])) {
            $this->consignment->setOnlyRecipient((bool) $options['only_recipient']);
        }

        if (! empty($options['large_format'])) {
            $this->consignment->setLargeFormat((bool) $options['large_format']);
        }

        if (! empty($options['age_check'])) {
            $this->consignment->setAgeCheck((bool) $options['age_check']);
        }

        if (! empty($options['signature'])) {
            $this->consignment->setSignature((bool) $options['signature']);
        }

        if (! empty($options['return'])) {
            $this->consignment->setReturn((bool) $options['return']);
        }

        if (! empty($options['delivery_date'])) {
            $this->consignment->setDeliveryDate($options['delivery_date']);
        }

        if (key_exists('insurance', $options)) {
            $insuranceAmount = $options['insurance']['amount'];
            $this->consignment->setInsurance($insuranceAmount / 100);
        }

        if (isset($options['delivery_type'])) {
            $this->consignment->setDeliveryType($options['delivery_type'], false);
        }

        return $this;
    }

    /**
     * @return $this
     */
    private function recipient()
    {
        $fields = [
            'company'       => '',
            'number'        => null,
            'number_suffix' => '',

        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->clearFields($fields);

        $methods = [
            'Company'      => 'company',
            'Number'       => 'number',
            'NumberSuffix' => 'number_suffix',
        ];
        /** @noinspection PhpInternalEntityUsedInspection */
        $this->setByMethods($this->data['recipient'], $methods);

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
                'PickupPostalCode'   => 'postal_code',
                'PickupStreet'       => 'street',
                'PickupCity'         => 'city',
                'PickupNumber'       => 'number',
                'PickupLocationName' => 'location_name',
                'PickupLocationCode' => 'location_code',
                'PickupNetworkId'    => 'network_id',
            ];
            /** @noinspection PhpInternalEntityUsedInspection */
            $this->setByMethods($this->data['pickup'], $methods);
        } else {

            $fields = [
                'pickup_postal_code'   => null,
                'pickup_street'        => null,
                'pickup_city'          => null,
                'pickup_number'        => null,
                'pickup_location_name' => null,
                'pickup_location_code' => '',
                'retail_network_id'    => '',

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
    private function setByMethods($data, $methods)
    {
        foreach ($methods as $method => $value) {
            if (! empty($data[$value])) {
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
    private function clearFields($fields)
    {
        foreach ($fields as $field => $default) {
            $this->consignment->{$field} = $default;
        }

        return $this;
    }
}
