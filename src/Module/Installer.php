<?php

namespace Gett\MyParcel\Module;

use Gett\MyParcel\Constant;
use Tab;
use Carrier;

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
        $result = true;
        $result &= $this->migrate();
        $result &= $this->hooks();
        $result &= $this->installTabs();

        if ($result) {
            $carrier = $this->addCarrier('Post NL');
            $this->addZones($carrier);
            $this->addGroups($carrier);
            $this->addRanges($carrier);
        }

        return $result;
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
            $languages[$lang['id_lang']] = 'MyParcel Carriers';
        }

        return [
            'MyParcelCarrier' => [
                'class_name' => 'MyParcelCarrier',
                'name' => $languages,
                'parent_class' => 'AdminParentShipping',
            ],
            'MyParcelLabelController' => [
                'class_name' => 'Label',
                'name' => $languages
            ],
        ];
    }

    protected function addCarrier($name)
    {
        $carrier = new Carrier();

        $carrier->name = $name;
        $carrier->is_module = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->module->name;
        $carrier->shipping_method = 2;

        foreach (\Language::getLanguages() as $lang) {
            $carrier->delay[$lang['id_lang']] = 'Super fast delivery';
        }

        if ($carrier->add() == true) {
            @copy(_PS_MODULE_DIR_  . 'myparcel/views/images/postnl.jpg', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.jpg');

            $insert = [];
            foreach (Constant::MY_PARCEL_CARRIER_CONFIGURATION_FIELDS as $item) {
                $insert[] = ['id_carrier' => $carrier->id, 'name' => $item];
            }
            \Db::getInstance()->insert('myparcel_carrier_configuration', $insert);
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

    protected function addZones($carrier)
    {
        $zones = \Zone::getZones();

        foreach ($zones as $zone) {
            $carrier->addZone($zone['id_zone']);
        }
    }

    private function hooks(): bool
    {
        $result = true;
        foreach ($this->module->hooks as $hook) {
            $result &= $this->module->registerHook($hook);
        }
        //$this->module->registerHook('actionOrderIndexAfter'); //IDK why but module reset is not working if this hook is in array of hooks
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
