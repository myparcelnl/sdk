<?php

namespace Gett\MyparcelBE\Module\Configuration;

use Gett\MyparcelBE\Constant;

class GeneralForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('General Settings', 'generalform');
    }

    protected function getFields(): array
    {
        return [
            Constant::SHARE_CUSTOMER_EMAIL_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Share customer email with MyParcel', 'generalform'),
                'name' => Constant::SHARE_CUSTOMER_EMAIL_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'generalform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'generalform'),
                    ],
                ],
            ],
            Constant::SHARE_CUSTOMER_PHONE_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Share customer phone with MyParcel', 'generalform'),
                'name' => Constant::SHARE_CUSTOMER_PHONE_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'generalform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'generalform'),
                    ],
                ],
            ],
            Constant::USE_ADDRESS2_AS_STREET_NUMBER_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Use second address field in checkout as street number', 'generalform'),
                'name' => Constant::USE_ADDRESS2_AS_STREET_NUMBER_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'generalform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'generalform'),
                    ],
                ],
            ],
        ];
    }
}
