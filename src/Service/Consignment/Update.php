<?php

namespace Gett\MyParcel\Service\Consignment;

use Doctrine\ORM\EntityManagerInterface;
use Gett\MyParcel\Entity\MyparcelOrderLabel;
use Gett\MyParcel\Service\MyparcelStatusProvider;
use MyParcelNL\Sdk\src\Helper\MyParcelCollection;

class Update
{
    private $api_key;
    private $entity_manager;
    private $status_provider;

    public function __construct(string $api_key, EntityManagerInterface $entityManager, MyparcelStatusProvider $status_provider)
    {
        $this->api_key = $api_key;
        $this->entity_manager = $entityManager;
        $this->status_provider = $status_provider;
    }

    public function updateLabel(array $id_labels)
    {
        $collection = MyParcelCollection::findMany($id_labels, $this->api_key);
        $collection->setLinkOfLabels();

        foreach ($collection as $consignment) {
            $order_label = $this->entity_manager->getRepository(MyparcelOrderLabel::class)->findOneBy(['id_label' => $consignment->getConsignmentId()]);
            $order_label->setStatus($this->status_provider->getStatus($consignment->getStatus()));
            $this->entity_manager->persist($order_label);
        }

        $this->entity_manager->flush();

        return true;
    }
}
