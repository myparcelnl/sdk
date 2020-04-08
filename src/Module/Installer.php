<?php

namespace Gett\MyParcel\Module;

use Tab;
use Carrier;
use Validate;
use Configuration;
use Gett\MyParcel\Constant;

class Installer
{
    /** @var \Module */
    private $module;

    public function __construct(\Module $module)
    {
        $this->module = $module;
    }

    public function __invoke(): bool
    {
        return $this->hooks()
            && $this->migrate()
            && $this->installTabs();
        //&& $this->addCarrier('PostNL', Constant::POSTNL_DEFAULT_CARRIER);;
    }

    public function installTabs()
    {
        $res = $this->uninstallTabs();

        if (!$res) {
            return false;
        }

        foreach ($this->getAdminTabsDefinition() as $key => $admin_tab) {
            $res &= $this->installTab($key);
        }

        return $res;
    }

    public function uninstallTabs($tabs = null)
    {
        $res = true;

        if ($tabs === null) {
            $tabs = $this->getAdminTabsDefinition();
        }

        foreach ($this->getAdminTabsDefinition() as $key => $admin_tab) {
            $res &= $this->uninstallTab($key);
        }

        return $res;
    }

    public function installTab($tabName)
    {
        $res = $this->uninstallTab($tabName);

        if (!$res) {
            return false;
        }

        $tabsDef = $this->getAdminTabsDefinition();

        if (!empty($tabsDef[$tabName])) {
            $admin_tab = $tabsDef[$tabName];
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $admin_tab['class_name'];
            $tab->name = $admin_tab['name'];
            $tab->id_parent = (!empty($admin_tab['parent_class'])
                ? (int) Tab::getIdFromClassName($admin_tab['parent_class'])
                : -1);
            $tab->module = $this->module->name;
            $res &= $tab->add();
        }

        return $res;
    }

    public function uninstallTab($tabName)
    {
        $res = true;

        $id_tab = (int) Tab::getIdFromClassName($tabName);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $res &= $tab->delete();
        }

        return $res;
    }

    public function getAdminTabsDefinition()
    {
        $languages = [];
        foreach (\Language::getLanguages(true) as $lang) {
            $languages[$lang['id_lang']] = 'test';
        }
        return [
            'MyParcelCarrier' => [
                'class_name' => 'MyParcelCarrier',
                'name' => $languages,
                'parent_class' => 'AdminCarriers',
            ],
        ];
    }

    protected function addCarrier($name, $key = Constant::POSTNL_DEFAULT_CARRIER)
    {
        $carrier = Carrier::getCarrierByReference(Configuration::get($key));
        if (Validate::isLoadedObject($carrier)) {
            return false; // Already added to DB
        }

        $carrier = new Carrier();

        $carrier->name = $name;
        $carrier->delay = [];
        $carrier->is_module = true;
        $carrier->active = 0;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 1;
        $carrier->external_module_name = $this->module->name;
        $carrier->shipping_handling = false;
        $carrier->shipping_method = 2;

        foreach (\Language::getLanguages() as $lang) {
            $idLang = (int) $lang['id_lang'];
            $carrier->delay[$idLang] = '-';
        }

        if ($carrier->add()) {
            /*
             * Use the Carrier ID as id_reference! Only the `id` prop has been set at this time and since it is
             * the first time this carrier is used the Carrier ID = `id_reference`
             */
            $this->addGroups($carrier);
            $this->addZones($carrier);
            \Db::getInstance()->update(
                'delivery',
                [
                    'price' => $key == Constant::POSTNL_DEFAULT_CARRIER ? (4.99 / 1.21) : (3.50 / 1.21),
                ],
                '`id_carrier` = ' . (int) $carrier->id
            );

            $carrier->setTaxRulesGroup((int) \TaxRulesGroup::getIdByName('NL Standard Rate (21%)'), true);

            @copy(
                dirname(__FILE__) . '/views/img/postnl-thumb.jpg',
                _PS_SHIP_IMG_DIR_ . DIRECTORY_SEPARATOR . (int) $carrier->id . '.jpg'
            );

            Configuration::updateGlobalValue($key, (int) $carrier->id);

            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = [];
        $groups = \Group::getGroups(\Context::getContext()->language->id);
        foreach ($groups as $group) {
            $groups_ids[] = $group['id_group'];
        }

        $carrier->setGroups($groups_ids);
    }

    protected function addZones($carrier)
    {
        $zones = \Zone::getZones();

        foreach ($zones as $zone) {
            $carrier->addZone($zone['id_zone']);
        }
    }

    protected function addRanges($carrier)
    {
        $range_price = new \RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new \RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
    }

    private function hooks(): bool
    {
        $result = true;
        foreach ($this->module->hooks as $hook) {
            $result &= $this->module->registerHook($hook);
        }

        return $result;
    }

    private function migrate(): bool
    {
        $result = true;
        foreach ($this->module->migrations as $migration) {
            $result &= $migration::up();
        }

        return $result;
    }
}
