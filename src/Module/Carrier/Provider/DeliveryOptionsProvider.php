<?php

namespace Gett\MyparcelBE\Module\Carrier\Provider;

use DateTime;
use Gett\MyparcelBE\OrderLabel;

class DeliveryOptionsProvider
{
    protected $nextDeliveryDate;
    protected $deliveryDate;

    public function provide(int $orderId)
    {
        $deliveryOptions = OrderLabel::getOrderDeliveryOptions($orderId);
        if (empty($deliveryOptions)) {
            $deliveryOptions = json_decode(json_encode(['date' => '']));
        }
        $this->nextDeliveryDate = new DateTime('tomorrow');// TODO: get next available delivery date
        if (!empty($deliveryOptions->date)) {
            $this->deliveryDate = new DateTime($deliveryOptions->date);
            $deliveryOptions->date = $this->deliveryDate->format('Y-m-d');
            if ($this->nextDeliveryDate > $this->deliveryDate) {
                $deliveryOptions->date = $this->nextDeliveryDate->format('Y-m-d');
            }
        } else {
            $deliveryOptions->date = $this->nextDeliveryDate->format('Y-m-d');
        }

        return $deliveryOptions;
    }

    public function provideWarningDisplay(int $orderId)
    {
        if (!$this->nextDeliveryDate) {
            $this->provide($orderId);
        }

        return $this->nextDeliveryDate > $this->deliveryDate;
    }
}
