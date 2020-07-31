<?php declare(strict_types=1);
/**
 * If you want to add improvements, please create a fork in our GitHub:
 * https://github.com/myparcelnl
 *
 * @author      Reindert Vetter <reindert@myparcel.nl>
 * @copyright   2010-2020 MyParcel
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US  CC BY-NC-ND 3.0 NL
 * @link        https://github.com/myparcelnl/sdk
 * @since       File available since Release v2.0.0
 */

namespace MyParcelNL\Sdk\src\Helper;

class RequestError
{
    /**
     * @param $error
     * @param array $result
     *
     * @return string
     */
    public static function getTotalMessage($error, $result)
    {
        return
            self::getErrorCode($error) . ' - ' .
            self::getErrorHumanMessage($error) . ' - ' .
            self::getErrorMessage($error, $result);
    }

    /**
     * @param $error
     *
     * @return string
     */
    private static function getErrorCode($error)
    {
        $code = '';
        if (key_exists('code', $error)) {
            $code = $error['code'];
        } elseif (key_exists('fields', $error)) {
            $code = $error['fields'][0];
        }

        return $code;
    }

    /**
     * @param $error
     *
     * @return string
     */
    private static function getErrorHumanMessage($error)
    {
        $humanMessage = key_exists('human', $error) ? $error['human'][0] : '';

        return $humanMessage;
    }

    /**
     * @param $error
     * @param array $result
     *
     * @return string
     */
    private static function getErrorMessage($error, $result)
    {
        if (key_exists('message', $result)) {
            $message = $result['message'];
        } elseif (key_exists('message', $error)) {
            $message = $error['message'];
        } else {
            $message = 'Unknown error: ' . json_encode($error) . '. Please contact MyParcel.';
        }

        return $message;
    }
}
