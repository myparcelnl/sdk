<?php

namespace Gett\MyParcel\Module\Configuration;

use Context;
use OrderState;

class OrderForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Order Settings');
    }

    protected function getFields(): array
    {
        $order_states = [
                [
                    "id_order_state" => 0,
                    "name" => "Off"
                ]
            ] + OrderState::getOrderStates((int)Context::getContext()->language->id);

        return [
            "MY_PARCEL_LABEL_CREATED_ORDER_STATUS" => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label created'),
                'name' => 'MY_PARCEL_LABEL_CREATED_ORDER_STATUS',
                'options' => array(
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                )
            ],
            "MY_PARCEL_LABEL_SCANNED_ORDER_STATUS" => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label scanned'),
                'name' => 'MY_PARCEL_LABEL_SCANNED_ORDER_STATUS',
                'default_value' => "0",
                'options' => array(
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                )
            ],
            "MY_PARCEL_DELIVERED_ORDER_STATUS" => [
                'type' => 'select',
                'label' => $this->module->l('Order status when delivered'),
                'name' => 'MY_PARCEL_DELIVERED_ORDER_STATUS',
                'options' => array(
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                )
            ],
            "MY_PARCEL_IGNORE_ORDER_STATUS" => [
                'type' => 'select',
                'label' => $this->module->l('Ignore order statuses'),
                'name' => 'MY_PARCEL_IGNORE_ORDER_STATUS',
                'options' => array(
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                )
            ],
            "MY_PARCEL_STATUS_CHANGE_MAIL" => [
                'type' => 'switch',
                'label' => $this->module->l('Order status mail'),
                'name' => 'MY_PARCEL_STATUS_CHANGE_MAIL',
                'required' => false,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled'),
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled'),
                    ),
                ),
            ],
            "MY_PARCEL_ORDER_NOTIFICATION_AFTER" => [
                'type' => 'select',
                'label' => $this->module->l('Send notification after'),
                'name' => 'MY_PARCEL_ORDER_NOTIFICATION_AFTER',
                'options' => array(
                    'query' => [
                        ["id" => 'first_scan', 'name' => $this->module->l('Label has passed first scan')],
                        ["id" => 'printed', 'name' => $this->module->l('Label is printed')]
                    ],
                    'id' => 'id',
                    'name' => 'name',
                )
            ],
            "MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS" => [
                'type' => 'switch',
                'label' => $this->module->l('Order status mail'),
                'name' => 'MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS',
                'required' => false,
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled'),
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled'),
                    ),
                ),
            ]
        ];
    }

}