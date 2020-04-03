<?php

use Gett\MyParcel\Constant;
use Gett\MyParcel\Module\Configuration\ApiFormmm;
use Gett\MyParcel\Module\Configuration\Configure;
use Gett\MyParcel\Module\Hooks\DisplayAdminProductsExtra;

if (!defined('_PS_VERSION_')) {
    exit;
}
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class myparcel extends CarrierModule
{
    use DisplayAdminProductsExtra;

    protected $baseUrl;
    /** @var string $baseUrlWithoutToken */
    protected $baseUrlWithoutToken;
    public $migrations = [
        \Gett\MyParcel\Database\CreateProductConfigurationTableMigration::class,
    ];
    public $hooks = [
        'displayAdminProductsExtra',
        'actionProductUpdate'
    ];

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
                array(
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                )
            );
            $this->baseUrl = $this->getAdminLink(
                'AdminModules',
                true,
                array(
                    'configure' => $this->name,
                    'tab_module' => $this->tab,
                    'module_name' => $this->name,
                )
            );
        }
        $this->displayName = $this->l('MyParcel');
        $this->description = $this->l('PrestaShop module to intergratie with MyParcel NL and MyParcel BE');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Get admin link (PS 1.5/1.6 + 1.7 hybrid)
     *
     * @param string $controller
     * @param bool $withToken
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopException
     *
     * @since 2.3.0
     */
    public function getAdminLink($controller, $withToken = true, $params = array())
    {
        $url = $this->mypa_parse_url($this->context->link->getAdminLink($controller, $withToken));
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $query);
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            $url['query'] = http_build_query($query + $params, PHP_QUERY_RFC1738);
        } else {
            $url['query'] = http_build_query($query + $params);
        }


        return $this->mypa_stringify_url($url);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->context->smarty->assign(array(
            'menutabs' => $this->initNavigation(),
            'ajaxUrl' => $this->baseUrlWithoutToken,
        ));

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->display(__FILE__, 'views/templates/admin/navbar.tpl');

//        switch (Tools::getValue('menu')) {
//            case static::MENU_API_SETTINGS:
//                $this->menu = static::MENU_API_SETTINGS;
//                break;
//            case static::MENU_GENERAL_SETTINGS:
//                $this->menu = static::MENU_GENERAL_SETTINGS;
//                break;
//            case static::MENU_LABEL_SETTINGS:
//                $this->menu = static::MENU_LABEL_SETTINGS;
//                break;
//            case static::MENU_ORDER_SETTINGS:
//                $this->menu = static::MENU_ORDER_SETTINGS;
//                break;
//            case static::MENU_CUSTOMS_SETTINGS:
//                $this->menu = static::MENU_CUSTOMS_SETTINGS;
//                break;
//        }

        return $output . (new Configure($this))(Tools::getValue('menu'));
    }

    protected function initNavigation()
    {
        $menu = array(
            'main' => array(
                'short' => $this->l('API'),
                'desc' => $this->l('API settings'),
                'href' => static::appendQueryToUrl($this->baseUrl,
                    array('menu' => (string)Constant::MENU_API_SETTINGS)),
                'active' => false,
                'icon' => 'icon-gears',
            ),
            'defaultsettings' => array(
                'short' => $this->l('General settings'),
                'desc' => $this->l('General module settings'),
                'href' => static::appendQueryToUrl($this->baseUrl,
                    array('menu' => (string)Constant::MENU_GENERAL_SETTINGS)),
                'active' => false,
                'icon' => 'icon-truck',
            ),
            'labeloptions' => array(
                'short' => $this->l('Label options'),
                'desc' => $this->l('Label options'),
                'href' => static::appendQueryToUrl($this->baseUrl,
                    array('menu' => (string)Constant::MENU_LABEL_SETTINGS)),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ),
            'orderoptions' => array(
                'short' => $this->l('Order options'),
                'desc' => $this->l('Order options'),
                'href' => static::appendQueryToUrl($this->baseUrl,
                    array('menu' => (string)Constant::MENU_ORDER_SETTINGS)),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ),
            'customsoptions' => array(
                'short' => $this->l('Customs options'),
                'desc' => $this->l('Customs options'),
                'href' => static::appendQueryToUrl($this->baseUrl,
                    array('menu' => (string)Constant::MENU_CUSTOMS_SETTINGS)),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ),
        );

        switch (Tools::getValue('menu')) {
            case Constant::MENU_API_SETTINGS:
                $this->menu = Constant::MENU_API_SETTINGS;
                $menu['main']['active'] = true;
                break;
            case Constant::MENU_GENERAL_SETTINGS:
                $this->menu = Constant::MENU_GENERAL_SETTINGS;
                $menu['defaultsettings']['active'] = true;
                break;
            case Constant::MENU_LABEL_SETTINGS:
                $this->menu = Constant::MENU_LABEL_SETTINGS;
                $menu['labeloptions']['active'] = true;
                break;
            case Constant::MENU_ORDER_SETTINGS:
                $this->menu = Constant::MENU_ORDER_SETTINGS;
                $menu['orderoptions']['active'] = true;
                break;
            case Constant::MENU_CUSTOMS_SETTINGS:
                $this->menu = Constant::MENU_CUSTOMS_SETTINGS;
                $menu['customsoptions']['active'] = true;
                break;
            default:
                $this->menu = Constant::MENU_API_SETTINGS;
                $menu['main']['active'] = true;
                break;
        }

        return $menu;
    }

    public function appendQueryToUrl($urlString, $query = array())
    {
        $url = $this->mypa_parse_url($urlString);
        $url['query'] = isset($url['query']) ? $url['query'] : '';
        parse_str($url['query'], $oldQuery);
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            $url['query'] = http_build_query($oldQuery + $query, PHP_QUERY_RFC1738);
        } else {
            $url['query'] = http_build_query($oldQuery + $query);
        }


        return $this->mypa_stringify_url($url);
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        return $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return true;
    }

    function mypa_stringify_url($parsedUrl)
    {
        $scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
        $host = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
        $user = isset($parsedUrl['user']) ? $parsedUrl['user'] : '';
        $pass = isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';
        $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
        $fragment = isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '';

        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    function mypa_parse_url($urlString, $component = -1)
    {
        return call_user_func_array('parse_url', func_get_args());
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
}
