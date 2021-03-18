<?php

function upgrade_module_1_0_1($module)
{
    return $module->registerHook('actionValidateOrder');
}
