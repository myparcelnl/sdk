<?php

namespace Gett\MyParcel\Module\Configuration;

use Context;
use Country;
use Gett\MyParcel\Constant;

class CustomsForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Customs Settings');
    }

    protected function getFields(): array
    {
        return [
            Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default customs form'),
                'name' => Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        ['id' => 'No', 'name' => $this->module->l('Do not automatically generate customs form')],
                        ['id' => 'Add', 'name' => $this->module->l('Add this product to customs form')],
                        ['id' => 'Skip', 'name' => $this->module->l('Skip this product on customs form')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME => [
                'type' => 'text',
                'label' => $this->module->l('Default customs code'),
                'name' => Constant::MY_PARCEL_DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME,
            ],
            Constant::MY_PARCEL_DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default customs origin'),
                'name' => Constant::MY_PARCEL_DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME,
                'options' => [
                    'query' => Country::getCountries(Context::getContext()->language->id),
                    'id' => 'iso_code',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Default customs age check'),
                'name' => Constant::MY_PARCEL_CUSTOMS_AGE_CHECK_CONFIGURATION_NAME,
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
