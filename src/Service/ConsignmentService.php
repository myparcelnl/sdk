<?php

namespace Gett\MyParcel\Service;

use Doctrine\ORM\EntityManagerInterface;
use Gett\MyParcel\Entity\MyparcelOrderLabel;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Factory\ConsignmentFactory;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;

class ConsignmentService
{
    private $api_key;
    private $entityManager;

    public function __construct(string $api_key, EntityManagerInterface $entityManager)
    {
        $this->api_key = $api_key;
        $this->entityManager = $entityManager;
    }

    public function createLabels(array $orders)
    {
        $myParcelCollection = (new MyParcelCollection())
            ->setUserAgent('prestashop', '1.0')
        ;

        foreach ($orders as $order) {
            $consignment = (ConsignmentFactory::createByCarrierId(PostNLConsignment::CARRIER_ID))  //TODO fetch carrier
                ->setApiKey($this->api_key)
                ->setReferenceId($order['id_order'])
                ->setCountry($order['iso_code'])
                ->setPerson($order['person'])
                ->setFullStreet($order['full_street'])
                ->setPostalCode($order['postcode'])
                ->setCity($order['city'])
                ->setEmail($order['email'])
            ;

            $myParcelCollection
                ->addConsignment($consignment)
            ;
            $consignment->getReferenceId();
        }

        $paymentUrl = $myParcelCollection->setPdfOfLabels()->getLabelPdf()['data']['payment_instructions']['0']['payment_url'];

        foreach ($myParcelCollection->setPdfOfLabels() as $consignment) {
            $orderLabel = (new MyparcelOrderLabel())
                ->setIdLabel($consignment->getConsignmentId())
                ->setIdOrder($consignment->getReferenceId())
                ->setPaymentUrl($paymentUrl)
                ->setStatus(0)
            ;

            $this->entityManager->persist($orderLabel);
        }
        $this->entityManager->flush();
        die(123);
    }
}
