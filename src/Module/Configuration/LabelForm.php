<?php

namespace Gett\MyParcel\Module\Configuration;

use Gett\MyParcel\Constant;

class LabelForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Label Settings');
    }

    protected function getFields(): array
    {
        return [
            Constant::MY_PARCEL_LABEL_DESCRIPTION_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Label description'),
                'name' => Constant::MY_PARCEL_LABEL_DESCRIPTION_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        ['id' => 'id_order', 'name' => 'Order ID'],
                        ['id' => 'reference', 'name' => 'Order Reference'],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_LABEL_SIZE_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default label size'),
                'name' => Constant::MY_PARCEL_LABEL_SIZE_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        ['id' => 'a4', 'name' => 'A4'],
                        ['id' => 'a6', 'name' => 'A6'],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_LABEL_POSITION_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default label position'),
                'name' => Constant::MY_PARCEL_LABEL_POSITION_CONFIGURATION_NAME,
                'form_group_class' => 'label_position',
                'options' => [
                    'query' => [
                        ['id' => '1', 'name' => $this->module->l('Top left')],
                        ['id' => '3', 'name' => $this->module->l('Top right')],
                        ['id' => '2', 'name' => $this->module->l('Bottom left')],
                        ['id' => '4', 'name' => $this->module->l('Bottom right')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Open or download label'),
                'name' => Constant::MY_PARCEL_LABEL_OPEN_DOWNLOAD_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        ['id' => 'true', 'name' => $this->module->l('Open')],
                        ['id' => 'false', 'name' => $this->module->l('Download')],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::MY_PARCEL_LABEL_PROMPT_POSITION_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Prompt for label position'),
                'name' => Constant::MY_PARCEL_LABEL_PROMPT_POSITION_CONFIGURATION_NAME,
                'required' => false,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('No'),
                    ],
                ],
            ],
        ];
    }
}
