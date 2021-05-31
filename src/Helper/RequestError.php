<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Helper;

/**
 * @since v2.0.0
 */
class RequestError
{
    /**
     * @param        $error
     * @param  array $result
     *
     * @return string
     */
    public static function getTotalMessage($error, array $result): string
    {
        return implode(
            ' - ',
            [
                self::getErrorCode($error),
                self::getErrorHumanMessage($error),
                self::getErrorMessage($error, $result),
            ]
        );
    }

    /**
     * @param $error
     *
     * @return int
     */
    private static function getErrorCode($error): int
    {
        $code = '';

        if (array_key_exists('code', $error)) {
            $code = $error['code'];
        } elseif (array_key_exists('fields', $error)) {
            $code = $error['fields'][0];
        }

        return (int) $code;
    }

    /**
     * @param $error
     *
     * @return string
     */
    private static function getErrorHumanMessage($error): string
    {
        return array_key_exists('human', $error) ? $error['human'][0] : '';
    }

    /**
     * @param        $error
     * @param  array $result
     *
     * @return string
     */
    private static function getErrorMessage($error, array $result): string
    {
        if (array_key_exists('message', $result)) {
            $message = $result['message'];
        } elseif (array_key_exists('message', $error)) {
            $message = $error['message'];
        } else {
            $message = 'Unknown error: ' . json_encode($error) . '. Please contact MyParcel.';
        }

        return $message;
    }
}
