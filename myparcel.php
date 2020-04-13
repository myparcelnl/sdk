<?php

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

    public $baseUrl;
    public $id_carrier;
    public $migrations = [
        \Gett\MyParcel\Database\CreateProductConfigurationTableMigration::class,
    ];
    public $hooks = [
        'displayAdminProductsExtra',
        'actionProductUpdate',
        'displayCarrierExtraContent',
        'displayHeader',
        'actionCarrierProcess',
        'actionOrderGridDefinitionModifier',
        'actionAdminOrderIndexAfter',
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

    public function hookActionAdminOrderIndexAfter(array $params)
    {
        var_dump($params);
        die();
    }

    public function hookActionOrderGridDefinitionModifier(array $params)
    {
        /** @var \PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $definition->getBulkActions()->add(
            (new \Gett\MyParcel\Grid\Action\Bulk\CreateLabelBulkAction('create_label'))
                ->setName('Create label')
                ->setOptions([
                    'submit_route' => 'admin_myparcel_orders_label_bulk_create',
                ])
        );

        $definition
            ->getColumns()
            ->addAfter(
                'osname',
                (new \Gett\MyParcel\Grid\Column\BarcodeTypeColumn('barcode'))
                    ->setName($this->l('Barcode'))
                    ->setOptions([
                        'barcode' => 'Barcode Example',
                    ])
            )
        ;
    }

    public function hookActionCarrierProcess()
    {
        //var_dump($_POST);die();
    }

    public function hookDisplayHeader()
    {
        //todo add check if it's checkout page
        $this->context->controller->addCss($this->_path . 'views/sandbox/sandbox.css'); //will be removed after frontend implemented
        $this->context->controller->addCss($this->_path . 'views/css/myparcel.css');

        return "<script type='text/javascript' src='modules/" . $this->name . "/views/js/myparcel.js'></script>";
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