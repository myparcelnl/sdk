<?php

namespace Gett\MyparcelBE\Module\Configuration;

use Carrier;
use Configuration;
use Currency;
use Db;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Module\Tools\Tools;

class Carriers extends AbstractForm
{
    private $context;

    public function __construct(\Module $module)
    {
        parent::__construct($module);
        $this->context = \Context::getContext();
    }

    protected function getLegend(): string
    {
        return '';
    }

    protected function getFields(): array
    {
        return [];
    }

    public function __invoke(): string
    {
        if (Tools::isSubmit('submitMyparcelCarrierSettings')
            || Tools::isSubmit('submitMyparcelCarrierSettingsAndStay')) {
            $dropOff = [];
            $postFields = Tools::getAllValues();
            $carrierId = $postFields['id_carrier'];
            foreach ($postFields as $key => $value) {
                if (stripos($key, 'dropOffDays') !== false) {
                    $temp = explode('_', $key);
                    $dropOff[] = end($temp);
                }
            }
            $postFields['dropOffDays'] = '';
            if (!empty($dropOff)) {
                $postFields['dropOffDays'] = implode(',', $dropOff);
            }
            foreach (Constant::CARRIER_CONFIGURATION_FIELDS as $field) {
                $updatedValue = $postFields[$field] ?? '';
                if (stripos($field, 'price') === 0) {
                    $price = $updatedValue = Tools::normalizeFloat($updatedValue);
                    if (!empty($price) && !\Validate::isFloat($price)) {
                        switch ($field) {
                            case 'priceMondayDelivery':
                                $label = $this->module->l('Delivery Monday price', 'carriers');
                                break;
                            case 'priceMorningDelivery':
                                $label = $this->module->l('Delivery morning price', 'carriers');
                                break;
//                            case 'priceStandardDelivery':
//                                $label = $this->module->l('Delivery standard price', 'carriers');
//                                break;
                            case 'priceEveningDelivery':
                                $label = $this->module->l('Delivery evening price', 'carriers');
                                break;
                            case 'priceSaturdayDelivery':
                                $label = $this->module->l('Delivery Saturday price', 'carriers');
                                break;
                            case 'priceSignature':
                                $label = $this->module->l('Signature price', 'carriers');
                                break;
                            case 'priceOnlyRecipient':
                                $label = $this->module->l('Only recipient price', 'carriers');
                                break;
                            case 'pricePickup':
                                $label = $this->module->l('Pickup price', 'carriers');
                                break;
                            default:
                                $label = $this->module->l('Price field', 'carriers');
                                break;
                        }
                        $this->context->controller->errors[] = sprintf(
                            $this->module->l('Wrong price format for %s', 'carriers'),
                            $label
                        );
                        continue;
                    }
                }
                Db::getInstance()->update(
                    'myparcelbe_carrier_configuration',
                    ['value' => pSQL($updatedValue)],
                    'id_carrier = ' . (int) $carrierId . ' AND name = "' . pSQL($field) . '" '
                );
            }
        }

        if (Tools::isSubmit('updatecarrier')
            || !empty($this->context->controller->errors)
            || Tools::isSubmit('submitMyparcelCarrierSettingsAndStay')) {
            return $this->getForm();
        }

        return $this->getList();
    }

    private function getForm()
    {
        $carrierName = $this->module->l('Carriers', 'carriers');
        $id_carrier = (int) Tools::getValue('id_carrier');
        $carrier = new Carrier($id_carrier, $this->context->language->id);
        if (!empty($carrier->name)) {
            $carrierName = $carrier->name;
        }

        $carrierType = $this->exclusiveField->getCarrierType($carrier);
        $countryIso = $this->module->getModuleCountry();
        $tabs = [];
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'ALLOW_STANDARD_FORM')) {
            $tabs['form'] = $this->module->l('Checkout delivery form', 'carriers');
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'ALLOW_DELIVERY_FORM')) {
            $tabs['delivery'] = $this->module->l('Delivery', 'carriers');
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'ALLOW_RETURN_FORM')) {
            $tabs['return'] = $this->module->l('Return', 'carriers');
        }
        $fields = [
            'form' => [
                'legend' => [
                    'title' => $carrierName,
                    'icon' => 'icon-truck',
                ],
                'tabs' => $tabs,
                'input' => $this->getFormInputs($carrier),
                'submit' => [
                    'title' => $this->module->l('Save', 'carriers'),
                ],
                'buttons' => [
                    'save-and-stay' => [
                        'title' => $this->module->l('Save and stay', 'carriers'),
                        'name' => 'submitMyparcelCarrierSettingsAndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save',
                    ],
                ],
            ],
        ];

        $helper = new \HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->submit_action = 'submitMyparcelCarrierSettings';
        $helper->currentIndex = \AdminController::$currentIndex
            . '&configure=' . $this->module->name
            . '&id_carrier=' . (int) $carrier->id
            . '&menu=' . Tools::getValue('menu', 0)
            . '&updatecarrier=';
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $carrierConfigs = Db::getInstance()->executeS('SELECT *
            FROM `' . _DB_PREFIX_ . 'myparcelbe_carrier_configuration`
            WHERE `id_carrier` = ' . $id_carrier);
        $vars = [];
        foreach ($carrierConfigs as $row) {
            if ($row['name'] == 'dropOffDays') {
                $temp = explode(',', $row['value']);
                foreach ($temp as $value) {
                    $vars['dropOffDays_' . $value] = 1;
                }
                continue;
            }
            if ($row['name'] == Constant::CUTOFF_EXCEPTIONS) {
                if (empty($row['value'])) {
                    $row['value'] = '{}';
                }
            }
            $vars[$row['name']] = $row['value'];
        }

        $vars['id_carrier'] = $id_carrier;
        $this->setExclusiveFieldsValues($carrier, $vars);
        $helper->tpl_vars = [
            'fields_value' => $vars,
        ];

        return $helper->generateForm([$fields]);
    }

    private function getList()
    {
        $fieldsList = [
            'id_carrier' => [
                'title' => $this->module->l('ID', 'carriers'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->module->l('Name', 'carriers'),
            ],
        ];

        $helper = new \HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = ['edit'];
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->identifier = 'id_carrier';
        $helper->title = $this->module->l('Delivery options', 'carriers');
        $helper->table = 'carrier';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = \AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . Tools::getValue(
            'menu',
            0
        );
        $helper->colorOnBackground = true;
        $helper->no_link = true;

        $list = Db::getInstance()->executeS('SELECT a.*
            FROM `' . _DB_PREFIX_ . "carrier` a
            WHERE a.external_module_name = '" . $this->module->name . "'
                AND a.`deleted` = 0
            ORDER BY a.`position` ASC
            LIMIT 0, 50");

        return $helper->generateList($list, $fieldsList);
    }

    private function getFormInputs(Carrier $carrier)
    {
        $currency = Currency::getDefaultCurrency();

        $packageTypeOptions = [
            1 => $this->module->l('Parcel', 'carriers'),
            2 => $this->module->l('Mailbox package', 'carriers'),
            3 => $this->module->l('Letter', 'carriers'),
            4 => $this->module->l('Digital stamp', 'carriers'),
        ];
        $packageFormatOptions = [
            1 => $this->module->l('Normal', 'carriers'),
            2 => $this->module->l('Large', 'carriers'),
            3 => $this->module->l('Automatic', 'carriers'),
        ];
        $fields = [];
        $formTabFields = $this->getFormTabFields($carrier, $currency);
        $deliveryTabFields = $this->getExtraTabFields($carrier, $packageTypeOptions, $packageFormatOptions);
        $returnTabFields = $this->getExtraTabFields($carrier, $packageTypeOptions, $packageFormatOptions, 'return');

        return array_merge($fields, $formTabFields, $deliveryTabFields, $returnTabFields);
    }

    private function getFormTabFields(Carrier $carrier, Currency $currency)
    {
        $fields = [];
        $carrierType = $this->exclusiveField->getCarrierType($carrier);
        $countryIso = $this->module->getModuleCountry();
        if (!$this->exclusiveField->isAvailable($countryIso, $carrierType, 'ALLOW_STANDARD_FORM')) {
            return $fields;
        }

        $deliveryDaysOptions = [
            [
                'id' => -1,
                'name' => $this->module->l('Hide days', 'carriers'),
            ],
        ];
        for ($i = 1; $i < 15; ++$i) {
            $deliveryDaysOptions[] = [
                'id' => $i,
                'name' => sprintf($this->module->l('%d days', 'carriers'), $i),
            ];
        }

        $dropOffDelayOptions = [
            [
                'id' => 0,
                'name' => $this->module->l('No delay', 'carriers'),
            ],
            [
                'id' => 1,
                'name' => $this->module->l('1 day', 'carriers'),
            ],
        ];
        for ($i = 2; $i < 15; ++$i) {
            $dropOffDelayOptions[] = [
                'id' => $i,
                'name' => sprintf($this->module->l('%d days', 'carriers'), $i),
            ];
        }
        $cutoffTimeValues = [];
        foreach (Constant::WEEK_DAYS as $index => $day) {
            $cutoffTimeValues[$index] = [
                'name' => $day . 'CutoffTime',
                'class' => 'cutoff-time-pseudo',
                'prefix' => $this->module->l('Cutoff Time', 'carriers'),
            ];
        }
        $fields[] = [
            'tab' => 'form',
            'type' => 'text',
            'label' => $this->module->l('Delivery Title', 'carriers'),
            'name' => 'deliveryTitle',
            'desc' => $this->module->l('General delivery title', 'carriers'),
        ];
        $fields[] = [
            'tab' => 'form',
            'type' => 'checkbox',
            'multiple' => true,
            'label' => $this->module->l('Drop off days', 'carriers'),
            'name' => 'dropOffDays',
            'form_group_class' => 'with-cutoff-time',
            'values' => [
                'query' => [
                    ['day_number' => 1, 'name' => $this->module->l('Monday', 'carriers')],
                    ['day_number' => 2, 'name' => $this->module->l('Tuesday', 'carriers')],
                    ['day_number' => 3, 'name' => $this->module->l('Wednesday', 'carriers')],
                    ['day_number' => 4, 'name' => $this->module->l('Thursday', 'carriers')],
                    ['day_number' => 5, 'name' => $this->module->l('Friday', 'carriers')],
                    ['day_number' => 6, 'name' => $this->module->l('Saturday', 'carriers')],
                    ['day_number' => 7, 'name' => $this->module->l('Sunday', 'carriers')],
                ],
                'id' => 'day_number',
                'name' => 'name',
            ],
            'cutoff_time' => $cutoffTimeValues,
            'desc' => [
                sprintf($this->module->l(
                'This option allows the Merchant to set the days he normally goes to %s to hand in the 
                        parcels. Monday is 1 and Saturday is 6.',
                    'carriers'
                ), $carrier->name),
                sprintf($this->module->l(
                    'The Cutoff Time option allows the Merchant to indicate the latest cut-off time before an order will 
                        still be picked, packed and dispatched on the same/first set dropoff day, taking into account 
                        the dropoff-delay. Industry standard default time is 17:00. For example, if cutoff time is 
                        17:00, Monday is a delivery day and there\'s no delivery delay; all orders placed Monday 
                        before 17:00 will be dropped of at %s on that same Monday in time for the Monday collection 
                        and delivery on Tuesday.',
                    'carriers'
                ), $carrier->name),
            ],
        ];
        foreach (Constant::WEEK_DAYS as $index => $day) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'hidden',
                'name' => $day . 'CutoffTime',
            ];
        }
        $fields[] = [
            'tab' => 'form',
            'type' => 'cutoffexceptions',
            'label' => $this->module->l('Exception schedule', 'carriers'),
            'name' => Constant::CUTOFF_EXCEPTIONS,
        ];
        $fields[] = [
            'tab' => 'form',
            'type' => 'select',
            'label' => $this->module->l('Delivery days window', 'carriers'),
            'name' => 'deliveryDaysWindow',
            'options' => [
                'query' => $deliveryDaysOptions,
                'id' => 'id',
                'name' => 'name',
            ],
            'desc' => sprintf($this->module->l(
                'This option allows the Merchant to set the number of days into the future for which he wants to 
                show his consumers delivery options. For example; If set to 3 (days) in his checkout, a consumer 
                ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided 
                there is no drop-off delay, it\'s before the cut-off time and he goes to %s on Mondays). Min. is 
                1 and max. is 14.',
                'carriers'
            ), $carrier->name),
        ];
        $fields[] = [
            'type' => 'select',
            'label' => $this->module->l('Drop off delay', 'carriers'),
            'name' => 'dropOffDelay',
            'tab' => 'form',
            'options' => [
                'query' => $dropOffDelayOptions,
                'id' => 'id',
                'name' => 'name',
            ],
            'desc' => sprintf($this->module->l(
                'This option allows the Merchant to set the number of days it takes him to pick, pack and hand in 
                his parcel at %s when ordered before the cutoff time. By default this is 0 and max. is 14.',
                'carriers'
            ), $carrier->name),
        ];
        $fields[] = [
            'type' => 'text',
            'label' => $this->module->l('Delivery standard title', 'carriers'),
            'name' => 'deliveryStandardTitle',
            'tab' => 'form',
            'desc' => $this->module->l(
                'When there is no title, the delivery time will automatically be visible.',
                'carriers'
            ),
        ];
//        $fields[] = [
//            'type' => 'text',
//            'label' => $this->module->l('Delivery standard price', 'carriers'),
//            'name' => 'priceStandardDelivery',
//            'suffix' => $currency->getSign(),
//            'class' => 'col-lg-2',
//            'tab' => 'form',
//        ];
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowMondayDelivery')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow Monday delivery', 'carriers'),
                'name' => 'allowMondayDelivery',
                'desc' => sprintf($this->module->l(
                    'Monday delivery is only possible when the package is delivered before 15.00 on Saturday at 
                    the designated %s locations. Note: To activate Monday delivery value 6 must be given with 
                    dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 
                    15:00 (14:30 recommended) so that Monday will be shown.',
                    'carriers'
                ), $carrier->name),
                'values' => [
                    [
                        'id' => 'allowMondayDelivery_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowMondayDelivery_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery Monday price', 'carriers'),
                'name' => 'priceMondayDelivery',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowMondayDelivery',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowMorningDelivery')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'allowMorningDelivery_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowMorningDelivery_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Allow morning delivery', 'carriers'),
                'name' => 'allowMorningDelivery',
                'desc' => sprintf($this->module->l(
                    'Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the 
                    designated %s locations. Note: To activate Monday delivery value 6 must be given with 
                    dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 
                    15:00 (14:30 recommended) so that Monday will be shown.',
                    'carriers'
                ), $carrier->name),
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery morning title', 'carriers'),
                'name' => 'deliveryMorningTitle',
                'desc' => $this->module->l(
                    'When there is no title, the delivery time will automatically be visible.',
                    'carriers'
                ),
                'form_group_class' => 'toggle-child-field allowMorningDelivery',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery morning price', 'carriers'),
                'name' => 'priceMorningDelivery',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowMorningDelivery',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowEveningDelivery')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow evening delivery', 'carriers'),
                'name' => 'allowEveningDelivery',
                'values' => [
                    [
                        'id' => 'allowEveningDelivery_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowEveningDelivery_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery evening title', 'carriers'),
                'name' => 'deliveryEveningTitle',
                'desc' => $this->module->l(
                    'When there is no title, the delivery time will automatically be visible.',
                    'carriers'
                ),
                'form_group_class' => 'toggle-child-field allowEveningDelivery',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery evening price', 'carriers'),
                'name' => 'priceEveningDelivery',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowEveningDelivery',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowSaturdayDelivery')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'allowSaturdayDelivery_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowSaturdayDelivery_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Allow Saturday delivery', 'carriers'),
                'name' => 'allowSaturdayDelivery',
                'desc' => sprintf($this->module->l(
                    'Saturday delivery is only possible when the package is delivered before 15:00 on Friday 
                    at the designated %s locations. Note: To allow Saturday delivery, Friday must be enabled in 
                    Drop-off days.',
                    'carriers'
                ), $carrier->name),
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery Saturday title', 'carriers'),
                'name' => 'saturdayDeliveryTitle',
                'desc' => $this->module->l(
                    'When there is no title, the delivery time will automatically be visible.',
                    'carriers'
                ),
                'form_group_class' => 'toggle-child-field allowSaturdayDelivery',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Delivery Saturday price', 'carriers'),
                'name' => 'priceSaturdayDelivery',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowSaturdayDelivery',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowSignature')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow signature', 'carriers'),
                'name' => 'allowSignature',
                'values' => [
                    [
                        'id' => 'allowSignature_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowSignature_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Signature title', 'carriers'),
                'name' => 'signatureTitle',
                'form_group_class' => 'toggle-child-field allowSignature',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Signature price', 'carriers'),
                'name' => 'priceSignature',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowSignature',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowOnlyRecipient')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow only recipient', 'carriers'),
                'name' => 'allowOnlyRecipient',
                'values' => [
                    [
                        'id' => 'allowOnlyRecipient_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowOnlyRecipient_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Only recipient title', 'carriers'),
                'name' => 'onlyRecipientTitle',
                'form_group_class' => 'toggle-child-field allowOnlyRecipient',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Only recipient price', 'carriers'),
                'name' => 'priceOnlyRecipient',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'form_group_class' => 'toggle-child-field allowOnlyRecipient',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowPickupPoints')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow pickup points', 'carriers'),
                'name' => 'allowPickupPoints',
                'values' => [
                    [
                        'id' => 'allowPickupPoints_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowPickupPoints_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable title automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Pickup title', 'carriers'),
                'name' => 'pickupTitle',
                'form_group_class' => 'toggle-child-field allowPickupPoints',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Pickup price', 'carriers'),
                'name' => 'pricePickup',
                'suffix' => $currency->getSign(),
                'class' => 'col-lg-2',
                'desc' => $this->module->l(
                    'It\'s possible to fill in a positive or negative amount. Would you like to give a discount 
                    for the use of this feature or would you like to calculate extra costs? If the amount is negative 
                    the price will appear green in the checkout.',
                    'carriers'
                ),
                'form_group_class' => 'toggle-child-field allowPickupPoints',
            ];
        }
        if ($this->exclusiveField->isAvailable($countryIso, $carrierType, 'allowPickupExpress')) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'switch',
                'is_bool' => true,
                'label' => $this->module->l('Allow pickup express', 'carriers'),
                'name' => 'allowPickupExpress',
                'values' => [
                    [
                        'id' => 'allowPickupExpress_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => 'allowPickupExpress_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'form_group_class' => 'toggle-parent-field',
            ];
            // Disable price automatically when the option is not available
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('Price pickup express', 'carriers'),
                'name' => 'pricePickupExpress',
                'form_group_class' => 'toggle-child-field allowPickupExpress',
            ];
        }
        if ($this->module->isBE()) {
            $fields[] = [
                'tab' => 'form',
                'type' => 'text',
                'label' => $this->module->l('BE delivery title', 'carriers'),
                'name' => 'BEdeliveryTitle',
            ];
        }

        return $fields;
    }

    private function getExtraTabFields(
        Carrier $carrier,
        array $packageTypeOptions,
        array $packageFormatOptions,
        string $prefix = ''
    ) {
        $fields = [];
        $carrierType = $this->exclusiveField->getCarrierType($carrier);
        $countryIso = $this->module->getModuleCountry();
        $tabName = 'ALLOW_DELIVERY_FORM';
        $tabId = 'delivery';
        if ($prefix == 'return') {
            $tabName = 'ALLOW_RETURN_FORM';
            $tabId = $prefix;
            $prefix .= '_';
        }
        if (!$this->exclusiveField->isAvailable($countryIso, $carrierType, $tabName)) {
            return $fields;
        }

        $packageTypes = [];
        foreach ($packageTypeOptions as $index => $label) {
            if ($this->exclusiveField->isAvailable(
                $countryIso,
                $carrierType,
                $prefix . Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                $index
            )) {
                $packageTypes[] = ['id' => $index, 'name' => $label];
            }
        }

        $packageFormat = [];
        foreach ($packageFormatOptions as $index => $label) {
            if ($this->exclusiveField->isAvailable(
                $countryIso,
                $carrierType,
                $prefix . Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
                $index
            )) {
                $packageFormat[] = ['id' => $index, 'name' => $label];
            }
        }

        $fields[] = [
            'tab' => $tabId,
            'type' => 'select',
            'label' => $this->module->l('Default package type', 'carriers'),
            'name' => $prefix . Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
            'options' => [
                'query' => $packageTypes,
                'id' => 'id',
                'name' => 'name',
            ],
        ];
        $fields[] = [
            'tab' => $tabId,
            'type' => 'select',
            'label' => $this->module->l('Default package format', 'carriers'),
            'name' => $prefix . Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
            'options' => [
                'query' => $packageFormat,
                'id' => 'id',
                'name' => 'name',
            ],
        ];
        if ($this->exclusiveField->isAvailable(
            $countryIso,
            $carrierType,
            $prefix . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME
        )) {
            $fields[] = [
                'tab' => $tabId,
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $prefix . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME . '_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => $prefix . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME . '_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Deliver only to recipient', 'carriers'),
                'name' => $prefix . Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
            ];
        }
        if ($this->exclusiveField->isAvailable(
            $countryIso,
            $carrierType,
            $prefix . Constant::AGE_CHECK_CONFIGURATION_NAME
        )) {
            $fields[] = [
                'tab' => $tabId,
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $prefix . Constant::AGE_CHECK_CONFIGURATION_NAME . '_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => $prefix . Constant::AGE_CHECK_CONFIGURATION_NAME . '_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Age check', 'carriers'),
                'name' => $prefix . Constant::AGE_CHECK_CONFIGURATION_NAME,
            ];
        }
        if ($this->exclusiveField->isAvailable(
            $countryIso,
            $carrierType,
            $prefix . Constant::RETURN_PACKAGE_CONFIGURATION_NAME
        )) {
            $fields[] = [
                'tab' => $tabId,
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $prefix . Constant::RETURN_PACKAGE_CONFIGURATION_NAME . '_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => $prefix . Constant::RETURN_PACKAGE_CONFIGURATION_NAME . '_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Return package when recipient is not home', 'carriers'),
                'name' => $prefix . Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
            ];
        }
        if ($this->exclusiveField->isAvailable(
            $countryIso,
            $carrierType,
            $prefix . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME
        )) {
            $fields[] = [
                'tab' => $tabId,
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $prefix . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME . '_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => $prefix . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME . '_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Recipient need to sign', 'carriers'),
                'name' => $prefix . Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
            ];
        }
        if ($this->exclusiveField->isAvailable(
            $countryIso,
            $carrierType,
            $prefix . Constant::INSURANCE_CONFIGURATION_NAME
        )) {
            $fields[] = [
                'tab' => $tabId,
                'type' => 'switch',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $prefix . Constant::INSURANCE_CONFIGURATION_NAME . '_on',
                        'value' => 1,
                        'label' => $this->module->l('Yes', 'carriers'),
                    ],
                    [
                        'id' => $prefix . Constant::INSURANCE_CONFIGURATION_NAME . '_off',
                        'value' => 0,
                        'label' => $this->module->l('No', 'carriers'),
                    ],
                ],
                'label' => $this->module->l('Package with insurance', 'carriers'),
                'name' => $prefix . Constant::INSURANCE_CONFIGURATION_NAME,
            ];
        }

        return $fields;
    }
}
