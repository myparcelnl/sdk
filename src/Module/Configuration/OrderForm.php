<?php

namespace Gett\MyParcelBE\Module\Configuration;

use Context;
use Gett\MyParcelBE\Constant;
use OrderState;

class OrderForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Order Settings', 'orderform');
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
            Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label created', 'orderform'),
                'name' => Constant::LABEL_CREATED_ORDER_STATUS_CONFIGURATION_NAME,
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when label scanned', 'orderform'),
                'name' => Constant::LABEL_SCANNED_ORDER_STATUS_CONFIGURATION_NAME,
                'default_value' => '0',
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Order status when delivered', 'orderform'),
                'name' => Constant::DELIVERED_ORDER_STATUS_CONFIGURATION_NAME,
                'options' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::IGNORE_ORDER_STATUS_CONFIGURATION_NAME => [
                'type' => 'checkbox',
                'label' => $this->module->l('Ignore order statuses', 'orderform'),
                'name' => Constant::IGNORE_ORDER_STATUS_CONFIGURATION_NAME,
                'multiple' => true,
                'values' => [
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name',
                ],
            ],
            Constant::STATUS_CHANGE_MAIL_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Order status mail', 'orderform'),
                'name' => Constant::STATUS_CHANGE_MAIL_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'orderform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'orderform'),
                    ],
                ],
            ],
            Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Send notification after', 'orderform'),
                'name' => Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        [
                            'id' => 'first_scan',
                            'name' => $this->module->l('Label has passed first scan', 'orderform')
                        ],
                        [
                            'id' => 'printed',
                            'name' => $this->module->l('Label is printed', 'orderform')
                        ],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Automatic set order state to ‘sent’ for digital stamp', 'orderform'),
                'name' => Constant::SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'orderform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'orderform'),
                    ],
                ],
            ],
        ];
    }
}
