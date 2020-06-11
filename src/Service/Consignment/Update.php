<?php

namespace Gett\MyParcelBE\Service\Consignment;

use Gett\MyParcelBE\OrderLabel;
use Doctrine\ORM\EntityManagerInterface;
use Gett\MyParcelBE\Service\MyparcelStatusProvider;
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
            $order_label = OrderLabel::findByLabelId($consignment->getConsignmentId());
            $order_label->status = $this->status_provider->getStatus($consignment->getStatus());
            $order_label->save();
        }

        return true;
    }
}
