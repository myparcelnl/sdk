<?php

namespace Gett\MyParcel\Logger;

use Gett\MyParcel\Constant;

class Logger
{
    public static function log($message, bool $is_exception = false)
    {
        if ($is_exception || \Configuration::get(Constant::MY_PARCEL_API_LOGGING_CONFIGURATION_NAME)) {
            \PrestaShopLogger::addLog('MYPARCEL_' . $message);
        }
    }
}
