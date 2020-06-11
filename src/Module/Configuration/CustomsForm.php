<?php

namespace Gett\MyparcelBE\Module\Configuration;

use Context;
use Country;
use Gett\MyparcelBE\Constant;

class CustomsForm extends AbstractForm
{
    protected $icon = 'cog';

    protected function getLegend(): string
    {
        return $this->module->l('Customs Settings', 'customsform');
    }

    protected function getFields(): array
    {
        return [
            Constant::CUSTOMS_FORM_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default customs form', 'customsform'),
                'name' => Constant::CUSTOMS_FORM_CONFIGURATION_NAME,
                'options' => [
                    'query' => [
                        [
                            'id' => 'No',
                            'name' => $this->module->l(
                                'Do not automatically generate customs form',
                                'customsform'
                            )
                        ],
                        [
                            'id' => 'Add',
                            'name' => $this->module->l('Add this product to customs form', 'customsform')
                        ],
                        [
                            'id' => 'Skip',
                            'name' => $this->module->l('Skip this product on customs form', 'customsform')
                        ],
                    ],
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            Constant::DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME => [
                'type' => 'text',
                'label' => $this->module->l('Default customs code', 'customsform'),
                'name' => Constant::DEFAULT_CUSTOMS_CODE_CONFIGURATION_NAME,
            ],
            Constant::DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME => [
                'type' => 'select',
                'label' => $this->module->l('Default customs origin', 'customsform'),
                'name' => Constant::DEFAULT_CUSTOMS_ORIGIN_CONFIGURATION_NAME,
                'options' => [
                    'query' => Country::getCountries(Context::getContext()->language->id),
                    'id' => 'iso_code',
                    'name' => 'name',
                ],
            ],
            Constant::CUSTOMS_AGE_CHECK_CONFIGURATION_NAME => [
                'type' => 'switch',
                'label' => $this->module->l('Default customs age check', 'customsform'),
                'name' => Constant::CUSTOMS_AGE_CHECK_CONFIGURATION_NAME,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->module->l('Enabled', 'customsform'),
                    ],
                    [
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->module->l('Disabled', 'customsform'),
                    ],
                ],
            ],
        ];
    }
}
