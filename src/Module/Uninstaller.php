<?php

namespace Gett\MyParcel\Module;

use Tab;
use Configuration;
use DbQuery;
use Db;
use Carrier;
use Gett\MyParcel\Constant;

class Uninstaller
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
            && $this->uninstallTabs()
            && $this->removeCarriers()
            && $this->removeConfigurations();
    }

    private function hooks(): bool
    {
        $result = true;
        foreach ($this->module->hooks as $hook) {
            $result &= $this->module->unregisterHook($hook);
        }

        return $result;
    }

    private function migrate(): bool
    {
        $result = true;
        foreach ($this->module->migrations as $migration) {
            $result &= $migration::down();
        }

        return $result;
    }

    private function uninstallTabs()
    {
        $res = true;

        $tabs = ['MyParcelLabelController'];

        foreach ($tabs as $tabName) {
            $id_tab = (int) Tab::getIdFromClassName($tabName);
            if ($id_tab) {
                $tab = new Tab($id_tab);
                $res &= $tab->delete();
            }
        }

        return $res;
    }

    private function removeCarriers()
    {
        $result = true;
        $carrierListConfig = [
            Constant::MY_PARCEL_POSTNL_CONFIGURATION_NAME,
            Constant::MY_PARCEL_BPOST_CONFIGURATION_NAME,
            Constant::MY_PARCEL_DPD_CONFIGURATION_NAME,
        ];
        foreach ($carrierListConfig as $item) {
            $idReference = Configuration::get($item);
            $query = new DbQuery();
            $query->select('id_carrier');
            $query->from('carrier');
            $query->where('id_reference = ' . (int) $idReference);
            $idCarrier = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
            if ($idCarrier) {
                $carrier = new Carrier($idCarrier);
                $result &= $carrier->delete();
            }
        }

        return $result;
    }

    private function removeConfigurations(): bool
    {
        foreach ($this->module->configItems as $configItem) {
            Configuration::deleteByName($configItem);
        }

        return true;
    }
}
