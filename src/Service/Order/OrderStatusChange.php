<?php

namespace Gett\MyParcel\Service\Order;

use PrestaShop\PrestaShop\Core\ConfigurationInterface;

class OrderStatusChange
{
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function changeOrderStatus()
    {
    }
}
