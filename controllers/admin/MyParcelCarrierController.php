<?php

class MyParcelCarrierController extends AdminController
{
    protected $position_identifier = 'id_carrier';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'carrier';
        $this->className = 'Carrier';
        $this->lang = false;
        $this->deleted = true;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->_defaultOrderBy = 'position';

        parent::__construct();

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
                'icon' => 'icon-trash',
            ],
        ];

        $this->fieldImageSettings = [
            'name' => 'logo',
            'dir' => 's',
        ];

        $this->fields_list = [
            'id_carrier' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'name' => [
                'title' => $this->trans('Name', [], 'Admin.Global'),
            ],
            'image' => [
                'title' => $this->trans('Logo', [], 'Admin.Global'),
                'align' => 'center',
                'image' => 's',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
            ],
            'delay' => [
                'title' => $this->trans('Delay', [], 'Admin.Shipping.Feature'),
                'orderby' => false,
            ],
            'active' => [
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ],
            'is_free' => [
                'title' => $this->trans('Free Shipping', [], 'Admin.Shipping.Feature'),
                'align' => 'center',
                'active' => 'isFree',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ],
            'position' => [
                'title' => $this->trans('Position', [], 'Admin.Global'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
            ],
        ];
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Carriers', [], 'Admin.Shipping.Feature'),
                'icon' => 'icon-truck',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Company', [], 'Admin.Global'),
                    'name' => 'name',
                    'required' => true,
                    'hint' => [
                        $this->trans('Allowed characters: letters, spaces and "%special_chars%".', ['%special_chars%' => '().-'], 'Admin.Shipping.Help'),
                        $this->trans('Carrier name displayed during checkout', [], 'Admin.Shipping.Help'),
                        $this->trans('For in-store pickup, enter 0 to replace the carrier name with your shop name.', [], 'Admin.Shipping.Help'),
                    ],
                ],
                [
                    'type' => 'file',
                    'label' => $this->trans('Logo', [], 'Admin.Global'),
                    'name' => 'logo',
                    'hint' => $this->trans('Upload a logo from your computer.', [], 'Admin.Shipping.Help') . ' (.gif, .jpg, .jpeg ' . $this->trans('or', [], 'Admin.Shipping.Help') . ' .png)',
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Transit time', [], 'Admin.Shipping.Feature'),
                    'name' => 'delay',
                    'lang' => true,
                    'required' => true,
                    'maxlength' => 512,
                    'hint' => $this->trans('Estimated delivery time will be displayed during checkout.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Speed grade', [], 'Admin.Shipping.Feature'),
                    'name' => 'grade',
                    'required' => false,
                    'hint' => $this->trans('Enter "0" for a longest shipping delay, or "9" for the shortest shipping delay.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('URL', [], 'Admin.Global'),
                    'name' => 'url',
                    'hint' => $this->trans('Delivery tracking URL: Type \'@\' where the tracking number should appear. It will then be automatically replaced by the tracking number.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'checkbox',
                    'label' => $this->trans('Zone', [], 'Admin.Global'),
                    'name' => 'zone',
                    'values' => [
                        'query' => Zone::getZones(false),
                        'id' => 'id_zone',
                        'name' => 'name',
                    ],
                    'hint' => $this->trans('The zones in which this carrier will be used.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'group',
                    'label' => $this->trans('Group access', [], 'Admin.Shipping.Help'),
                    'name' => 'groupBox',
                    'values' => Group::getGroups(Context::getContext()->language->id),
                    'hint' => $this->trans('Mark the groups that are allowed access to this carrier.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Status', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                    'hint' => $this->trans('Enable the carrier in the front office.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Apply shipping cost', [], 'Admin.Shipping.Feature'),
                    'name' => 'is_free',
                    'required' => false,
                    'class' => 't',
                    'values' => [
                        [
                            'id' => 'is_free_on',
                            'value' => 0,
                            'label' => '<img src="../img/admin/enabled.gif" alt="' . $this->trans('Yes', [], 'Admin.Global') . '" title="' . $this->trans('Yes', [], 'Admin.Global') . '" />',
                        ],
                        [
                            'id' => 'is_free_off',
                            'value' => 1,
                            'label' => '<img src="../img/admin/disabled.gif" alt="' . $this->trans('No', [], 'Admin.Global') . '" title="' . $this->trans('No', [], 'Admin.Global') . '" />',
                        ],
                    ],
                    'hint' => $this->trans('Apply both regular shipping cost and product-specific shipping costs.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'select',
                    'label' => $this->trans('Tax', [], 'Admin.Global'),
                    'name' => 'id_tax_rules_group',
                    'options' => [
                        'query' => TaxRulesGroup::getTaxRulesGroups(true),
                        'id' => 'id_tax_rules_group',
                        'name' => 'name',
                        'default' => [
                            'label' => $this->trans('No Tax', [], 'Admin.Global'),
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Shipping and handling', [], 'Admin.Shipping.Feature'),
                    'name' => 'shipping_handling',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'shipping_handling_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'shipping_handling_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                    'hint' => $this->trans('Include the shipping and handling costs in the carrier price.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'radio',
                    'label' => $this->trans('Billing', [], 'Admin.Shipping.Feature'),
                    'name' => 'shipping_method',
                    'required' => false,
                    'class' => 't',
                    'br' => true,
                    'values' => [
                        [
                            'id' => 'billing_default',
                            'value' => Carrier::SHIPPING_METHOD_DEFAULT,
                            'label' => $this->trans('Default behavior', [], 'Admin.Shipping.Feature'),
                        ],
                        [
                            'id' => 'billing_price',
                            'value' => Carrier::SHIPPING_METHOD_PRICE,
                            'label' => $this->trans('According to total price', [], 'Admin.Shipping.Feature'),
                        ],
                        [
                            'id' => 'billing_weight',
                            'value' => Carrier::SHIPPING_METHOD_WEIGHT,
                            'label' => $this->trans('According to total weight', [], 'Admin.Shipping.Feature'),
                        ],
                    ],
                ],
                [
                    'type' => 'select',
                    'label' => $this->trans('Out-of-range behavior', [], 'Admin.Shipping.Feature'),
                    'name' => 'range_behavior',
                    'options' => [
                        'query' => [
                            [
                                'id' => 0,
                                'name' => $this->trans('Apply the cost of the highest defined range', [], 'Admin.Shipping.Help'),
                            ],
                            [
                                'id' => 1,
                                'name' => $this->trans('Disable carrier', [], 'Admin.Shipping.Feature'),
                            ],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                    ],
                    'hint' => $this->trans('Out-of-range behavior occurs when none is defined (e.g. when a customer\'s cart weight is greater than the highest range limit).', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Maximum package height', [], 'Admin.Shipping.Feature'),
                    'name' => 'max_height',
                    'required' => false,
                    'hint' => $this->trans('Maximum height managed by this carrier. Set the value to "0," or leave this field blank to ignore.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Maximum package width', [], 'Admin.Shipping.Feature'),
                    'name' => 'max_width',
                    'required' => false,
                    'hint' => $this->trans('Maximum width managed by this carrier. Set the value to "0," or leave this field blank to ignore.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Maximum package depth', [], 'Admin.Shipping.Feature'),
                    'name' => 'max_depth',
                    'required' => false,
                    'hint' => $this->trans('Maximum depth managed by this carrier. Set the value to "0," or leave this field blank to ignore.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Maximum package weight', [], 'Admin.Shipping.Feature'),
                    'name' => 'max_weight',
                    'required' => false,
                    'hint' => $this->trans('Maximum weight managed by this carrier. Set the value to "0," or leave this field blank to ignore.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'hidden',
                    'name' => 'is_module',
                ],
                [
                    'type' => 'hidden',
                    'name' => 'external_module_name',
                ],
                [
                    'type' => 'hidden',
                    'name' => 'shipping_external',
                ],
                [
                    'type' => 'hidden',
                    'name' => 'need_range',
                ],
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association', [], 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            ];
        }

        $this->fields_form['submit'] = [
            'title' => $this->trans('Save', [], 'Admin.Actions'),
        ];

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->getFieldsValues($obj);

        return parent::renderForm();
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int) (Tools::getValue('way'));
        $id_carrier = (int) (Tools::getValue('id'));
        $positions = Tools::getValue($this->table);

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $id_carrier) {
                if ($carrier = new Carrier((int) $pos[2])) {
                    if (isset($position) && $carrier->updatePosition($way, $position)) {
                        echo json_encode(['status' => 'ok', 'position' => $position, 'carrier' => $id_carrier]);
                    } else {
                        echo json_encode(['status' => 'error',  'position' => $position, 'carrier' => $id_carrier]);
                    }
                } else {
                    echo json_encode(['status' => 'error',  'message' => 'Carrier can not be loaded', 'carrier' => $id_carrier]);
                }

                break;
            }
        }
    }
}
