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

            try {
                $consignment = (\MyParcelNL\Sdk\src\Factory\ConsignmentFactory::createByCarrierId(\MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment::CARRIER_ID))
                    ->setApiKey(Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME))
                    ->setReferenceId($order->id)
                    ->setCountry(CountryCore::getIsoById($address->id_country))
                    ->setPerson($address->firstname . ' ' . $address->lastname)
                    ->setFullStreet($address->address1)
                    ->setPostalCode($address->postcode)
                    ->setCity($address->city)
                    ->setEmail($customer->email)
                    ->setContents(1)
                ;

                $myParcelCollection = (new \MyParcelNL\Sdk\src\Helper\MyParcelCollection())
                    ->addConsignment($consignment)
                    ->setPdfOfLabels()->sendReturnLabelMails();
                \Gett\MyParcel\Logger\Logger::addLog($myParcelCollection->toJson());
            } catch (Exception $e) {
                \Gett\MyParcel\Logger\Logger::addLog($e->getMessage(), true);
                header('HTTP/1.1 500 Internal Server Error', true, 500);
                die($this->module->l('A error occurred in the MyParcel module, please try again.'));
            }

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

    public function ajaxProcessCreate()
    {
        $factory = new \Gett\MyParcel\Factory\Consignment\ConsignmentFactory(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
        );
        $createLabelIds = Tools::getValue('create_label');
        if (empty($createLabelIds['order_ids'])) {
            die($this->trans('No order ID found', [], 'Modules.Myparcel.Error'));
        }
        $order = \Gett\MyParcel\OrderLabel::getDataForLabelsCreate($createLabelIds['order_ids']);
        if (empty($order)) {
            $this->errors[] = $this->trans('No order found.', [], 'Modules.Myparcel.Error');
            die(json_encode(['hasError' => true, 'errors' => $this->errors]));
        }

        try {
            $collection = $factory->fromOrder($order[0]);
            $consignments = $collection->getConsignments();
            if (!empty($consignments)) {
                foreach ($consignments as &$consignment) {
                    $consignment->delivery_date = $this->fixPastDeliveryDate($consignment->delivery_date);
                }
            }
            \Gett\MyParcel\Logger\Logger::addLog($collection->toJson());
            $collection->setLinkOfLabels();
            if (Tools::getValue(\Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME)) {
                $collection->sendReturnLabelMails();
            }
        } catch (Exception $e) {
            \Gett\MyParcel\Logger\Logger::addLog($e->getMessage(), true);
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->module->l('A error occurred in the MyParcel module, please try again.'));
        }

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

        die(json_encode(['hasError' => false]));
    }

    public function processCreateb()
    {
        $factory = new \Gett\MyParcel\Factory\Consignment\ConsignmentFactory(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
        );
        if (!Tools::getValue('data')) {
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->trans('Can\'t create label for these orders', [], 'Modules.Myparcel.Error'));
        }
        $orders = \Gett\MyParcel\OrderLabel::getDataForLabelsCreate(array_keys(Tools::getValue('data')));
        if (empty($orders)) {
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->trans('Can\'t create label for these orders', [], 'Modules.Myparcel.Error'));
        }
        try {
            $collection = $factory->fromOrders($orders);
            foreach (Tools::getValue('data') as $key => $item) {
                $options = json_decode($item);
                $consignment = $collection->getConsignmentsByReferenceId($key)->getOneConsignment();
                if ($options->package_type && count($consignment->getItems()) == 0) {
                    $consignment->setPackageType($options->package_type);
                } else {
                    $consignment->setPackageType(1);
                }
                if ($options->only_to_recepient == 1) {
                    $consignment->setOnlyRecipient(true);
                } else {
                    $consignment->setOnlyRecipient(false);
                }
                if ($options->age_check == 1 && count($consignment->getItems()) == 0) {
                    $consignment->setAgeCheck(true);
                } else {
                    $consignment->setAgeCheck(false);
                }
                if ($options->signature == 1) {
                    $consignment->setSignature(true);
                } else {
                    $consignment->setSignature(false);
                }
                if ($options->insurance) {
                    $consignment->setInsurance(2500);
                }
            }
            $collection->setPdfOfLabels();
            \Gett\MyParcel\Logger\Logger::addLog($collection->toJson());
        } catch (Exception $e) {
            \Gett\MyParcel\Logger\Logger::addLog($e->getMessage(), true);
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->module->l('A error occurred in the MyParcel module, please try again.'));
        }

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
        if (empty($id_labels)) {
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->trans('No created labels found', [], 'Modules.Myparcel.Error'));
        }
        try {
            $collection = MyParcelCollection::findMany($id_labels, \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));

            $collection->setLinkOfLabels();
            \Gett\MyParcel\Logger\Logger::addLog($collection->toJson());
        } catch (Exception $e) {
            \Gett\MyParcel\Logger\Logger::addLog($e->getMessage(), true);
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            die($this->module->l('A error occurred in the MyParcel module, please try again.'));
        }

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
        if (empty($labels)) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
        }
        $service = new \Gett\MyParcel\Service\Consignment\Download(
            \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME),
            Symfony\Component\HttpFoundation\Request::createFromGlobals(),
            new \PrestaShop\PrestaShop\Adapter\Configuration()
        );
        $service->downloadLabel($labels);
    }

    public function processUpdatelabel()
    {
        try {
            $collection = MyParcelCollection::find(Tools::getValue('labelId'), \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_API_KEY_CONFIGURATION_NAME));
            $collection->setLinkOfLabels();
            \Gett\MyParcel\Logger\Logger::addLog($collection->toJson());
        } catch (Exception $e) {
            \Gett\MyParcel\Logger\Logger::addLog($e->getMessage(), true);
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
        }

        $status_provider = new \Gett\MyParcel\Service\MyparcelStatusProvider();

        foreach ($collection as $consignment) {
            $order_label = OrderLabel::findByLabelId($consignment->getConsignmentId());
            $order_label->status = $status_provider->getStatus($consignment->getStatus());
            $order_label->save();
        }

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
    }

    public function fixPastDeliveryDate(?string $deliveryDate): ?string
    {
        if (!$deliveryDate) {
            return $deliveryDate;
        }
        $tomorrow = new \DateTime('tomorrow');
        try {
            $oldDate = new \DateTime($deliveryDate);
        } catch (Exception $e) {
            return $deliveryDate;
        }
        $tomorrow->setTime(0, 0, 0, 0);
        $oldDate->setTime(0, 0, 0, 0);
        if ($tomorrow > $oldDate) {
            $deliveryDate = null; //$tomorrow->format('Y-m-d H:i:s');
        }

        return $deliveryDate;
    }
}
