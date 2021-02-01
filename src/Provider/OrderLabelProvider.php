<?php

namespace Gett\MyparcelBE\Provider;

use Gett\MyparcelBE\Module\Carrier\Provider\CarrierSettingsProvider;
use Gett\MyparcelBE\OrderLabel;
use Order;

class OrderLabelProvider
{
    protected $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function provideLabels(int $order_id, array $label_ids = [])
    {
        $labels = OrderLabel::getOrderLabels($order_id, $label_ids);
        $order = new Order($order_id);
        $carrierSettingsProvider = new CarrierSettingsProvider($this->module);
        $carrierSettings = $carrierSettingsProvider->provide($order->id_carrier);
        if (!empty($labels)) {
            foreach ($labels as &$label) {
                $label['ALLOW_DELIVERY_FORM'] = $carrierSettings['delivery']['ALLOW_FORM'];
                $label['ALLOW_RETURN_FORM'] = $carrierSettings['return']['ALLOW_FORM'];
            }
        }

        return $labels;
    }

    public function provideOrderId(int $labelId): int
    {
        return OrderLabel::getOrderIdByLabelId($labelId);
    }

}
