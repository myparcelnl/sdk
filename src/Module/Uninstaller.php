<?php

namespace Gett\MyparcelBE\Module;

use Tab;
use Configuration;
use DbQuery;
use Db;
use Carrier;
use Gett\MyparcelBE\Constant;

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

        $tabs = ['AdminMyParcelBE', 'AdminMyParcelBELabel'];

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
            Constant::POSTNL_CONFIGURATION_NAME,
            Constant::BPOST_CONFIGURATION_NAME,
            Constant::DPD_CONFIGURATION_NAME,
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
                $carrier->deleted = 1;
                $result &= $carrier->update();
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
