<?php

use Gett\MyParcel\OrderLabel;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

if (file_exists(_PS_MODULE_DIR_ . 'myparcel/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_ . 'myparcel/vendor/autoload.php';
}

class LabelController extends ModuleAdminControllerCore
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        switch (Tools::getValue('action')) {
            case 'return':
                $this->processReturn();
                break;
        }

        die();
        //Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
    }

    public function processReturn()
    {
        $order = new Order(Tools::getValue('id_order'));
        if (Validate::isLoadedObject($order)) {
            $address = new Address($order->id_address_delivery);
            $customer = new Customer($order->id_customer);
            $consignment = (\MyParcelNL\Sdk\src\Factory\ConsignmentFactory::createByCarrierId(\MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment::CARRIER_ID))
                ->setApiKey(Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME))
                ->setReferenceId($order->id)
                ->setCountry(CountryCore::getIsoById($address->id_country))
                ->setPerson($address->firstname . ' '. $address->lastname)
                ->setFullStreet($address->address1)
                ->setPostalCode($address->postcode)
                ->setCity($address->city)
                ->setEmail($customer->email);

            $myParcelCollection = (new \MyParcelNL\Sdk\src\Helper\MyParcelCollection())
                ->addConsignment($consignment)
                ->setPdfOfLabels()->sendReturnLabelMails();

            $consignment = $myParcelCollection->first();
            $orderLabel = new OrderLabel();
            $orderLabel->id_label = $consignment->getConsignmentId();
            $orderLabel->id_order = $consignment->getReferenceId();
            $orderLabel->barcode = $consignment->getBarcode();
            $orderLabel->track_link = $consignment->getBarcodeUrl(
                $consignment->getBarcode(),
                $consignment->getPostalCode(),
                $consignment->getCountry()
            );
            $status_provider = new \Gett\MyParcel\Service\MyparcelStatusProvider();
            $orderLabel->new_order_state = $consignment->getStatus();
            $orderLabel->status = $status_provider->getStatus($consignment->getStatus());
            $orderLabel->add();
        }
    }

    public function processCreate()
    {
        $factory = new \Gett\MyParcel\Factory\Consignment\ConsignmentFactory(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
      );
        $order = \Gett\MyParcel\OrderLabel::getDataForLabelsCreate(Tools::getValue('create_label')['order_ids']);

        $collection = $factory->fromOrder($order[0]);

        $collection->setPdfOfLabels();

        $status_provider = new \Gett\MyParcel\Service\MyparcelStatusProvider();
        foreach ($collection as $consignment) {
            $orderLabel = new \Gett\MyParcel\OrderLabel();
            $orderLabel->id_label = $consignment->getConsignmentId();
            $orderLabel->id_order = $consignment->getReferenceId();
            $orderLabel->barcode = $consignment->getBarcode();
            $orderLabel->track_link = $consignment->getBarcodeUrl(
                $consignment->getBarcode(),
                $consignment->getPostalCode(),
                $consignment->getCountry()
            );
            $orderLabel->new_order_state = $consignment->getStatus();
            $orderLabel->status = $status_provider->getStatus($consignment->getStatus());
            $orderLabel->add();
            //$paymentUrl = $myParcelCollection->setPdfOfLabels()->getLabelPdf()['data']['payment_instructions']['0']['payment_url'];
        }

        die();
    }

    public function processCreateb()
    {
        $factory = new \Gett\MyParcel\Factory\Consignment\ConsignmentFactory(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
        );

        $orders = \Gett\MyParcel\OrderLabel::getDataForLabelsCreate(array_keys(Tools::getValue('data')));

        $collection = $factory->fromOrders($orders);

        $collection->setPdfOfLabels();
        $status_provider = new \Gett\MyParcel\Service\MyparcelStatusProvider();
        foreach ($collection as $consignment) {
            $orderLabel = new OrderLabel();
            $orderLabel->id_label = $consignment->getConsignmentId();
            $orderLabel->id_order = $consignment->getReferenceId();
            $orderLabel->barcode = $consignment->getBarcode();
            $orderLabel->track_link = $consignment->getBarcodeUrl(
                $consignment->getBarcode(),
                $consignment->getPostalCode(),
                $consignment->getCountry()
            );
            $orderLabel->new_order_state = $consignment->getStatus();
            $orderLabel->status = $status_provider->getStatus($consignment->getStatus());
            $orderLabel->add();
            //$paymentUrl = $myParcelCollection->setPdfOfLabels()->getLabelPdf()['data']['payment_instructions']['0']['payment_url'];
        }
    }

    public function processRefresh()
    {
        $id_labels = OrderLabel::getOrderLabels(Tools::getValue('order_ids'));

        $collection = MyParcelCollection::findMany($id_labels,             \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));
        $collection->setLinkOfLabels();
        $status_provider = new \Gett\MyParcel\Service\MyparcelStatusProvider();

        foreach ($collection as $consignment) {
            $order_label = OrderLabel::findByLabelId($consignment->getConsignmentId());
            $order_label->status = $status_provider->getStatus($consignment->getStatus());
            $order_label->save();
        }

        die();
    }

    public function processPrint()
    {
        $labels = OrderLabel::getOrderLabels(Tools::getValue('order_ids'));

        $service = new \Gett\MyParcel\Service\Consignment\Download(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
        );
        $service->downloadLabel($labels);
    }
}