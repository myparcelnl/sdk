<?php

namespace Gett\MyParcel\Module;

use Gett\MyParcel\Constant;
use Carrier;
use Configuration;
use Validate;
use Tab;

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
            #&& $this->addCarrier('PostNL', Constant::POSTNL_DEFAULT_CARRIER);;
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

    protected function addCarrier($name, $key = Constant::POSTNL_DEFAULT_CARRIER)
    {
        $carrier = Carrier::getCarrierByReference(Configuration::get($key));
        if (Validate::isLoadedObject($carrier)) {
            return false; // Already added to DB
        }

        $carrier = new Carrier();

        $carrier->name = $name;
        $carrier->delay = array();
        $carrier->is_module = true;
        $carrier->active = 0;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 1;
        $carrier->external_module_name = $this->module->name;
        $carrier->shipping_handling = false;
        $carrier->shipping_method = 2;

        foreach (Language::getLanguages() as $lang) {
            $idLang = (int)$lang['id_lang'];
            $carrier->delay[$idLang] = '-';
        }

        if ($carrier->add()) {
            /*
             * Use the Carrier ID as id_reference! Only the `id` prop has been set at this time and since it is
             * the first time this carrier is used the Carrier ID = `id_reference`
             */
            $this->addGroups($carrier);
            $this->addZones($carrier);
            $this->addPriceRange($carrier);
            Db::getInstance()->update(
                'delivery',
                array(
                    'price' => $key == static::POSTNL_DEFAULT_CARRIER ? (4.99 / 1.21) : (3.50 / 1.21),
                ),
                '`id_carrier` = ' . (int)$carrier->id
            );

            $carrier->setTaxRulesGroup((int)TaxRulesGroup::getIdByName('NL Standard Rate (21%)'), true);

            @copy(
                dirname(__FILE__) . '/views/img/postnl-thumb.jpg',
                _PS_SHIP_IMG_DIR_ . DIRECTORY_SEPARATOR . (int)$carrier->id . '.jpg'
            );

            Configuration::updateGlobalValue($key, (int)$carrier->id);
            $deliverySetting = new MyParcelCarrierDeliverySetting();
            $deliverySetting->id_reference = $carrier->id;

            $deliverySetting->monday_cutoff = '15:30:00';
            $deliverySetting->tuesday_cutoff = '15:30:00';
            $deliverySetting->wednesday_cutoff = '15:30:00';
            $deliverySetting->thursday_cutoff = '15:30:00';
            $deliverySetting->friday_cutoff = '15:30:00';
            $deliverySetting->saturday_cutoff = '15:30:00';
            $deliverySetting->sunday_cutoff = '15:30:00';
            $deliverySetting->timeframe_days = 1;
            $deliverySetting->daytime = true;
            $deliverySetting->morning = false;
            $deliverySetting->morning_pickup = false;
            $deliverySetting->evening = false;
            $deliverySetting->signed = false;
            $deliverySetting->recipient_only = false;
            $deliverySetting->signed_recipient_only = false;
            $deliverySetting->dropoff_delay = 0;
            $deliverySetting->id_shop = $this->getShopId();
            $deliverySetting->morning_fee_tax_incl = 0;
            $deliverySetting->morning_pickup_fee_tax_incl = 0;
            $deliverySetting->default_fee_tax_incl = 0;
            $deliverySetting->evening_fee_tax_incl = 0;
            $deliverySetting->signed_fee_tax_incl = 0;
            $deliverySetting->recipient_only_fee_tax_incl = 0;
            $deliverySetting->signed_recipient_only_fee_tax_incl = 0;
            $deliverySetting->monday_enabled = false;
            $deliverySetting->tuesday_enabled = false;
            $deliverySetting->wednesday_enabled = false;
            $deliverySetting->thursday_enabled = false;
            $deliverySetting->friday_enabled = false;
            $deliverySetting->saturday_enabled = false;
            $deliverySetting->sunday_enabled = false;
            $deliverySetting->pickup = false;
            $deliverySetting->delivery = false;
            $deliverySetting->mailbox_package = false;
            $deliverySetting->digital_stamp = false;
            if ($key === static::POSTNL_DEFAULT_CARRIER) {
                $deliverySetting->monday_enabled = true;
                $deliverySetting->tuesday_enabled = true;
                $deliverySetting->wednesday_enabled = true;
                $deliverySetting->thursday_enabled = true;
                $deliverySetting->friday_enabled = true;
                $deliverySetting->saturday_enabled = false;
                $deliverySetting->sunday_enabled = false;
                $deliverySetting->delivery = true;
                $deliverySetting->pickup = true;
            } elseif ($key === static::POSTNL_DEFAULT_MAILBOX_PACKAGE_CARRIER) {
                $deliverySetting->mailbox_package = true;
            } else {
                $deliverySetting->digital_stamp = true;
            }
            try {
                $deliverySetting->add();
            } catch (PrestaShopException $e) {
                Logger::addLog(
                    sprintf(
                        "{$this->l('MyParcel: unable to save carrier settings for carrier with reference %d')}: {$e->getMessage()}",
                        $carrier->id
                    )
                );
            }

            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group) {
            $groups_ids[] = $group['id_group'];
        }

        $carrier->setGroups($groups_ids);
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone) {
            $carrier->addZone($zone['id_zone']);
        }
    }

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
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


    public function installTab($tab_name)
    {
        $res = $this->uninstallTab($tab_name);

        if (!$res) {
            return false;
        }

        $tabsDef = $this->getAdminTabsDefinition();

        if (!empty($tabsDef[$tab_name])) {
            $admin_tab = $tabsDef[$tab_name];
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = $admin_tab['class_name'];
            $tab->name = $admin_tab['name'];
            $tab->id_parent = (!empty($admin_tab['parent_class'])
                ? (int)Tab::getIdFromClassName($admin_tab['parent_class'])
                : -1);
            $tab->module = $this->module->name;
            $res &= $tab->add();
        }

        return $res;
    }

    public function uninstallTab($tab_name)
    {
        $res = true;

        $id_tab = (int)Tab::getIdFromClassName($tab_name);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $res &= $tab->delete();
        }

        return $res;
    }

    public function getAdminTabsDefinition()
    {
        return array(
            'MyParcelCarrier' => array(
                'class_name' => 'MyParcelCarrier',
                'name' => array(
                    1 => 'Carrier Controller'
                ),
                'parent_class' => 'AdminCarriers'
            ),
        );
    }


}
