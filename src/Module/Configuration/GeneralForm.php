<?php

namespace Gett\MyParcel\Module\Configuration;

class GeneralForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('General Settings');
    }

    protected function getFields(): array
    {
        return [
            'MY_PARCEL_SHARE_CUSTOMER_EMAIL' => [
                'type' => 'switch',
                'label' => $this->module->l('Share customer email with MyParcel'),
                'name' => 'MY_PARCEL_SHARE_CUSTOMER_EMAIL',
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
            'MY_PARCEL_SHARE_CUSTOMER_PHONE' => [
                'type' => 'switch',
                'label' => $this->module->l('Share customer phone with MyParcel'),
                'name' => 'MY_PARCEL_SHARE_CUSTOMER_PHONE',
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
