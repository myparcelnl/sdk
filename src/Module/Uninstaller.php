<?php

namespace Gett\MyParcel\Module;

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
        return true;
//        return $this->hooks()
//            && $this->migrate();
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
}
