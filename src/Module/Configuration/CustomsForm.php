<?php

namespace Gett\MyParcel\Module\Configuration;

use Context;
use Country;

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
            'MY_PARCEL_CUSTOMS_FORM' => [
                'type' => 'select',
                'label' => $this->module->l('Default customs form'),
                'name' => 'MY_PARCEL_CUSTOMS_FORM',
                'options' => [
                    'query' => [
                        ['id' => 'No', 'name' => 'No'],
                        ['id' => 'Add', 'name' => 'ADD'],
                        ['id' => 'Skip', 'name' => 'Skip'],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_DEFAULT_CUSTOMS_CODE' => [
                'type' => 'text',
                'label' => $this->module->l('Default customs code'),
                'name' => 'MY_PARCEL_DEFAULT_CUSTOMS_CODE',
            ],
            'MY_PARCEL_DEFAULT_CUSTOMS_ORIGIN' => [
                'type' => 'select',
                'label' => $this->module->l('Default customs origin'),
                'name' => 'MY_PARCEL_DEFAULT_CUSTOMS_ORIGIN',
                'options' => [
                    'query' => Country::getCountries(Context::getContext()->language->id),
                    'id' => 'iso_code',
                    'name' => 'name',
                ],
            ],
            'MY_PARCEL_DEFAULT_CUSTOMS_AGE_CHECK' => [
                'type' => 'text',
                'label' => $this->module->l('Default customs age check'),
                'name' => 'MY_PARCEL_DEFAULT_CUSTOMS_AGE_CHECK',
            ],
        ];
    }
}
