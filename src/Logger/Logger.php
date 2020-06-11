<?php

namespace Gett\MyparcelBE\Logger;

use Gett\MyparcelBE\Constant;

class Logger
{
    public static function addLog(
        $message,
        bool $is_exception = false,
        $allowDuplicate = false,
        $severity = 1,
        $errorCode = null
    ) {
        if ($is_exception || \Configuration::get(Constant::API_LOGGING_CONFIGURATION_NAME)) {
            \PrestaShopLogger::addLog(
                '[MYPARCEL] ' . $message,
                $severity,
                $errorCode,
                null,
                null,
                $allowDuplicate
            );
        }
    }
}
