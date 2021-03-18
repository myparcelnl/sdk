<?php

namespace Gett\MyparcelBE\Module\Hooks;

use Configuration;
use Dispatcher;
use Gett\MyparcelBE\Constant;
use Gett\MyparcelBE\Label\LabelOptionsResolver;
use Gett\MyparcelBE\Module\Hooks\Helpers\AdminOrderList;
use Gett\MyparcelBE\Module\Hooks\Helpers\AdminOrderView;
use Order;
use Validate;

trait LegacyOrderPageHooks
{
    protected $carrierList = [];

    public function hookDisplayAdminListBefore()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            $adminOrderList = new AdminOrderList($this);

            return $adminOrderList->getAdminAfterHeader();
        }

        return '';
    }

    public function hookActionAdminOrdersListingFieldsModifier(&$params)
    {
        if (!isset($params['select'])) {
            $params['select'] = '';
        }
        if (!isset($params['join'])) {
            $params['join'] = '';
        }
        $prefix = 'car' . $this->id;

        $params['select'] .= ', 1 as `myparcel_void_1`, 1 as `myparcel_void_2`, a.`id_carrier`';
        $params['select'] .= ', a.`id_cart` , \'\' AS myparcel_void_0';
        $params['select'] .= ', ' . $prefix . '.`id_reference` AS id_carrier_reference';
        $params['select'] .= ', ' . $prefix . '.`name` AS carrier_name';

        $params['join'] .= '
            LEFT JOIN ' . _DB_PREFIX_ . 'carrier ' . $prefix . ' ON (a.id_carrier = ' . $prefix . '.id_carrier)';

        $params['fields']['myparcel_void_0'] = [
            'title' => $this->l('Delivery date', 'legacyorderpagehooks'),
            'callback' => 'printMyParcelDeliveryInfo',
            'search' => false,
            'orderby' => false,
            'callback_object' => $this,
        ];

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
        $sql->from('myparcelbe_order_label');
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

        $labelOptionsResolver = new LabelOptionsResolver();
        $carrierReference = (int) $this->carrierList[(int) $params['id_carrier']];
        $orderHelper = new AdminOrderList($this);

        $this->context->smarty->assign([
            'label_options' => $labelOptionsResolver->getLabelOptions($params),
            'allowSetSignature' => $orderHelper->allowSetSignature($carrierReference),
            'allowSetOnlyRecipient' => $orderHelper->allowSetOnlyRecipient($carrierReference),
        ]);

        return $this->display($this->name, 'views/templates/admin/icon-concept.tpl');
    }

    public function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller instanceof \AdminOrdersController
            || $this->context->controller->php_self == 'AdminOrders') {
            $adminOrder = new AdminOrderList($this);
            $adminOrder->setHeaderContent();
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

    public function hookDisplayInvoice($params): string
    {
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            return '';
        }
        $idOrder = (int) $params['id_order'];
        $controller = Dispatcher::getInstance()->getController();

        if (empty($idOrder) || $controller !== 'AdminOrders') {
            return '';
        }
        $order = new Order($idOrder);
        if (!Validate::isLoadedObject($order)) {
            return '';
        }
        $adminOrderView = new AdminOrderView($this, (int) $params['id_order'], $this->context);

        return $adminOrderView->display();
    }

    public function printMyParcelDeliveryInfo($id, $row)
    {
        if (empty($row['id_carrier_reference'])) {
            return '';
        }
        $adminOrderList = new AdminOrderList($this);
        if (!$adminOrderList->isMyParcelCarrier((int) $row['id_carrier_reference'])) {
            return '';
        }
        $deliverySettings = $this->getDeliverySettingsByCart($row['id_cart']);
        try {
            if (empty($deliverySettings['date'])) {
                return '';
            }
            $date = new \DateTime($deliverySettings['date']);
            $dateFormatted = $date->format($this->context->language->date_format_lite);
            if (!empty($dateFormatted)) {
                $id = sprintf('[%s] %s', $dateFormatted, $row['carrier_name']);
            }
        } catch (\Exception $exception) {
        }

        return $id;
    }
}
