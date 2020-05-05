<?php

namespace Gett\MyParcel;

class OrderLabel extends \ObjectModel
{
    public $id_order;
    public $status;
    public $new_order_state;
    public $barcode;
    public $track_link;
    public $payment_url;
    public $id_label;

    public static $definition = array(
        'table' => "myparcel_order_label",
        'primary' => 'id_order_label',
        'multilang' => false,
        'fields' => array(
            'id_order' => array('type' => self::TYPE_INT, 'required' => TRUE),
            'status' => array('type' => self::TYPE_STRING),
            'new_order_state' => array('type' => self::TYPE_INT),
            'barcode' => array('type' => self::TYPE_STRING),
            'track_link' => array('type' => self::TYPE_STRING),
            'payment_url' => array('type' => self::TYPE_STRING),
            'id_label' => array('type' => self::TYPE_STRING)
        )
    );

    public static function updateStatus($idShipment, $barcode, $statusCode, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }

        if ($statusCode === 14) {
            if (\Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME)) {
                OrderLabel::setShipped($idShipment, false);
            } else {
                OrderLabel::setPrinted($idShipment, false);
            }
        } else {
            if ($statusCode >= 2) {
                OrderLabel::setPrinted($idShipment);
            }
            if ($statusCode >= 3) {
                OrderLabel::setShipped($idShipment);
            }
            if ($statusCode >= 7 && $statusCode <= 11) {
                OrderLabel::setReceived($idShipment);
            }
        }

        MyParcelOrderHistory::log($idShipment, $statusCode, $date);

        return (bool)Db::getInstance()->update(
            bqSQL(static::$definition['table']),
            array(
                'tracktrace' => pSQL($barcode),
                'postnl_status' => (int)$statusCode,
                'date_upd' => pSQL($date),
            ),
            'id_shipment = ' . (int)$idShipment
        );
    }

    public static function findByLabelId(int $label_id)
    {
        $id = \Db::getInstance()->getValue("SELECT id_order_label FROM " ._DB_PREFIX_.self::$definition['table'] ." WHERE id_label = '".$label_id."' ");

        return new OrderLabel($id);
    }

    public static function setShipped($idShipment, $mail = true)
    {
        $targetOrderState = \Configuration::get('PS_OS_SHIPPING');
        if (!Configuration::get(MyParcel::NOTIFICATION_MOMENT) && $mail) {
            static::sendShippedNotification($idShipment);
        }

        if (!$targetOrderState) {
            return;
        }

        static::setOrderStatus($idShipment, $targetOrderState);
    }

    public static function setPrinted($idShipment, $mail = true)
    {
        $targetOrderState = 14;
        if ($mail && \Configuration::get(MyParcel::NOTIFICATION_MOMENT)) {
            static::sendShippedNotification($idShipment);
        }

        if (!$targetOrderState) {
            return;
        }

        static::setOrderStatus($idShipment, $targetOrderState);
    }

    public static function sendShippedNotification()
    {

    }

    public static function setOrderStatus($idShipment, $status, $addWithEmail = true)
    {
        $targetOrderState = (int)$status;
        if (!$targetOrderState) {
            return;
        }
        $order = MyParcelOrder::getOrderByShipmentId($idShipment);
        $shipment = MyParcelOrder::getByShipmentId($idShipment);
        if (!Validate::isLoadedObject($order) || !Validate::isLoadedObject($shipment)) {
            return;
        }
        if (in_array($order->getCurrentState(), MyParcel::getIgnoredStatuses())) {
            return;
        }
        $shipment = mypa_dot(@json_decode($shipment->shipment, true));

        $idOrder = (int)$order->id;
        $history = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS("SELECT `id_order_state` FROM " . _DB_PREFIX_ . "order_history WHERE `id_order` = $idOrder");
        if (is_array($history)) {
            $history = array_column($history, 'id_order_state');
            if (in_array($targetOrderState, $history)) {
                return;
            }
        }

        $history = new OrderHistory();
        $history->id_order = (int)$order->id;
        $history->changeIdOrderState($targetOrderState, (int)$order->id, !$order->hasInvoice());
        if ($addWithEmail && !in_array((int)$shipment->get('options.package_type'), array(1, 2))) {
            $history->addWithemail();
        } else {
            $history->add();
        }
    }

    public static function setReceived($idShipment)
    {
        $targetOrderState = (int)Configuration::get(MyParcel::RECEIVED_STATUS);
        if (!$targetOrderState) {
            return;
        }

        static::setOrderStatus($idShipment, $targetOrderState);
    }

    public static function getDataForLabelsCreate(array $orderIds)
    {
        $qb = new \DbQuery();
        $qb->select('o.id_order,
                    o.reference,
                    co.iso_code,
                    CONCAT(a.firstname, " ",a.lastname) as person,
                    CONCAT(a.address1, " ", a.address2) as full_street,
                    a.postcode,
                    a.city,
                    c.email,
                    a.phone,
                    ds.delivery_settings,
                    o.id_carrier,
                    a.id_country
                    ');
        $qb->from('orders', 'o');
        $qb->innerJoin('address', 'a', 'o.id_address_delivery = a.id_address');
        $qb->innerJoin('country', 'co', 'co.id_country = a.id_country');
        $qb->innerJoin('customer', 'c', 'o.id_customer = c.id_customer');
        $qb->innerJoin('myparcel_delivery_settings', 'ds', 'o.id_cart = ds.id_cart');

        $qb->where('o.id_order IN (' . implode(',', $orderIds) . ') ');

        return \Db::getInstance()->executeS($qb);
    }

    public static function getOrderDeliveryOptions(int $id_order)
    {
        $qb = new \DbQuery();
        $qb->select('ds.delivery_settings');
        $qb->from('myparcel_delivery_settings', 'ds');
        $qb->innerJoin('orders', 'o', 'o.id_cart = ds.id_cart');
        $qb->where('o.id_order = "'.$id_order.'" ');

        $res = \Db::getInstance()->executeS($qb);
        if (isset($res[0]['delivery_settings'])) {
            return json_decode($res[0]['delivery_settings']);
        }

        return false;
    }

    public static function getOrderProducts(int $id_order)
    {
        $qb = new \DbQuery();
        $qb->select('od.product_id');
        $qb->from('order_detail', 'od');
        $qb->where('od.id_order = "'.$id_order.'" ');


        return \Db::getInstance()->executeS($qb);
    }

    public static function getOrderLabels(array $orders_id)
    {
        $qb = new \DbQuery();
        $qb->select('ol.id_label');
        $qb->from('myparcel_order_label', 'ol');
        $qb->where('ol.id_order IN (' . implode(',', $orders_id) . ') ');

        $return = [];
        foreach (\Db::getInstance()->executeS($qb) as $item) {
            $return[] = $item['id_label'];
        }

        return $return;
    }

    public static function getCustomsOrderProducts(int $id_order)
    {
        $qb = new \DbQuery();
        $qb->select('od.product_id, pc.value , od.product_quantity, od.product_name, od.product_price, od.product_weight');
        $qb->from('order_detail', 'od');
        $qb->innerJoin('myparcel_product_configuration', 'pc', 'od.product_id = pc.id_product');
        $qb->where('od.id_order = "'.$id_order.'" AND pc.name = "'.Constant::MY_PARCEL_CUSTOMS_FORM_CONFIGURATION_NAME.'" ');

        $return = [];
        foreach (\Db::getInstance()->executeS($qb) as $item) {
            if ($item['value'] && $item['value'] == 'No') {
                return false;
            }
        }
    }
}