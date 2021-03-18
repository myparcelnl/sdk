<?php

namespace Gett\MyparcelBE\Factory\Consignment;

use Configuration;
use Country;
use DateTime;
use Exception;
use Gett\MyparcelBE\Carrier\PackageTypeCalculator;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Module\Carrier\Provider\CarrierSettingsProvider;
use Gett\MyparcelBE\OrderLabel;
use Gett\MyparcelBE\Service\Order\OrderTotalWeight;
use Gett\MyparcelBE\Service\ProductConfigurationProvider;
use Module;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\BpostConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\DPDConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use Tools;

class ConsignmentFactory
{
    private $api_key;
    private $request;
    private $configuration;
    private $module;

    public function __construct(string $api_key, array $request, Configuration $configuration, Module $module)
    {
        $this->api_key = $api_key;
        $this->configuration = $configuration;
        $this->request = $request;
        $this->module = $module;
    }

    public function fromOrders(array $orders): MyParcelCollection
    {
        $myParcelCollection = (new MyParcelCollection())
            ->setUserAgents(['prestashop' => _PS_VERSION_])
        ;

        foreach ($orders as $order) {
            $myParcelCollection
                ->addConsignment($this->initConsignment($order))
            ;
        }

        return $myParcelCollection;
    }

    public function fromOrder(array $order)
    {
        $myParcelCollection = (new MyParcelCollection())
            ->setUserAgents(['prestashop' => _PS_VERSION_])
        ;

        for ($i = 0; $i < $this->request['label_amount']; ++$i) {
            $consignment = $this->initConsignment($order);
            foreach (Constant::SINGLE_LABEL_CREATION_OPTIONS as $key => $option) {
                if (isset($this->request[$key])) {
                    if (method_exists($this, $option)) {
                        $consignment = $this->{$option}($consignment);
                    }
                }
            }

            $myParcelCollection
                ->addConsignment($consignment)
            ;
        }

        return $myParcelCollection;
    }

    private function initConsignment(array $order): AbstractConsignment
    {
        $countryCode = strtoupper($order['iso_code']);
        $consignment = (\MyParcelNL\Sdk\src\Factory\ConsignmentFactory::createByCarrierId(
            $this->getMyParcelCarrierId($order['id_carrier']))
        )
            ->setApiKey($this->api_key)
            ->setReferenceId($order['id_order'])
            ->setCountry($countryCode)
            ->setPerson($order['person'])
            ->setFullStreet($order['full_street'])
            ->setPostalCode($order['postcode'])
            ->setCity($order['city'])
            ->setContents(1)
            ->setInvoice($order['invoice_number'])
        ;

        $carrierSettingsProvider = new CarrierSettingsProvider($this->module);
        $carrierSettings = $carrierSettingsProvider->provide($order['id_carrier']);
        if (!empty($labels)) {
            foreach ($labels as &$label) {
                $label['ALLOW_DELIVERY_FORM'] = $carrierSettings['delivery']['ALLOW_FORM'];
                $label['ALLOW_RETURN_FORM'] = $carrierSettings['return']['ALLOW_FORM'];
            }
        }
        if (isset($this->request['packageType'])) {
            $packageType = $this->request['packageType'];
        } else {
            $packageType = (new PackageTypeCalculator())->getOrderPackageType($order['id_order'], $order['id_carrier']);
        }
        if (empty($carrierSettings['delivery']['packageType'][(int) $packageType])) {
            $packageType = 1; // TODO: for NL the DPD and Bpost don't allow any.
        }
        $consignment->setPackageType((int) $packageType);

        if ($this->configuration::get(Constant::SHARE_CUSTOMER_EMAIL_CONFIGURATION_NAME)) {
            $consignment->setEmail($order['email']);
        }

        if ($this->configuration::get(Constant::SHARE_CUSTOMER_PHONE_CONFIGURATION_NAME)) {
            $consignment->setPhone($order['phone']);
        }
        $delivery_setting = json_decode($order['delivery_settings']);
        if (!empty($delivery_setting->date)) {
            $deliveryDate = new DateTime($delivery_setting->date);
            $consignment->setDeliveryDate($deliveryDate->format('Y-m-d H:i:s'));
        }

        if (!empty($delivery_setting->isPickup)) {
            $consignment->setDeliveryType(
                AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME]
            );
        } else {
            if (!empty($delivery_setting->deliveryType)) {
                $consignment->setDeliveryType(
                    AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[$delivery_setting->deliveryType]
                );
            }
        }

        if ($consignment->getDeliveryType() === AbstractConsignment::DELIVERY_TYPE_PICKUP) {
            $pickupLocation = $delivery_setting->pickupLocation ?? null;
            if (!empty($pickupLocation)) {
                if (!empty($pickupLocation->cc)) {
                    $consignment->setPickupCountry($pickupLocation->cc);
                }
                if (!empty($pickupLocation->postal_code)) {
                    $consignment->setPickupPostalCode($pickupLocation->postal_code);
                }
                if (!empty($pickupLocation->street)) {
                    $consignment->setPickupStreet($pickupLocation->street);
                }
                if (!empty($pickupLocation->city)) {
                    $consignment->setPickupCity($pickupLocation->city);
                }
                if (!empty($pickupLocation->number)) {
                    $consignment->setPickupNumber($pickupLocation->number . ($pickupLocation->number_suffix ?? ''));
                }
                if (!empty($pickupLocation->location_name)) {
                    $consignment->setPickupLocationName($pickupLocation->location_name);
                }
                if (!empty($pickupLocation->location_code)) {
                    $consignment->setPickupLocationCode($pickupLocation->location_code);
                }
                if (!empty($pickupLocation->retail_network_id)) {
                    $consignment->setRetailNetworkId($pickupLocation->retail_network_id);
                }
            }
        }

        if ($consignment instanceof PostNLConsignment
            && isset($delivery_setting->shipmentOptions->only_recipient)
            && $delivery_setting->shipmentOptions->only_recipient) {
            $consignment->setOnlyRecipient(true);
        }
        // Signature is required for pickup delivery type
        if ($consignment->getDeliveryType() === AbstractConsignment::DELIVERY_TYPE_PICKUP
            || (!empty($delivery_setting->shipmentOptions->signature)
                && !empty($carrierSettings['allowSignature'][$countryCode]))) {
            $consignment->setSignature(true);
        }
        $consignment->setLabelDescription(
            $this->getLabelParams($order, Configuration::get(Constant::LABEL_DESCRIPTION_CONFIGURATION_NAME))
        );
        if ((int) $consignment->getPackageType() === AbstractConsignment::PACKAGE_TYPE_DIGITAL_STAMP) {
            $consignment->setTotalWeight((new OrderTotalWeight())->provide((int) $order['id_order']));
        }

        if (Country::getIdZone($order['id_country']) != 1
            && $this->configuration::get(Constant::CUSTOMS_FORM_CONFIGURATION_NAME) != 'No') { //NON EU zone
            $products = OrderLabel::getCustomsOrderProducts($order['id_order']);
            $consignment->setAgeCheck(false); //The age check is not possible with an EU shipment or world shipment
            if ($products !== false) {
                foreach ($products as $product) {
                    $item = (new MyParcelCustomsItem());
                    $item->setAmount($product['product_quantity']);
                    $item->setClassification(
                        ProductConfigurationProvider::get(
                            $product['product_id'],
                            Constant::CUSTOMS_CODE_CONFIGURATION_NAME)
                                ?? (int) $this->configuration::get(Constant::DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME
                        )
                    );
                    $item->setCountry(
                        ProductConfigurationProvider::get(
                            $product['product_id'],
                            Constant::CUSTOMS_ORIGIN_CONFIGURATION_NAME)
                                ?? $this->configuration::get(Constant::DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME
                        )
                    );

                    $item->setDescription($product['product_name']);
                    $item->setItemValue(Tools::ps_round($product['unit_price_tax_incl'] * 100)); // cents
                    $item->setWeight($product['product_weight']);
                    $consignment->addItem($item);
                }
            }
        }

        return $consignment;
    }

    private function MYPARCELBE_RECIPIENT_ONLY(AbstractConsignment $consignment)
    {
        if ($consignment instanceof PostNLConsignment) {
            return $consignment->setOnlyRecipient(true);
        }

        return false;
    }

    private function MYPARCELBE_AGE_CHECK(AbstractConsignment $consignment)
    {
        return $consignment->setAgeCheck(true);
    }

    private function MYPARCELBE_PACKAGE_TYPE(AbstractConsignment $consignment)
    {
        return $consignment->setPackageType($this->request['packageType']);
    }

    private function MYPARCELBE_INSURANCE(AbstractConsignment $consignment)
    {
        $insuranceValue = 0;
        if (isset($postValues['insuranceAmount'])) {
            if (strpos($postValues['insuranceAmount'], 'amount') !== false) {
                $insuranceValue = (int) str_replace(
                    'amount',
                    '',
                    $postValues['insuranceAmount']
                );
            } else {
                $insuranceValue = (int) $postValues['insurance-amount-custom-value'] ?? 0;
                if (empty($insuranceValue)) {
                    throw new Exception('Insurance value cannot be empty');
                }
            }
        }

        if ($this->module->isBE() && $insuranceValue > 500) {
            $this->module->controller->errors[] = $this->module->l(
                'Insurance value cannot more than € 500',
                'consignmentfactory'
            );
            throw new Exception('Insurance value cannot more than € 500');
        }
        if ($this->module->isNL() && $insuranceValue > 5000) {
            $this->module->controller->errors[] = $this->module->l(
                'Insurance value cannot more than € 5000',
                'consignmentfactory'
            );
            throw new Exception('Insurance value cannot more than € 5000');
        }

        return $consignment->setInsurance($insuranceValue);
    }

    private function MYPARCELBE_RETURN_PACKAGE(AbstractConsignment $consignment)
    {
        return $consignment->setReturn(true);
    }

    private function MYPARCELBE_SIGNATURE_REQUIRED(AbstractConsignment $consignment)
    {
        if (!$consignment instanceof DPDConsignment) {
            return $consignment->setSignature(true);
        }

        return false;
    }

    private function MYPARCELBE_PACKAGE_FORMAT(AbstractConsignment $consignment)
    {
        return $consignment->setLargeFormat($this->request['packageFormat'] == 2);
    }

    private function getLabelParams(array $order, string $labelParams, string $labelDefaultParam = 'id_order'): string
    {
        if (!isset($order[$labelDefaultParam])) {
            $labelDefaultParam = 'id_order';
        }
        if (empty(trim($labelParams))) {
            return $order[$labelDefaultParam];
        }

        $pattern = '/\{[a-zA-Z_]+\.[a-zA-Z_]+\}/m';

        preg_match_all($pattern, $labelParams, $matches, PREG_SET_ORDER, 0);

        $keys = [];
        if (!empty($matches)) {
            foreach ($matches as $result) {
                foreach ($result as $value) {
                    $key = trim($value, '{}');
                    $key = explode('.', $key);
                    if (count($key) === 1) {
                        $keys[$value] = $key;
                        continue;
                    }
                    if (count($key) === 2) {
                        if ($key[0] === 'order') {
                            $keys[$value] = $key[1];
                            continue;
                        }
                    }
                }
            }
        }
        if (empty($keys)) {
            return $order[$labelDefaultParam];
        }
        foreach ($keys as $index => $key) {
            if (!isset($order[$key])) {
                unset($keys[$index]);
            }
            $labelParams = str_replace($index, $order[$key], $labelParams);
        }

        return trim($labelParams);
    }

    public function getMyParcelCarrierId(int $id_carrier): int
    {
        $carrier = new \Carrier($id_carrier);
        if (!\Validate::isLoadedObject($carrier)) {
            throw new Exception('No carrier found.');
        }
        if ($carrier->id_reference == $this->configuration::get(Constant::POSTNL_CONFIGURATION_NAME)) {
            return PostNLConsignment::CARRIER_ID;
        }

        if ($carrier->id_reference == $this->configuration::get(Constant::BPOST_CONFIGURATION_NAME)) {
            return BpostConsignment::CARRIER_ID;
        }

        if ($carrier->id_reference == $this->configuration::get(Constant::DPD_CONFIGURATION_NAME)) {
            return DPDConsignment::CARRIER_ID;
        }

        throw new Exception('Undefined carrier');
    }
}
