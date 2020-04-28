<?php

use Gett\MyParcel\OrderLabel;

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

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminOrders'));
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
}