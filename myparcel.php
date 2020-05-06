<?php

use Gett\MyParcel\Module\Hooks\FrontHooks;
use Gett\MyParcel\Module\Hooks\OrderLabelHooks;
use Gett\MyParcel\Module\Hooks\OrdersGridHooks;
use Gett\MyParcel\Module\Configuration\Configure;
use Gett\MyParcel\Module\Hooks\LegacyOrderPageHooks;
use Gett\MyParcel\Module\Hooks\DisplayBackOfficeHeader;
use Gett\MyParcel\Module\Hooks\DisplayAdminProductsExtra;

if (!defined('_PS_VERSION_')) {
    exit;
}
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class MyParcel extends CarrierModule
{
    use DisplayAdminProductsExtra;
    use DisplayBackOfficeHeader;
    use OrdersGridHooks;
    use FrontHooks;
    use LegacyOrderPageHooks;
    use OrderLabelHooks;
    public $baseUrl;
    public $id_carrier;
    public $migrations = [
        \Gett\MyParcel\Database\CreateProductConfigurationTableMigration::class,
        \Gett\MyParcel\Database\CreateCarrierConfigurationTableMigration::class,
        \Gett\MyParcel\Database\CreateOrderLabelTableMigration::class,
        \Gett\MyParcel\Database\CreateDeliverySettingTableMigration::class,
    ];

    public $hooks = [
        'displayAdminProductsExtra',
        'displayBackOfficeHeader',
        'actionProductUpdate',
        'displayCarrierExtraContent',
        'displayHeader',
        'actionCarrierProcess',
        'actionOrderGridDefinitionModifier',
        'actionAdminControllerSetMedia',
        'actionOrderGridQueryBuilderModifier',
        'actionAdminOrdersListingFieldsModifier',
        'displayAdminListBefore',
        'actionAdminControllerSetMedia',
        'displayAdminOrderMainBottom',
        'actionObjectGettMyParcelOrderLabelAddAfter',
        'actionObjectGettMyParcelOrderLabelUpdateAfter',
    ];
    /** @var string $baseUrlWithoutToken */
    protected $baseUrlWithoutToken;

    public function __construct()
    {
        $this->name = 'myparcel';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'Gett';
        $this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();

        if (!empty(Context::getContext()->employee->id)) {
            $this->baseUrlWithoutToken = $this->getAdminLink(
                'AdminModules',
                false,
                [
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                ]
            );
            $this->baseUrl = $this->getAdminLink(
                'AdminModules',
                true,
                [
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                ]
            );
        }
        $this->displayName = $this->l('MyParcel');
        $this->description = $this->l('PrestaShop module to intergratie with MyParcel NL and MyParcel BE');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function getAdminLink(string $controller, bool $withToken = true, array $params = [])
    {
        $url = parse_url($this->context->link->getAdminLink($controller, $withToken));
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $query);
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            $url['query'] = http_build_query($query + $params, PHP_QUERY_RFC1738);
        } else {
            $url['query'] = http_build_query($query + $params);
        }

        return $this->mypa_stringify_url($url);
    }

    public function getContent()
    {
        $configuration = new Configure($this);

        $this->context->smarty->assign([
            'menutabs' => $configuration->initNavigation(),
            'ajaxUrl' => $this->baseUrlWithoutToken,
        ]);

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->display(__FILE__, 'views/templates/admin/navbar.tpl');

        return $output . $configuration(Tools::getValue('menu'));
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        return 228;
    }

    public function getOrderShippingCostExternal($params)
    {
        return true;
    }

    public function install(): bool
    {
        return parent::install()
            && (new \Gett\MyParcel\Module\Installer($this))();
    }

    public function uninstall(): bool
    {
        return (new \Gett\MyParcel\Module\Uninstaller($this))()
            && parent::uninstall();
    }

    public function appendQueryToUrl($urlString, $query = [])
    {
        $url = parse_url($urlString);
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $oldQuery);
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            $url['query'] = http_build_query($oldQuery + $query, PHP_QUERY_RFC1738);
        } else {
            $url['query'] = http_build_query($oldQuery + $query);
        }

        return $this->mypa_stringify_url($url);
    }

    public static function updateStatus($idShipment, $barcode, $statusCode, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }

        $order = static::getOrderByShipmentId($idShipment);

        if (!$order->shipping_number) {
            // Checking a legacy field is allowed in this case
            static::updateOrderTrackingNumber($order, $barcode);
        }

        if ($statusCode === 14) {
            if (Configuration::get(MyParcel::DIGITAL_STAMP_USE_SHIPPED_STATUS)) {
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

        return (bool) Db::getInstance()->update(
            bqSQL(static::$definition['table']),
            [
                'tracktrace' => pSQL($barcode),
                'postnl_status' => (int) $statusCode,
                'date_upd' => pSQL($date),
            ],
            'id_shipment = ' . (int) $idShipment
        );
    }

    public static function getOrderByShipmentId(int $id_shipment)
    {
        $sql = new \DbQuery();
        $sql->select('*');
        $sql->from('myparcel_order_label', 'mol');
        $sql->where('mol.`id_label` = ' . $id_shipment);

        $shipment = Db::getInstance()->getRow($sql);

        if ($shipment) {
            return $shipment;
        }

        return false;
    }

    private function mypa_stringify_url($parsedUrl)
    {
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user = isset($parsedUrl['user']) ? $parsedUrl['user'] : '';
        $pass = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass'] : '';
        $pass = ($user || $pass) ? "{$pass}@" : '';
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "{$scheme}{$user}{$pass}{$host}{$port}{$path}{$query}{$fragment}";
    }
}
