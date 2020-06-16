<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Configuration;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Label\LabelOptionsResolver;

trait LegacyOrderPageHooks
{
    protected $carrierList = [];

    public function hookDisplayAdminListBefore()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            \Media::addJsDefL('print_labels_text', $this->l('Print labels', 'legacyorderpagehooks'));
            \Media::addJsDefL('refresh_labels_text', $this->l('Refresh labels', 'legacyorderpagehooks'));
            \Media::addJsDefL('create_label_text', $this->l('Create label', 'legacyorderpagehooks'));
            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/order.js'
            );

            $link = $this->context->link;
            $this->context->smarty->assign([
                'action' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'createLabel']),
                'download_action' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'downloadLabel']),
                'print_bulk_action' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'print']),
                'isBE' => $this->isBE(),
                'labelConfiguration' => $this->getLabelDefaultConfiguration(),
                'PACKAGE_TYPE' => Constant::PACKAGE_TYPE_CONFIGURATION_NAME,
                'ONLY_RECIPIENT' => Constant::ONLY_RECIPIENT_CONFIGURATION_NAME,
                'AGE_CHECK' => Constant::AGE_CHECK_CONFIGURATION_NAME,
                'PACKAGE_FORMAT' => Constant::PACKAGE_FORMAT_CONFIGURATION_NAME,
                'RETURN_PACKAGE' => Constant::RETURN_PACKAGE_CONFIGURATION_NAME,
                'SIGNATURE_REQUIRED' => Constant::SIGNATURE_REQUIRED_CONFIGURATION_NAME,
                'INSURANCE' => Constant::INSURANCE_CONFIGURATION_NAME,
            ]);

            return $this->display($this->name, 'views/templates/admin/hook/orders_popups.tpl');
        }

        return '';
    }

    public function hookActionAdminOrdersListingFieldsModifier($params)
    {
        if (!isset($params['select'])) {
            $params['select'] = '';
        }
        $params['select'] .= ',1 as `myparcel_void_1` ,1 as `myparcel_void_2`, a.id_carrier';

        $params['fields']['myparcel_void_1'] = [
            'title' => $this->l('Labels', 'legacyorderpagehooks'),
            'class' => 'pointer-myparcel-labels text-center',
            'callback' => 'printMyParcelLabel',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => $this,
        ];

        $params['fields']['myparcel_void_2'] = [
            'title' => '',
            'class' => 'text-nowrap',
            'callback' => 'printMyParcelIcon',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => $this,
        ];
    }

    public function printMyParcelLabel($id, $params)
    {
        if (!$this->searchMyParcelCarrier((int) $params['id_carrier'])) {
            return '';
        }
        $sql = new \DbQuery();
        $sql->select('*');
        $sql->from('myparcel_order_label');
        $sql->where('id_order = "' . pSQL($params['id_order']) . '" ');
        $result = \Db::getInstance()->executeS($sql);
        $link = $this->context->link;

        $this->context->smarty->assign([
            'labels' => $result,
            'link' => $link,
            'promptForLabelPosition' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
        ]);

        return $this->display($this->name, 'views/templates/admin/icon-labels.tpl');
    }

    public function printMyParcelIcon($id, $params)
    {
        if (!$this->searchMyParcelCarrier((int) $params['id_carrier'])) {
            return '';
        }

        $label_options_resolver = new LabelOptionsResolver();

        $this->context->smarty->assign([
            'label_options' => $label_options_resolver->getLabelOptions($params),
        ]);

        return $this->display($this->name, 'views/templates/admin/icon-concept.tpl');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            $link = new \Link();
            \Media::addJsDef(
                [
                    'default_label_size' => Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME),
                    'default_label_position' => Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME),
                    'prompt_for_label_position' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                    'create_labels_bulk_route' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'createb']),
                    'refresh_labels_bulk_route' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'refresh']),
                    'create_label_action' => $link->getAdminLink('AdminLabel', true, [], ['action' => 'create']),
                    'create_label_error' => $this->l('Cannot create label for orders', 'legacyorderpagehooks'),
                    'no_order_selected_error' => $this->l('Please select at least one order first.', 'legacyorderpagehooks'),
                ]
            );

            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/order.js'
            );
        } elseif ($this->context->controller->php_self == 'AdminOrders') { //symfony controller
            \Media::addJsDef(
                [
                    'default_label_size' => Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : Configuration::get(Constant::LABEL_SIZE_CONFIGURATION_NAME),
                    'default_label_position' => Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : Configuration::get(Constant::LABEL_POSITION_CONFIGURATION_NAME),
                    'prompt_for_label_position' => Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : Configuration::get(Constant::LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                ]
            );

            $this->context->controller->addJS(
                $this->_path . 'views/js/admin/symfony/orders-list.js'
            );
        }
    }

    public function searchMyParcelCarrier($idCarrier)
    {
        $carrier = $this->carrierList[$idCarrier] ?? new \Carrier($idCarrier);
        if (empty($this->carrierList[$idCarrier])) {
            $this->carrierList[$idCarrier] = $carrier->id_reference;
        }

        return in_array($this->carrierList[$idCarrier], [
            Configuration::get(Constant::DPD_CONFIGURATION_NAME),
            Configuration::get(Constant::BPOST_CONFIGURATION_NAME),
            Configuration::get(Constant::POSTNL_CONFIGURATION_NAME),
        ]);
    }
    
    public function getLabelDefaultConfiguration(): array
    {
        return Configuration::getMultiple([
            Constant::LABEL_SIZE_CONFIGURATION_NAME,
            Constant::LABEL_POSITION_CONFIGURATION_NAME,
        ]);
    }
}