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

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Admin.Notifications.Info'),
                'confirm' => $this->trans('Delete selected items?', array(), 'Admin.Notifications.Info'),
                'icon' => 'icon-trash',
            ),
        );

        $this->fieldImageSettings = array(
            'name' => 'logo',
            'dir' => 's',
        );

        $this->fields_list = array(
            'id_carrier' => array(
                'title' => $this->trans('ID', array(), 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'name' => array(
                'title' => $this->trans('Name', array(), 'Admin.Global'),
            ),
            'image' => array(
                'title' => $this->trans('Logo', array(), 'Admin.Global'),
                'align' => 'center',
                'image' => 's',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
            ),
            'delay' => array(
                'title' => $this->trans('Delay', array(), 'Admin.Shipping.Feature'),
                'orderby' => false,
            ),
            'active' => array(
                'title' => $this->trans('Status', array(), 'Admin.Global'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ),
            'is_free' => array(
                'title' => $this->trans('Free Shipping', array(), 'Admin.Shipping.Feature'),
                'align' => 'center',
                'active' => 'isFree',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ),
            'position' => array(
                'title' => $this->trans('Position', array(), 'Admin.Global'),
                'filter_key' => 'a!position',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'position' => 'position',
            ),
        );
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->trans('Carriers', array(), 'Admin.Shipping.Feature'),
                'icon' => 'icon-truck',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->trans('Company', array(), 'Admin.Global'),
                    'name' => 'name',
                    'required' => true,
                    'hint' => array(
                        $this->trans('Allowed characters: letters, spaces and "%special_chars%".', array('%special_chars%' => '().-'), 'Admin.Shipping.Help'),
                        $this->trans('Carrier name displayed during checkout', array(), 'Admin.Shipping.Help'),
                        $this->trans('For in-store pickup, enter 0 to replace the carrier name with your shop name.', array(), 'Admin.Shipping.Help'),
                    ),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->trans('Logo', array(), 'Admin.Global'),
                    'name' => 'logo',
                    'hint' => $this->trans('Upload a logo from your computer.', array(), 'Admin.Shipping.Help') . ' (.gif, .jpg, .jpeg ' . $this->trans('or', array(), 'Admin.Shipping.Help') . ' .png)',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Transit time', array(), 'Admin.Shipping.Feature'),
                    'name' => 'delay',
                    'lang' => true,
                    'required' => true,
                    'maxlength' => 512,
                    'hint' => $this->trans('Estimated delivery time will be displayed during checkout.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Speed grade', array(), 'Admin.Shipping.Feature'),
                    'name' => 'grade',
                    'required' => false,
                    'hint' => $this->trans('Enter "0" for a longest shipping delay, or "9" for the shortest shipping delay.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('URL', array(), 'Admin.Global'),
                    'name' => 'url',
                    'hint' => $this->trans('Delivery tracking URL: Type \'@\' where the tracking number should appear. It will then be automatically replaced by the tracking number.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'checkbox',
                    'label' => $this->trans('Zone', array(), 'Admin.Global'),
                    'name' => 'zone',
                    'values' => array(
                        'query' => Zone::getZones(false),
                        'id' => 'id_zone',
                        'name' => 'name',
                    ),
                    'hint' => $this->trans('The zones in which this carrier will be used.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'group',
                    'label' => $this->trans('Group access', array(), 'Admin.Shipping.Help'),
                    'name' => 'groupBox',
                    'values' => Group::getGroups(Context::getContext()->language->id),
                    'hint' => $this->trans('Mark the groups that are allowed access to this carrier.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Status', array(), 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                        ),
                    ),
                    'hint' => $this->trans('Enable the carrier in the front office.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Apply shipping cost', array(), 'Admin.Shipping.Feature'),
                    'name' => 'is_free',
                    'required' => false,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'is_free_on',
                            'value' => 0,
                            'label' => '<img src="../img/admin/enabled.gif" alt="' . $this->trans('Yes', array(), 'Admin.Global') . '" title="' . $this->trans('Yes', array(), 'Admin.Global') . '" />',
                        ),
                        array(
                            'id' => 'is_free_off',
                            'value' => 1,
                            'label' => '<img src="../img/admin/disabled.gif" alt="' . $this->trans('No', array(), 'Admin.Global') . '" title="' . $this->trans('No', array(), 'Admin.Global') . '" />',
                        ),
                    ),
                    'hint' => $this->trans('Apply both regular shipping cost and product-specific shipping costs.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Tax', array(), 'Admin.Global'),
                    'name' => 'id_tax_rules_group',
                    'options' => array(
                        'query' => TaxRulesGroup::getTaxRulesGroups(true),
                        'id' => 'id_tax_rules_group',
                        'name' => 'name',
                        'default' => array(
                            'label' => $this->trans('No Tax', array(), 'Admin.Global'),
                            'value' => 0,
                        ),
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->trans('Shipping and handling', array(), 'Admin.Shipping.Feature'),
                    'name' => 'shipping_handling',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'shipping_handling_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', array(), 'Admin.Global'),
                        ),
                        array(
                            'id' => 'shipping_handling_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', array(), 'Admin.Global'),
                        ),
                    ),
                    'hint' => $this->trans('Include the shipping and handling costs in the carrier price.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->trans('Billing', array(), 'Admin.Shipping.Feature'),
                    'name' => 'shipping_method',
                    'required' => false,
                    'class' => 't',
                    'br' => true,
                    'values' => array(
                        array(
                            'id' => 'billing_default',
                            'value' => Carrier::SHIPPING_METHOD_DEFAULT,
                            'label' => $this->trans('Default behavior', array(), 'Admin.Shipping.Feature'),
                        ),
                        array(
                            'id' => 'billing_price',
                            'value' => Carrier::SHIPPING_METHOD_PRICE,
                            'label' => $this->trans('According to total price', array(), 'Admin.Shipping.Feature'),
                        ),
                        array(
                            'id' => 'billing_weight',
                            'value' => Carrier::SHIPPING_METHOD_WEIGHT,
                            'label' => $this->trans('According to total weight', array(), 'Admin.Shipping.Feature'),
                        ),
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->trans('Out-of-range behavior', array(), 'Admin.Shipping.Feature'),
                    'name' => 'range_behavior',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 0,
                                'name' => $this->trans('Apply the cost of the highest defined range', array(), 'Admin.Shipping.Help'),
                            ),
                            array(
                                'id' => 1,
                                'name' => $this->trans('Disable carrier', array(), 'Admin.Shipping.Feature'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'hint' => $this->trans('Out-of-range behavior occurs when none is defined (e.g. when a customer\'s cart weight is greater than the highest range limit).', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Maximum package height', array(), 'Admin.Shipping.Feature'),
                    'name' => 'max_height',
                    'required' => false,
                    'hint' => $this->trans('Maximum height managed by this carrier. Set the value to "0," or leave this field blank to ignore.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Maximum package width', array(), 'Admin.Shipping.Feature'),
                    'name' => 'max_width',
                    'required' => false,
                    'hint' => $this->trans('Maximum width managed by this carrier. Set the value to "0," or leave this field blank to ignore.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Maximum package depth', array(), 'Admin.Shipping.Feature'),
                    'name' => 'max_depth',
                    'required' => false,
                    'hint' => $this->trans('Maximum depth managed by this carrier. Set the value to "0," or leave this field blank to ignore.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->trans('Maximum package weight', array(), 'Admin.Shipping.Feature'),
                    'name' => 'max_weight',
                    'required' => false,
                    'hint' => $this->trans('Maximum weight managed by this carrier. Set the value to "0," or leave this field blank to ignore.', array(), 'Admin.Shipping.Help'),
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'is_module',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'external_module_name',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'shipping_external',
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'need_range',
                ),
            ),
        );

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            );
        }

        $this->fields_form['submit'] = array(
            'title' => $this->trans('Save', array(), 'Admin.Actions'),
        );

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
                        echo 'ok position ' . (int) $position . ' for carrier ' . (int) $pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update carrier ' . (int) $id_carrier . ' to position ' . (int) $position . ' "}';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This carrier (' . (int) $id_carrier . ') can t be loaded"}';
                }

                break;
            }
        }
    }
}
