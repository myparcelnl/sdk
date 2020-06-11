<?php

namespace Gett\MyparcelBE\Listener;

use Gett\MyparcelBE\Constant;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gett\MyparcelBE\Entity\MyparcelOrderLabel;
use Gett\MyparcelBE\Service\Order\OrderStatusChange;
use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class MyparcelOrderLabelListener
{
    private $order_status_change;
    private $configuration;

    public function __construct(OrderStatusChange $order_status_change_service, ConfigurationInterface $configuration)
    {
        $this->order_status_change = $order_status_change_service;
        $this->configuration = $configuration;
    }

    public function prePersist(MyparcelOrderLabel $label, LifecycleEventArgs $event)
    {
        if ($status = $this->configuration->get(Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME)) {
            $this->order_status_change->changeOrderStatus($label->getIdOrder(), $status, Constant::STATUS_CHANGE_MAIL_CONFIGURATION_NAME);
        }
    }
}
