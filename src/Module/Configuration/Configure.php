<?php

namespace Gett\MyparcelBE\Module\Configuration;

use Module;
use Gett\MyparcelBE\Constant;

class Configure
{
    /** @var Module */
    private $module;

    /** @var array */
    private $forms = [
        Constant::MENU_API_SETTINGS => ApiForm::class,
        Constant::MENU_GENERAL_SETTINGS => GeneralForm::class,
        Constant::MENU_LABEL_SETTINGS => LabelForm::class,
        Constant::MENU_ORDER_SETTINGS => OrderForm::class,
        Constant::MENU_CUSTOMS_SETTINGS => CustomsForm::class,
        5 => Carriers::class,
    ];

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function __invoke(int $id_form): string
    {
        return (new $this->forms[$id_form]($this->module))();
    }

    public function initNavigation()
    {
        $menu = [
            'main' => [
                'short' => $this->module->l('API', 'configure'),
                'desc' => $this->module->l('API settings', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => Constant::MENU_API_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-gears',
            ],
            'defaultsettings' => [
                'short' => $this->module->l('General settings', 'configure'),
                'desc' => $this->module->l('General module settings', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => (string) Constant::MENU_GENERAL_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-truck',
            ],
            'labeloptions' => [
                'short' => $this->module->l('Label options', 'configure'),
                'desc' => $this->module->l('Label options', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => (string) Constant::MENU_LABEL_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ],
            'orderoptions' => [
                'short' => $this->module->l('Order options', 'configure'),
                'desc' => $this->module->l('Order options', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => (string) Constant::MENU_ORDER_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ],
            'customsoptions' => [
                'short' => $this->module->l('Customs options', 'configure'),
                'desc' => $this->module->l('Customs options', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => (string) Constant::MENU_CUSTOMS_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ],
            'carriersoptions' => [
                'short' => $this->module->l('Carriers options', 'configure'),
                'desc' => $this->module->l('Carriers options', 'configure'),
                'href' => $this->module->appendQueryToUrl(
                    $this->module->baseUrl,
                    ['menu' => (string) Constant::MENU_CARRIER_SETTINGS]
                ),
                'active' => false,
                'icon' => 'icon-shopping-cart',
            ],
        ];

        switch (\Tools::getValue('menu')) {
            case Constant::MENU_GENERAL_SETTINGS:
                $menu['defaultsettings']['active'] = true;

                break;
            case Constant::MENU_LABEL_SETTINGS:
                $menu['labeloptions']['active'] = true;

                break;
            case Constant::MENU_ORDER_SETTINGS:
                $menu['orderoptions']['active'] = true;

                break;
            case Constant::MENU_CUSTOMS_SETTINGS:
                $menu['customsoptions']['active'] = true;

                break;
            case Constant::MENU_CARRIER_SETTINGS:
                $menu['carriersoptions']['active'] = true;

                break;
            case Constant::MENU_API_SETTINGS:
            default:
                $menu['main']['active'] = true;

                break;
        }

        return $menu;
    }
}
