<?php

namespace Gett\MyParcel\Service\Consignment;

use Doctrine\ORM\EntityManagerInterface;
use Gett\MyParcel\Entity\MyparcelOrderLabel;
use Gett\MyParcel\Service\MyparcelStatusProvider;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class Create
{
    private $entityManager;
    private $status_provider;
    private $consignment_factory;

    public function __construct(EntityManagerInterface $entityManager, MyparcelStatusProvider $status_provider, \Gett\MyParcel\Factory\Consignment\ConsignmentFactory $factory)
    {
        $this->entityManager = $entityManager;
        $this->status_provider = $status_provider;
        $this->consignment_factory = $factory;
    }

    public function createLabels(array $orders)
    {
        if (isset($orders['id_order'])) {
            $myParcelCollection = $this->consignment_factory->fromOrder($orders);
        } else {
            $myParcelCollection = $this->consignment_factory->fromOrders($orders);
        }

        $this->process($myParcelCollection);

        return true;
    }

    private function process(MyParcelCollection $collection)
    {
        foreach ($collection->setPdfOfLabels() as $consignment) {
            $orderLabel = (new MyparcelOrderLabel())
                ->setIdLabel($consignment->getConsignmentId())
                ->setIdOrder($consignment->getReferenceId())
                ->setBarcode($consignment->getBarcode())
                ->setTrackLink($consignment->getBarcodeUrl(
                    $consignment->getBarcode(),
                    $consignment->getPostalCode(),
                    $consignment->getCountry()
                ))
                ->setStatus($this->status_provider->getStatus($consignment->getStatus()))
            ;
            //$paymentUrl = $myParcelCollection->setPdfOfLabels()->getLabelPdf()['data']['payment_instructions']['0']['payment_url'];
            $this->entityManager->persist($orderLabel);
        }

        $this->entityManager->flush();
    }
}
