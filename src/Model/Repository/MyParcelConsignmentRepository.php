<?php
/**
 * The repository of a MyParcel consignment
 *
 * LICENSE: This source file is subject to the Creative Commons License:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 *
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2016 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since release 0.1.0
 */
namespace MyParcelNL\Sdk\src\Model\Repository;


use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
/**
 * The repository of a MyParcel consignment
 *
 * Class MyParcelConsignmentRepository
 * @package MyParcelNL\Sdk\Model\Repository
 */
class MyParcelConsignmentRepository extends MyParcelConsignment
{

    /**
     * Regular expression used to split street name from house number.
     *
     * For the full description go to:
     * @link https://gist.github.com/reindert-vetter/a90fdffe7d452f92d1c65bbf759f6e38
     */
    const SPLIT_STREET_REGEX = '~(?P<street>.*?)\s?(?P<street_suffix>(?P<number>[\d]+)-?(?P<number_suffix>[a-zA-Z/\s]{0,5}$|[0-9/]{0,5}$|\s[a-zA-Z]{1}[0-9]{0,3}$))$~';

    /**
     * Get entire street
     *
     * @return string Entire street
     */
    public function getFullStreet()
    {
        $fullStreet = $this->getStreet();

        if ($this->getNumber())
            $fullStreet .= ' ' . $this->getNumber();

        if ($this->getNumberSuffix())
            $fullStreet .= ' ' . $this->getNumberSuffix();

        return trim($fullStreet);
    }

    /**
     * Splitting a full NL address and save it in this object
     *
     * Required: Yes or use setStreet()
     *
     * @param $fullStreet
     *
     * @return $this
     * @throws \Exception
     */
    public function setFullStreet($fullStreet)
    {
        if ($this->getCountry() === null)
            throw new \Exception('First set the country code with setCountry() before running setFullStreet()');

        if ($this->getCountry() == 'NL') {
            $streetData = $this->splitStreet($fullStreet);
            $this->setStreet($streetData['street']);
            $this->setNumber($streetData['number']);
            $this->setNumberSuffix($streetData['number_suffix']);
        } else {
            $this->setStreet($fullStreet);
        }
        return $this;
    }

    /**
     * The total weight for all items in whole grams
     *
     * @todo get weight of all items
     *
     * @return int
     */
    public function getTotalWeight()
    {
        return;
    }

    /**
     * Encode all the data before sending it to MyParcel
     *
     * @return array
     */
    public function apiEncode()
    {
        $aConsignment = array(
            'recipient' => array(
                'cc' => $this->getCountry(),
                'person' => $this->getPerson(),
                'company' => $this->getCompany(),
                'postal_code' => $this->getPostalCode(),
                'street' => $this->getStreet(),
                'number' => $this->getNumber(),
                'number_suffix' => $this->getNumberSuffix(),
                'city' => $this->getCity(),
                'email' => $this->getEmail(),
                'phone' => $this->getPhone(),
            ),
            'options' => [
                'package_type' => $this->getPackageType(),
                'large_format' => $this->isLargeFormat() ? 1 : 0,
                'only_recipient' => $this->isOnlyRecipient() ? 1 : 0,
                'signature' => $this->isSignature() ? 1 : 0,
                'return' => $this->isReturn() ? 1 : 0,
                'label_description' => $this->getLabelDescription(),
                'delivery_type' => $this->getDeliveryType(),
            ],
            'carrier' => 1,
        );

        if ($this->getInsurance() > 1) {
            $aConsignment['options']['insurance'] = ['amount' => (int)$this->getInsurance() * 100, 'currency' => 'EUR'];
        }
        if ($this->getDeliveryDate())
            $aConsignment['options']['delivery_date'] = $this->getDeliveryDate();

        return $aConsignment;
    }

    /**
     * Decode all the data after the request with the API
     *
     * @param $data
     *
     * @return $this
     */
    public function apiDecode($data)
    {
        $recipient = $data['recipient'];
        $options = $data['options'];

        $this
            ->setApiId($data['id'])
            ->setBarcode($data['barcode'])
            ->setStatus($data['status'])
            ->setCountry($recipient['cc'])
            ->setPerson($recipient['person'])
            ->setCompany($recipient['company'])
            ->setPostalCode($recipient['postal_code'])
            ->setStreet($recipient['street'])
            ->setNumber($recipient['number'])
            ->setNumberSuffix($recipient['number_suffix'])
            ->setCity($recipient['city'])
            ->setEmail($recipient['email'])
            ->setPhone($recipient['phone'])
            ->setPackageType($options['package_type'])
            ->setLargeFormat($options['large_format'])
            ->setOnlyRecipient($options['only_recipient'])
            ->setSignature($options['signature'])
            ->setReturn($options['return'])
            ->setLabelDescription($options['label_description'])
        ;

        if (key_exists('insurance', $data['options']))
            $this->setInsurance($data['options']['insurance']['amount'] / 100);
        ;

        if (key_exists('delivery_date', $data['options']))
            $this->setDeliveryDate($data['options']['delivery_date']);

        return $this;
    }

    /**
     * Splits street data into separate parts for street name, house number and extension.
     *
     * @param string $fullStreet The full street name including all parts
     *
     * @return array
     *
     * @throws \Exception
     */
    private function splitStreet($fullStreet)
    {
        $street = '';
        $number = '';
        $number_suffix = '';

        $result = preg_match(self::SPLIT_STREET_REGEX, $fullStreet, $matches);

        if (!$result || !is_array($matches) || $fullStreet != $matches[0]) {
            if ($fullStreet != $matches[0]) {
                // Characters are gone by preg_match
                throw new \Exception('Something went wrong with splitting up address ' . $fullStreet);
            } else {
                // Invalid full street supplied
                throw new \Exception('Invalid full street supplied: ' . $fullStreet);
            }
        }

        if (isset($matches['street'])) {
            $street = $matches['street'];
        }

        if (isset($matches['number'])) {
            $number = $matches['number'];
        }

        if (isset($matches['number_suffix'])) {
            $number_suffix = trim($matches['number_suffix']);
        }

        $streetData = array(
            'street' => $street,
            'number' => $number,
            'number_suffix' => $number_suffix,
        );

        return $streetData;
    }
}