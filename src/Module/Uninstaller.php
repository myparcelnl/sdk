<?php

namespace Gett\MyParcel\Module;

use Tab;

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
            && $this->uninstallTabs();
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
}
