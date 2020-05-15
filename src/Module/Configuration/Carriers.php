<?php

namespace Gett\MyParcel\Module\Configuration;

use Gett\MyParcel\Constant;

class Carriers
{
    private $context;

    public function __construct($module)
    {
        $this->module = $module;
        $this->name = str_replace(' ', '', $module->displayName) . self::class;
        $this->context = \Context::getContext();
    }

    public function __invoke(): string
    {
        if (\Tools::isSubmit('submitMyparcelCarrierSettings')) {
            foreach (Constant::MY_PARCEL_CARRIER_CONFIGURATION_FIELDS as $value) {
                \DB::getInstance()->update('myparcel_carrier_configuration', ['value' => pSQL(\Tools::getValue($value))], 'id_carrier = "' . \Tools::getValue('id_carrier') . '" AND name = "' . pSQL($value) . '" ');
            }
        }

        if (\Tools::isSubmit('updatecarrier')) {
            return $this->getForm();
        }

        return $this->getList();
    }

    private function getForm()
    {
        $fields = [
            'form' => [
                'legend' => [
                    'title' => $this->module->l('Carriers'),
                    'icon' => 'icon-truck',
                ],
                'tabs' => [
                    'form' => $this->module->l('Checkout delivery form'),
                    'delivery' => $this->module->l('Delivery'),
                    'return' => $this->module->l('Return'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery Title'),
                        'name' => 'deliveryTitle',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('General delivery title'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Drop off days'),
                        'name' => 'dropOffDays',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('This option allows the Merchant to set the days she normally goes to PostNL to hand in her parcels. Monday is 1 and Saturday is 6.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Cutoff Time'),
                        'name' => 'cutoffTime',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('This option allows the Merchant to indicate the latest cut-off time before an order will still be picked, packed and dispatched on the same/first set dropoff day, taking into account the dropoff-delay. Industry standard default time is 17:00. For example, if cutoff time is 17:00, Monday is a delivery day and there\'s no delivery delay; all orders placed Monday before 17:00 will be dropped of at PostNL on that same Monday in time for the Monday collection and delivery on Tuesday.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery days window'),
                        'name' => 'deliverydaysWindow',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('This option allows the Merchant to set the number of days into the future for which she wants to show her consumers delivery options. For example; If set to 3 (days) in her checkout, a consumer ordering on Monday will see possible delivery options for Tuesday, Wednesday and Thursday (provided there is no drop-off delay, it\'s before the cut-off time and she goes to PostNL on Mondays). Min. is 1 and max. is 14.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Drop off delay'),
                        'name' => 'dropoffDelay',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('This option allows the Merchant to set the number of days it takes her to pick, pack and hand in her parcel at PostNL when ordered before the cutoff time. By default this is 0 and max. is 14.'),
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow monday delivery'),
                        'name' => 'allowMondayDelivery',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                        ],
                        'values' => [
                            ['id' => 'allowMondayDelivery_on', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'allowMondayDelivery_off', 'value' => 0, 'label' => 'No'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Saturday cutoff time'),
                        'name' => 'saturdayCutoffTime',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Allow monday delivery'),
                        'name' => 'allowMondayDelivery',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Allow morning delivery'),
                        'name' => 'allowMorningDelivery',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('Monday delivery is only possible when the package is delivered before 15.00 on Saturday at the designated PostNL locations. Note: To activate Monday delivery value 6 must be given with dropOffDays and value 1 must be given by monday_delivery. On Saturday the cutoffTime must be before 15:00 (14:30 recommended) so that Monday will be shown.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery morning title'),
                        'name' => 'deliveryMorningTitle',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('When there is no title, the delivery time will automatically be visible.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery morning price'),
                        'name' => 'priceMorningDelivery',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery standart title'),
                        'name' => 'deliveryStandardTitle',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('When there is no title, the delivery time will automatically be visible.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery standart price'),
                        'name' => 'priceStandardDelivery',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow evening delivery'),
                        'name' => 'allowEveningDelivery',
                        'tab' => 'form',
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Delivery evening title'),
                        'name' => 'deliveryEveningTitle',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('When there is no title, the delivery time will automatically be visible.'),
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Price evening delivery'),
                        'name' => 'priceEveningDelivery',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->module->l('Allow signature'),
                        'name' => 'allowSignature',
                        'tab' => 'form',
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Signature title'),
                        'name' => 'signatureTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Price signature'),
                        'name' => 'priceSignature',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Allow only recipient'),
                        'name' => 'allowOnlyRecipient',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Only recipient title'),
                        'name' => 'onlyRecipientTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Price Only recipient'),
                        'name' => 'priceOnlyRecipient',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Allow pickup points'),
                        'name' => 'allowPickupPoints',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Pickup title'),
                        'name' => 'pickupTitle',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Pickup price'),
                        'name' => 'pricePickup',
                        'tab' => 'form',
                        'hint' => [
                            $this->module->l('It\'s possible to fill in a positive or negative amount. Would you like to give a discount for the use of this feature or would you like to calculate extra costs? If the amount is negative the price will appear green in the checkout.'),
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Allow pickup express'),
                        'name' => 'allowPickupExpress',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('Price pickup express'),
                        'name' => 'pricePickupExpress',
                        'tab' => 'form',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->module->l('BE delivery title'),
                        'name' => 'BEdeliveryTitle',
                        'tab' => 'form',
                    ],
                    [
                        'tab' => 'delivery',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => 'Package'],
                                ['id' => 2, 'name' => 'Mailbox package'],
                                ['id' => 3, 'name' => 'Letter'],
                                ['id' => 4, 'name' => 'Digital stamp'],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Deliver only to recipient'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Age check'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'tab' => 'delivery',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => 'Normal'],
                                ['id' => 2, 'name' => 'Large'],
                                ['id' => 3, 'name' => 'Automatic'],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Return package when recipient is not home'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Recipient need to sign'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Package with insurance'),
                        'name' => \Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME,
                        'tab' => 'delivery',
                    ],

                    [
                        'tab' => 'return',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_TYPE_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => 'Package'],
                                ['id' => 2, 'name' => 'Mailbox package'],
                                ['id' => 3, 'name' => 'Letter'],
                                ['id' => 4, 'name' => 'Digital stamp'],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Deliver only to recipient'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_ONLY_RECIPIENT_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Age check'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_AGE_CHECK_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'tab' => 'return',
                        'type' => 'select',
                        'label' => $this->module->l('Default package type'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_PACKAGE_FORMAT_CONFIGURATION_NAME,
                        'options' => [
                            'query' => [
                                ['id' => 1, 'name' => 'Normal'],
                                ['id' => 2, 'name' => 'Large'],
                                ['id' => 3, 'name' => 'Automatic'],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                            'default' => [
                                'label' => $this->module->l('Select'),
                                'value' => 0,
                            ],
                        ],
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Return package when recipient is not home'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_RETURN_PACKAGE_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Recipient need to sign'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'switch',
                        'is_bool' => true,
                        'values' => [
                            ['id' => 'Yes', 'value' => 1, 'label' => 'Yes'],
                            ['id' => 'no', 'value' => 0, 'label' => 'No'],
                        ],
                        'label' => $this->module->l('Package with insurance'),
                        'name' => 'return_' . \Gett\MyParcel\Constant::MY_PARCEL_INSURANCE_CONFIGURATION_NAME,
                        'tab' => 'return',
                    ],
                    [
                        'type' => 'hidden',
                        'name' => 'id_carrier',
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save'),
                ],
            ],
        ];

        $helper = new \HelperForm();

        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = \Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->submit_action = 'submitMyparcelCarrierSettings';
        $helper->currentIndex = \AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . \Tools::getValue(
            'menu',
            0
        );
        $helper->token = \Tools::getAdminTokenLite('AdminModules');

        $result = \Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'myparcel_carrier_configuration WHERE id_carrier = "' . \Tools::getValue('id_carrier') . '"  ');
        $vars = [];
        foreach ($result as $item) {
            $vars[$item['name']] = $item['value'];
        }
        $vars['id_carrier'] = \Tools::getValue('id_carrier');
        $helper->tpl_vars = [
            'fields_value' => $vars,
        ];

        return $helper->generateForm([$fields]);
    }

    private function getList()
    {
        $fieldsList = [
            'id_carrier' => [
                'title' => $this->module->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->module->l('Name'),
            ],
        ];

        $helper = new \HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = ['edit'];
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->identifier = 'id_carrier';
        $helper->title = "{$this->module->l('Delivery options')}";
        $helper->table = 'carrier';
        $helper->token = \Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = \AdminController::$currentIndex . '&configure=' . $this->module->name . '&menu=' . \Tools::getValue(
            'menu',
            0
        );
        $helper->colorOnBackground = true;
        $helper->no_link = true;

        $list = \Db::getInstance()->executeS('SELECT a.* FROM `' . _DB_PREFIX_ . "carrier` a WHERE a.external_module_name = '" . $this->module->name . "' AND a.`deleted` = 0 ORDER BY a.`position` ASC LIMIT 0, 50");

        return $helper->generateList($list, $fieldsList);
    }
}
