<?php

namespace Gett\MyParcel\Factory\Consignment;

use Gett\MyParcel\Constant;
use Gett\MyParcel\OrderLabel;
use Symfony\Component\HttpFoundation\Request;
use Gett\MyParcel\Carrier\PackageTypeCalculator;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;
use Gett\MyParcel\Service\ProductConfigurationProvider;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;

class ConsignmentFactory
{
    private $api_key;
    private $request;
    private $configuration;

    public function __construct(string $api_key, Request $request, ConfigurationInterface $configuration)
    {
        $this->api_key = $api_key;
        $this->configuration = $configuration;
        $this->request = $request;
    }

    public function fromOrders(array $orders): MyParcelCollection
    {
        $myParcelCollection = (new MyParcelCollection())
            ->setUserAgent('prestashop', '1.0')
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
            ->setUserAgent('prestashop', '1.0')
        ;

        for ($i = 0; $i < $this->request->get('number'); ++$i) {
            $consignment = $this->initConsignment($order);
            foreach (Constant::SINGLE_LABEL_CREATION_OPTIONS as $option) {
                if ($this->request->get($option)) {
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
        $consignment = (\MyParcelNL\Sdk\src\Factory\ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))  //TODO fetch carrier
            ->setApiKey($this->api_key)
            ->setReferenceId($order['id_order'])
            ->setCountry($order['iso_code'])
            ->setPerson($order['person'])
            ->setFullStreet($order['full_street'])
            ->setPostalCode($order['postcode'])
            ->setCity($order['city'])
            ->setContents(1)
            ->setInvoice(123)
        ;

        if ($package_type = $this->request->get('MY_PARCEL_PACKAGE_TYPE')) {
            $consignment->setPackageType($package_type);
        } else {
            $consignment->setPackageType(PackageTypeCalculator::getOrderPackageType($order['id_order'], $order['id_carrier']));
        }

        if ($this->configuration->get(Constant::MY_PARCEL_SHARE_CUSTOMER_EMAIL_CONFIGURATION_NAME)) {
            $consignment->setEmail($order['email']);
        }

        if ($this->configuration->get(Constant::MY_PARCEL_SHARE_CUSTOMER_PHONE_CONFIGURATION_NAME)) {
            $consignment->setPhone($order['phone']);
        }
        $delivery_setting = json_decode($order['delivery_settings']);
        $consignment->setDeliveryDate(date('Y-m-d H:i:s', strtotime($delivery_setting->date)));

        if ($delivery_setting->isPickup) {
            $consignment->setDeliveryType(AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[AbstractConsignment::DELIVERY_TYPE_PICKUP_NAME]);
            $consignment->setPickupNetworkId($delivery_setting->pickupLocation->retail_network_id);
        } else {
            $consignment->setDeliveryType(AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[$delivery_setting->deliveryType]);
        }

        if (isset($delivery_setting->shipmentOptions->only_recipient) && $delivery_setting->shipmentOptions->only_recipient) {
            $consignment->setOnlyRecipient(true);
        }
        if (isset($delivery_setting->shipmentOptions->signature) && $delivery_setting->shipmentOptions->signature) {
            $consignment->setSignature(true);
        }
        $consignment->setLabelDescription(
            isset($order[\Configuration::get(Constant::MY_PARCEL_LABEL_DESCRIPTION_CONFIGURATION_NAME)]) ? $order[\Configuration::get(Constant::MY_PARCEL_LABEL_DESCRIPTION_CONFIGURATION_NAME)] : $order['id_order']
        );

        if (\CountryCore::getIdZone($order['id_country']) != 1 && $this->configuration->get(Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME) != 'No') { //NON EU zone
            $products = OrderLabel::getCustomsOrderProducts($order['id_order']);
            $consignment->setAgeCheck(false); //The age check is not possible with an EU shipment or world shipment
            if ($products !== false) {
                foreach ($products as $product) {
                    $item = (new MyParcelCustomsItem());
                    $item->setAmount($product['product_quantity']);
                    $item->setClassification(
                        ProductConfigurationProvider::get($product['product_id'], Constant::MY_PARCEL_CUSTOMS_CODE_CONFIGURATION_NAME) ?? (int) $this->configuration->get(Constant::MY_PARCEL_DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME)
                    );
                    $item->setCountry(
                        ProductConfigurationProvider::get($product['product_id'], Constant::MY_PARCEL_CUSTOMS_ORIGIN_CONFIGURATION_NAME) ?? $this->configuration->get(Constant::MY_PARCEL_DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME)
                    );

                    $item->setDescription($product['product_name']);
                    $item->setItemValue($product['product_price']);
                    $item->setWeight($product['product_weight']);
                    $consignment->addItem($item);
                }
            }
        }

        return $consignment;
    }

    private function MY_PARCEL_RECIPIENT_ONLY(AbstractConsignment $consignment)
    {
        return $consignment->setOnlyRecipient(true);
    }

    private function MY_PARCEL_AGE_CHECK(AbstractConsignment $consignment)
    {
        return $consignment->setAgeCheck(true);
    }

    private function MY_PARCEL_PACKAGE_TYPE(AbstractConsignment $consignment)
    {
        return $consignment->setPackageType($this->request->get(__FUNCTION__));
    }

    private function MY_PARCEL_INSURANCE(AbstractConsignment $consignment)
    {
        $insurance = $this->request->get('insurance-higher-amount') * 100 ?? $this->request->get('insurance-value-option');

        return $consignment->setInsurance($insurance);
    }

    private function MY_PARCEL_RETURN_PACKAGE(AbstractConsignment $consignment)
    {
        return $consignment->setReturn(true);
    }

    private function MY_PARCEL_SIGNATURE_REQUIRED(AbstractConsignment $consignment)
    {
        return $consignment->setSignature(true);
    }

    private function MY_PARCEL_PACKAGE_FORMAT(AbstractConsignment $consignment)
    {
        return $consignment->setLargeFormat($this->request->get(__FUNCTION__) == 2);
    }
}
