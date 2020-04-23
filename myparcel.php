<?php

use Gett\MyParcel\Module\Hooks\OrdersGridHooks;
use Gett\MyParcel\Module\Configuration\Configure;
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
    use OrdersGridHooks;

    public $baseUrl;
    public $id_carrier;
    public $migrations = [
        \Gett\MyParcel\Database\CreateProductConfigurationTableMigration::class,
        \Gett\MyParcel\Database\CreateCarrierConfigurationTableMigration::class,
        \Gett\MyParcel\Database\CreateOrderLabelTableMigration::class,
    ];

    public $hooks = [
        'displayAdminProductsExtra',
        'actionProductUpdate',
        'displayCarrierExtraContent',
        'displayHeader',
        'actionCarrierProcess',
        'actionOrderGridDefinitionModifier',
        'actionAdminControllerSetMedia',
        'actionOrderGridQueryBuilderModifier',
        'actionAdminOrdersListingFieldsModifier',
        'displayAdminListBefore',
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

    public function hookDisplayAdminListBefore()
    {
        if ($this->context->controller instanceof \AdminOrdersController) {
            \Media::addJsDef([
                'FEDEXBATCHEXPORT_LINK' => (new \Link())->getAdminLink('AdminFedexBatchExport'),
                'FEDEXBATCHEXPORT_LANG' => [
                    'FedEx batch export' => $this->trans('FedEx batch export', [], 'Modules.Fedexbatchexport.Back_office_hooks.php'),
                ],
            ]);
            $this->context->controller->addJS(
                $this->_path . 'resources/js/admin/order.js'
            );

            $link = new Link();
            $this->context->smarty->assign([
                'action' => $link->getAdminLink('AdminLabel', true, ['action' => 'createLabel']),
                'download_action' => $link->getAdminLink('AdminLabel', true, ['action' => 'downloadLabel']),
            ]);

            return $this->display(__FILE__, 'views/templates/admin/hook/orders_popups.tpl');
        }
    }

    public function hookActionAdminOrdersListingFieldsModifier($params)
    {
        $params['select'] .= ',1 as `myparcel_void_1` ,1 as `myparcel_void_2`';
//        $params['fields']['myparcel_field'] = [
//            'title' => "Myparcel",
//            'class' => 'fixed-width-lg',
//            'callback' => 'printMyParcelTrackTrace',
//            'remove_onclick' => true,
//            'filter_key' => '1'
//        ];

        $params['fields']['myparcel_void_1'] = [
            'title' => 'Labels',
            'class' => 'text-nowrap',
            'callback' => 'printMyParcelLabel',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => Module::getInstanceByName($this->name),
        ];

        $params['fields']['myparcel_void_2'] = [
            'title' => 'TTTTT',
            'class' => 'text-nowrap',
            'callback' => 'printMyParcelIcon',
            'search' => false,
            'orderby' => false,
            'remove_onclick' => true,
            'callback_object' => Module::getInstanceByName($this->name),
        ];
    }

    public function printMyParcelLabel($id, $params)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('myparcel_order_label');
        $sql->where('id_order = "' . pSQL($params['id_order']) . '" ');
        $result = Db::getInstance()->executeS($sql);
        $link = new Link();
        $this->context->smarty->assign([
            'labels' => $result,
            'link' => $link,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/icon-labels.tpl');
    }

    public function printMyParcelIcon($id, $params)
    {
        return $this->display(__FILE__, 'views/templates/admin/icon-concept.tpl');
    }

    public function hookActionAdminControllerSetMedia()
    {
        $link = new Link();
//        var_dump($this->context->link->getModuleLink($this->name,'checkout'));die();
        Media::addJsDef(
            [
                'default_label_size' => \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_SIZE_CONFIGURATION_NAME) == false ? 'a4' : \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_SIZE_CONFIGURATION_NAME),
                'default_label_position' => \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_POSITION_CONFIGURATION_NAME) == false ? '1' : \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_POSITION_CONFIGURATION_NAME),
                'prompt_for_label_position' => \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_PROMPT_POSITION_CONFIGURATION_NAME) == false ? '0' : \Configuration::get(\Gett\MyParcel\Constant::MY_PARCEL_LABEL_PROMPT_POSITION_CONFIGURATION_NAME),
                'create_labels_bulk_route' => $link->getAdminLink('AdminLabel', true, ['action' => 'createLabelsBulk']),
            ]
        );

        $this->context->controller->addJS(
            $this->_path . 'views/js/admin/order.js'
        );
    }

    public function hookActionCarrierProcess()
    {
//        var_dump($_POST);die();
    }

    public function hookDisplayHeader()
    {
        //todo add check if it's checkout page
        $this->context->controller->addCss($this->_path . 'views/sandbox/sandbox.css'); //will be removed after frontend implemented
        $this->context->controller->addCss($this->_path . 'views/css/myparcel.css');
        $this->context->controller->addJs($this->_path . 'views/js/myparcelinit.js');

        return "<script type='text/javascript' src='modules/" . $this->name . "/node_modules/@myparcel/delivery-options/dist/myparcel.js'></script>";
    }

    public function hookDisplayCarrierExtraContent()
    {
        $address = new \Address($this->context->cart->id_address_delivery);
        if (\Validate::isLoadedObject($address)) {
            $address->address1 = preg_replace('/[^0-9]/', '', $address->address1);

            $this->context->smarty->assign([
                'address' => $address,
            ]);

            //TODO pass carrier configuraiton params
            return $this->display(__FILE__, 'carrier.tpl');
        }
    }

    public function getAdminLink(string $controller, bool $withToken = true, array $params = [])
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

    private function mypa_parse_url($urlString, $component = -1)
    {
        return call_user_func_array('parse_url', func_get_args());
    }
}
