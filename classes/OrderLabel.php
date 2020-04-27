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
            if (Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME)) {
                MyParcelOrderHistory::setShipped($idShipment, false);
            } else {
                MyParcelOrderHistory::setPrinted($idShipment, false);
            }
        } else {
            if ($statusCode >= 2) {
                MyParcelOrderHistory::setPrinted($idShipment);
            }
            if ($statusCode >= 3) {
                MyParcelOrderHistory::setShipped($idShipment);
            }
            if ($statusCode >= 7 && $statusCode <= 11) {
                MyParcelOrderHistory::setReceived($idShipment);
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
        return \Db::getInstance()->executeS("SELECT * FROM " ._DB_PREFIX_.self::$definition['table'] ." WHERE id_label = '".$label_id."' ")[0];
    }
}