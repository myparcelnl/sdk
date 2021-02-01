<?php

namespace Gett\MyparcelBE;

use Address;
use Configuration;
use Context;
use Country;
use Customer;
use Db;
use DbQuery;
use Gett\MyparcelBE\Service\Tracktrace;
use Language;
use Mail;
use MyParcelNL\Sdk\src\Model\Consignment\AbstractConsignment;
use Gett\MyparcelBE\Service\MyparcelStatusProvider;
use Order;
use Translate;
use Validate;

class OrderLabel extends \ObjectModel
{
    public $id_order;
    public $status;
    public $new_order_state;
    public $barcode;
    public $track_link;
    public $payment_url;
    public $id_label;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'myparcelbe_order_label',
        'primary' => 'id_order_label',
        'multilang' => false,
        'fields' => [
            'id_order' => ['type' => self::TYPE_INT, 'required' => true],
            'status' => ['type' => self::TYPE_STRING],
            'new_order_state' => ['type' => self::TYPE_INT],
            'barcode' => ['type' => self::TYPE_STRING],
            'track_link' => ['type' => self::TYPE_STRING],
            'payment_url' => ['type' => self::TYPE_STRING],
            'id_label' => ['type' => self::TYPE_STRING],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public static function updateStatus($idShipment, $barcode, $statusCode, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }

        if ($statusCode === 14) {
            $myparcel = \Module::getInstanceByName('myparacelbe');
            if ($myparcel->isNL()
                && \Configuration::get(\Gett\MyparcelBE\Constant::SENT_ORDER_STATE_FOR_DIGITAL_STAMPS_CONFIGURATION_NAME)) {
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

        return true;
    }

    public static function findByLabelId(int $label_id)
    {
        $id = Db::getInstance()->getValue('SELECT id_order_label FROM ' . _DB_PREFIX_ . self::$definition['table'] . " WHERE id_label = '" . $label_id . "' ");

        return new OrderLabel($id);
    }

    public static function setShipped($idShipment, $mail = true)
    {
        $targetOrderState = \Configuration::get('PS_OS_SHIPPING');
        if (\Configuration::get(Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME) == 'first_scan' && $mail) {
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
        if ($mail && \Configuration::get(Constant::ORDER_NOTIFICATION_AFTER_CONFIGURATION_NAME) == 'printed') {
            static::sendShippedNotification($idShipment);
        }

        if (!$targetOrderState) {
            return;
        }

        static::setOrderStatus($idShipment, $targetOrderState);
    }

    public static function sendShippedNotification(int $idShipment)
    {
        if (!\Configuration::get(Constant::STATUS_CHANGE_MAIL_CONFIGURATION_NAME)) {
            return;
        }
        $order_label = self::findByLabelId($idShipment);
        if (!Validate::isLoadedObject($order_label)) {
            return;
        }
        $order = new Order($order_label->id_order);
        if (!Validate::isLoadedObject($order)) {
            return;
        }

        $customer = new Customer($order->id_customer);
        if (!Validate::isEmail($customer->email)) {
            return;
        }
        $address = new Address($order->id_address_delivery);
        $deliveryOptions = self::getOrderDeliveryOptions($order_label->id_order);
        $mailIso = Language::getIsoById($order->id_lang);
        $mailIsoEn = 'en';
        $mailIsoUpper = strtoupper($mailIso);
        $countryIso = strtoupper(Country::getIsoById($address->id_country));
        $templateVars = [
            '{firstname}' => $address->firstname,
            '{lastname}' => $address->lastname,
            '{shipping_number}' => $order_label->barcode,
            '{followup}' => "http://postnl.nl/tracktrace/?L={$mailIsoUpper}&B={$order_label->barcode}&P={$address->postcode}&D={$countryIso}&T=C",
            '{order_name}' => $order->getUniqReference(),
            '{order_id}' => $order->id,
            '{utc_offset}' => date('P'),
        ];
        // Assume PHP localization is not available
        $nlDays = [
            1 => 'maandag',
            2 => 'dinsdag',
            3 => 'woensdag',
            4 => 'donderdag',
            5 => 'vrijdag',
            6 => 'zaterdag',
            0 => 'zondag',
        ];
        $nlMonths = [
            1 => 'januari',
            2 => 'februari',
            3 => 'maart',
            4 => 'april',
            5 => 'mei',
            6 => 'juni',
            7 => 'juli',
            8 => 'augustus',
            9 => 'september',
            10 => 'oktober',
            11 => 'november',
            12 => 'december',
        ];
        $tracktraceInfo = (new Tracktrace(\Configuration::get(Constant::API_KEY_CONFIGURATION_NAME)))
            ->getTrackTrace($order_label->id_label, true);
        $deliveryDate = $tracktraceInfo['data']['tracktraces'][0]['delivery_moment']['start']['date']
            ?? $tracktraceInfo['data']['tracktraces'][0]['options']['delivery_date']
            ?? $deliveryOptions->date;
        $deliveryDateFrom = $tracktraceInfo['data']['tracktraces'][0]['delivery_moment']['start']['date'] ?? $deliveryOptions->date;
        $deliveryDateTo = $tracktraceInfo['data']['tracktraces'][0]['delivery_moment']['end']['date'] ?? $deliveryOptions->date;
        $dayNumber = (int) date('w', strtotime($deliveryDate));
        $monthNumber = (int) date('n', strtotime($deliveryDate));
        $templateVars['{delivery_street}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['street'];
        $templateVars['{delivery_number}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['street_additional_info'] . ' ' . $tracktraceInfo['data']['tracktraces'][0]['recipient']['number'];
        $templateVars['{delivery_postcode}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['postal_code'];
        $templateVars['{delivery_city}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['city'];
        $templateVars['{delivery_cc}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['cc'];
        $templateVars['{pickup_name}'] = $tracktraceInfo['data']['tracktraces'][0]['pickup']['location_name'];
        $templateVars['{pickup_street}'] = $tracktraceInfo['data']['tracktraces'][0]['pickup']['street'];
        $templateVars['{pickup_number}'] = $tracktraceInfo['data']['tracktraces'][0]['pickup']['number'];
        $templateVars['{pickup_postcode}'] = strtoupper(str_replace(' ', '', $tracktraceInfo['data']['tracktraces'][0]['pickup']['postal_code']));
        $templateVars['{pickup_region}'] = $tracktraceInfo['data']['tracktraces'][0]['pickup']['region'] ?: '-';
        $templateVars['{pickup_city}'] = $tracktraceInfo['data']['tracktraces'][0]['pickup']['city'];
        $templateVars['{pickup_cc}'] = $tracktraceInfo['data']['tracktraces'][0]['recipient']['cc'];

        if (!$deliveryOptions->deliveryType || in_array(
            AbstractConsignment::DELIVERY_TYPES_NAMES_IDS_MAP[$deliveryOptions->deliveryType],
            [1, 2, 3]
        )) {
            $templateVars['{delivery_day_name}'] = date('l', strtotime($deliveryDateFrom));
            $templateVars['{delivery_day}'] = date('j', strtotime($deliveryDateFrom));
            $templateVars['{delivery_day_leading_zero}'] = date('d', strtotime($deliveryDateFrom));
            $templateVars['{delivery_month}'] = date('n', strtotime($deliveryDateFrom));
            $templateVars['{delivery_month_leading_zero}'] = date('m', strtotime($deliveryDateFrom));
            $templateVars['{delivery_month_name}'] = date('F', strtotime($deliveryDateFrom));
            $templateVars['{delivery_year}'] = date('Y', strtotime($deliveryDateFrom));
            $templateVars['{delivery_time_from}'] = date('H:i', strtotime($deliveryDateFrom));
            $templateVars['{delivery_time_from_localized}'] = date('h:i A', strtotime($deliveryDateFrom));
            $templateVars['{delivery_time_to}'] = date('H:i', strtotime($deliveryDateTo));
            $templateVars['{delivery_time_to_localized}'] = date('h:i A', strtotime($deliveryDateTo));
        } elseif (in_array($deliveryOptions->deliveryType, [4, 5])) {
            $cc = $tracktraceInfo['data']['tracktraces'][0]['recipient']['cc'];
            $pickup_city = $tracktraceInfo['data']['tracktraces'][0]['pickup']['city'];
            $count1 = strtoupper($cc);
            $count = str_replace(' ', '+', $pickup_city, $count1);
            $googleMapsDestinationLocation = implode(
                ',',
                [
                    str_replace(' ', '+', $tracktraceInfo['data']['tracktraces'][0]['pickup']['street'] . ' ' . str_replace(
                        ' ',
                        '+',
                        $tracktraceInfo['data']['tracktraces'][0]['pickup']['number'] . $tracktraceInfo['data']['tracktraces'][0]['pickup']['number_suffix'],
                        $count
                    )), ]
            );
            $str_replace = str_replace(' ', '+', $tracktraceInfo['data']['tracktraces'][0]['recipient']['city']);

            if ($mailIsoUpper === 'NL') {
                $dayNumber = (int) date('w', strtotime($deliveryDateFrom));
                $templateVars['{delivery_day_name}'] = $nlDays[$dayNumber];
                $templateVars['{delivery_day}'] = date('j', strtotime($deliveryDateFrom));
                $templateVars['{delivery_day_leading_zero}'] = date('d', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month}'] = date('n', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month_leading_zero}'] = date('m', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month_name}'] = $nlMonths[$monthNumber];
                $templateVars['{delivery_year}'] = date('Y', strtotime($deliveryDateFrom));
                $templateVars['{delivery_time_from}'] = '15:00';
                $templateVars['{delivery_time_from_localized}'] = '15:00';
            } else {
                $templateVars['{delivery_day_name}'] = date('l', strtotime($deliveryDateFrom));
                $templateVars['{delivery_day}'] = date('d', strtotime($deliveryDateFrom));
                $templateVars['{delivery_day_leading_zero}'] = date('d', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month}'] = date('m', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month_leading_zero}'] = date('m', strtotime($deliveryDateFrom));
                $templateVars['{delivery_month_name}'] = date('F', strtotime($deliveryDateFrom));
                $templateVars['{delivery_year}'] = date('Y', strtotime($deliveryDateFrom));
                $templateVars['{delivery_time_from}'] = '15:00';
                $templateVars['{delivery_time_from_localized}'] = '03:00 PM';
            }
            foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
                $dayFrom = $deliveryOptions->opening_hours->{$day}[0];
                if (strpos($dayFrom, '-') !== false) {
                    list($dayFrom) = explode('-', $dayFrom);
                }
                $dayTo = $deliveryOptions->opening_hours->{$day}[count($deliveryOptions->opening_hours->{$day}) - 1];
                if (strpos($dayTo, '-') !== false) {
                    list(, $dayTo) = array_pad(explode('-', $dayTo), 2, '');
                }
                if ($dayFrom) {
                    $dayFull = "{$dayFrom} - {$dayTo}";
                } else {
                    $dayFull = Translate::getModuleTranslation('myparcelbe', 'Closed', 'myparcelbe');
                }
                $templateVars["{opening_hours_{$day}_from}"] = $dayFrom;
                $templateVars["{opening_hours_{$day}_to}"] = $dayTo;
                $templateVars["{opening_hours_{$day}}"] = $dayFull;
            }
        }

        $mailType = ($tracktraceInfo['data']['tracktraces'][0]['options']['package_type'] === AbstractConsignment::PACKAGE_TYPE_MAILBOX)
            ? 'mailboxpackage'
            : 'standard';
        if ($deliveryOptions->isPickup) {
            $mailType = 'pickup';
        }

        $mailDir = false;
        if (file_exists(_PS_THEME_DIR_ . "modules/myparcelbe/mails/{$mailIso}/myparcel_{$mailType}_shipped.txt")
            && file_exists(
                _PS_THEME_DIR_ . "modules/myparcelbe/mails/{$mailIso}/myparcel_{$mailType}_shipped.html"
            )
        ) {
            $mailDir = _PS_THEME_DIR_ . 'modules/myparcelbe/mails/';
        } elseif (file_exists(dirname(__FILE__) . "/../mails/{$mailIso}/myparcel_{$mailType}_shipped.txt")
            && file_exists(dirname(__FILE__) . "/../mails/{$mailIso}/myparcel_{$mailType}_shipped.html")
        ) {
            $mailDir = dirname(__FILE__) . '/../mails/';
        } elseif (file_exists(_PS_THEME_DIR_ . "modules/myparcelbe/mails/{$mailIsoEn}/myparcel_{$mailType}_shipped.txt")
            && file_exists(
                _PS_THEME_DIR_ . "modules/myparcelbe/mails/{$mailIsoEn}/myparcel_{$mailType}_shipped.html"
            )
        ) {
            $mailDir = _PS_THEME_DIR_ . 'modules/myparcelbe/mails/';
        } elseif (file_exists(dirname(__FILE__) . "/../mails/{$mailIsoEn}/myparcel_{$mailType}_shipped.txt")
            && file_exists(dirname(__FILE__) . "/../mails/{$mailIsoEn}/myparcel_{$mailType}_shipped.html")
        ) {
            $mailDir = dirname(__FILE__) . '/../mails/';
        }

        if ($mailDir) {
            Mail::send(
                $order->id_lang,
                "myparcel_{$mailType}_shipped",
                $mailIsoUpper === 'NL' ? "Bestelling {$order->getUniqReference()} is verzonden" : "Order {$order->getUniqReference()} has been shipped",
                $templateVars,
                (string) $customer->email,
                null,
                (string) Configuration::get(
                    'PS_SHOP_EMAIL',
                    null,
                    null,
                    Context::getContext()->shop->id
                ),
                (string) Configuration::get(
                    'PS_SHOP_NAME',
                    null,
                    null,
                    Context::getContext()->shop->id
                ),
                null,
                null,
                $mailDir,
                false,
                Context::getContext()->shop->id
            );
        }
    }

    public static function setOrderStatus($idShipment, $status, $addWithEmail = true)
    {
        $targetOrderState = (int) $status;
        if (!$targetOrderState) {
            return;
        }

        $order_label = self::findByLabelId($idShipment);
        $order = new \Order($order_label->id_order);

        if (!\Validate::isLoadedObject($order_label) || !\Validate::isLoadedObject($order)) {
            return;
        }
        $ignore = \Configuration::get(Constant::IGNORE_ORDER_STATUS_CONFIGURATION_NAME);
        if ($ignore) {
            $ignore = explode(',', $ignore);
        }
        if (in_array($order->getCurrentState(), $ignore)) {
            return;
        }

        $idOrder = (int) $order->id;
        $history = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT `id_order_state` FROM ' . _DB_PREFIX_ . "order_history WHERE `id_order` = {$idOrder}");
        if (is_array($history)) {
            $history = array_column($history, 'id_order_state');
            if (in_array($targetOrderState, $history)) {
                return;
            }
        }

        $history = new \OrderHistory();
        $history->id_order = (int) $order->id;
        $history->changeIdOrderState($targetOrderState, (int) $order->id, !$order->hasInvoice());
        if ($addWithEmail) {
            $history->addWithemail();
        } else {
            $history->add();
        }
    }

    public static function setReceived($idShipment)
    {
        $targetOrderState = (int) 5;
        if (!$targetOrderState) {
            return;
        }

        static::setOrderStatus($idShipment, $targetOrderState);
    }

    public static function getDataForLabelsCreate(array $orderIds)
    {
        $qb = new DbQuery();
        $qb->select('o.id_order,
                    o.id_order AS id,
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
                    a.id_country,
                    o.invoice_number
                    ');
        $qb->from('orders', 'o');
        $qb->innerJoin('address', 'a', 'o.id_address_delivery = a.id_address');
        $qb->innerJoin('country', 'co', 'co.id_country = a.id_country');
        $qb->innerJoin('customer', 'c', 'o.id_customer = c.id_customer');
        $qb->innerJoin('myparcelbe_delivery_settings', 'ds', 'o.id_cart = ds.id_cart');

        $qb->where('o.id_order IN (' . implode(',', $orderIds) . ') ');

        return Db::getInstance()->executeS($qb);
    }

    public static function getOrderDeliveryOptions(int $id_order)
    {
        $qb = new DbQuery();
        $qb->select('ds.delivery_settings');
        $qb->from('myparcelbe_delivery_settings', 'ds');
        $qb->innerJoin('orders', 'o', 'o.id_cart = ds.id_cart');
        $qb->where('o.id_order = "' . $id_order . '" ');

        $res = Db::getInstance()->executeS($qb);
        if (isset($res[0]['delivery_settings'])) {
            return json_decode($res[0]['delivery_settings']);
        }

        return false;
    }

    public static function getOrderProducts(int $id_order)
    {
        $qb = new DbQuery();
        $qb->select('od.product_id');
        $qb->from('order_detail', 'od');
        $qb->where('od.id_order = "' . $id_order . '" ');

        return Db::getInstance()->executeS($qb);
    }

    public static function getOrdersLabels(array $orders_id)
    {
        $qb = new DbQuery();
        $qb->select('ol.id_label');
        $qb->from('myparcelbe_order_label', 'ol');
        $qb->where('ol.id_order IN (' . implode(',', $orders_id) . ') ');

        $return = [];
        foreach (Db::getInstance()->executeS($qb) as $item) {
            $return[] = $item['id_label'];
        }

        return $return;
    }

    public static function getOrderLabels(int $order_id, array $label_ids = [])
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('myparcelbe_order_label');
        $sql->where('id_order = ' . (int) $order_id);
        if (!empty($label_ids)) {
            $sql->where('id_label IN(' . implode(',', $label_ids) . ')');
        }

        return Db::getInstance()->executeS($sql);
    }

    public static function getCustomsOrderProducts(int $id_order)
    {
        $qb = new DbQuery();
        $qb->select('od.product_id, pc.value , od.product_quantity, od.product_name, od.product_weight');
        $qb->select('od.unit_price_tax_incl');
        $qb->from('order_detail', 'od');
        $qb->leftJoin('myparcelbe_product_configuration', 'pc', 'od.product_id = pc.id_product');
        $qb->where('od.id_order = ' . $id_order);
        $qb->where('pc.name = "' . pSQL(Constant::CUSTOMS_FORM_CONFIGURATION_NAME) . '"');

        $return = Db::getInstance()->executeS($qb);
        foreach ($return as $item) {
            if ($item['value'] && $item['value'] == 'No') {
                return false;
            }
        }

        return $return;
    }

    public static function createFromConsignment(
        AbstractConsignment $consignment,
        MyparcelStatusProvider $status_provider
    ) {
        $orderLabel = new self();
        $orderLabel->id_label = $consignment->getConsignmentId();
        $orderLabel->id_order = $consignment->getReferenceId();
        $orderLabel->barcode = $consignment->getBarcode();
        $orderLabel->track_link = $consignment->getBarcodeUrl(
            $consignment->getBarcode(),
            $consignment->getPostalCode(),
            $consignment->getCountry()
        );
        $orderLabel->new_order_state = $consignment->getStatus();
        $orderLabel->status = $status_provider->getStatus($consignment->getStatus());
        if ($orderLabel->add()) {
            return (int) $orderLabel->id_label;
        }

        return 0;
    }

    public static function getOrderIdByLabelId(int $labelId): int
    {
        $sql = new DbQuery();
        $sql->select('id_order');
        $sql->from('myparcelbe_order_label');
        $sql->where('id_label = ' . (int) $labelId);

        return (int) Db::getInstance()->getValue($sql);
    }
}
