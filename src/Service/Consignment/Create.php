<?php

namespace Gett\MyparcelBE\Service\Consignment;

use Gett\MyparcelBE\OrderLabel;
use Doctrine\ORM\EntityManagerInterface;
use Gett\MyparcelBE\Service\MyparcelStatusProvider;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class Create
{
    private $entityManager;
    private $status_provider;
    private $consignment_factory;

    public function __construct(EntityManagerInterface $entityManager, MyparcelStatusProvider $status_provider, \Gett\MyparcelBE\Factory\Consignment\ConsignmentFactory $factory)
    {
        $this->entityManager = $entityManager;
        $this->status_provider = $status_provider;
        $this->consignment_factory = $factory;
    }

    public function createLabels(array $orders)
    {
        if (isset($orders['id_order'])) {
            $order = $orders;
            $myParcelCollection = $this->consignment_factory->fromOrder($order);
        } else {
            $myParcelCollection = $this->consignment_factory->fromOrders($orders);
        }

        $this->process($myParcelCollection);

        return true;
    }

    public function createReturnLabel(array $order)
    {
        $myParcelCollection = $this->consignment_factory->fromOrder($order);

        $this->process($myParcelCollection, true);

        return true;
    }

    private function process(MyParcelCollection $collection, $return = false)
    {
        $collection->setPdfOfLabels();
        if ($return) {
            $collection->sendReturnLabelMails();
        }
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
            $orderLabel->status = $this->status_provider->getStatus($consignment->getStatus());
            $orderLabel->add();
            //$paymentUrl = $myParcelCollection->setPdfOfLabels()->getLabelPdf()['data']['payment_instructions']['0']['payment_url'];
        }
    }
}
