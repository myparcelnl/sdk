<?php

namespace Gett\MyParcel\Module\Configuration;

use Context;
use Gett\MyParcel\Constant;
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
                'id_order_state' => 0,
                'name' => 'Off',
            ],
        ] + OrderState::getOrderStates((int) Context::getContext()->language->id);

        return [
            Constant::MY_PARCEL_LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label created'),
                'name' => Constant::MY_PARCEL_LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME,
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label scanned'),
                'name' => Constant::MY_PARCEL_LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME,
                'default_value' => '0',
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_DELIVERED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when delivered'),
                'name' => Constant::MY_PARCEL_DELIVERED_ORDER_STATUS_CONFIGURATION_NAME,
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_IGNORE_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'checkbox',
                'label' => $this->module->l('Ignore order statuses'),
                'name' => Constant::MY_PARCEL_IGNORE_ORDER_STATUS_CONFIGURATION_NAME,
                'multiple' => true,
                'values' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_STATUS_CHANGE_MAIL_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Order status mail'),
                'name' => Constant::MY_PARCEL_STATUS_CHANGE_MAIL_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled'),
                    ],
                ],
            ],
            Constant::MY_PARCEL_ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Send notification after'),
                'name' => Constant::MY_PARCEL_ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        ['id' => 'first_scan', 'name' => $this->module->l('Label has passed first scan')],
                        ['id' => 'printed', 'name' => $this->module->l('Label is printed')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Automatic set order state to ‘sent’ for digital stamp'),
                'name' => Constant::MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled'),
                    ],
                ],
            ],
        ];
    }
}
