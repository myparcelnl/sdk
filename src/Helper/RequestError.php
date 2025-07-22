<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Helper;

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
            array_filter([
                self::getHttpStatusCode($error),
                self::getErrorCode($error),
                self::getErrorHumanMessage($error),
                self::getErrorMessage($error, $result),
            ])
        );
    }

    /**
     * @param array $error
     *
     * @return int
     */
    private static function getHttpStatusCode(array $error): string
    {
        return array_key_exists('status', $error) ? 'HTTP status ' . $error['status'] : null;
    }

    /**
     * @param $error
     *
     * @return int
     */
    private static function getErrorCode(array $error): ?int
    {
        if (array_key_exists('code', $error)) {
            return (int) $error['code'];
        } elseif (array_key_exists('fields', $error)) {
            return (int) $error['fields'][0];
        }

        return null;
    }

    /**
     * @param $error
     *
     * @return string
     */
    private static function getErrorHumanMessage(array $error): ?string
    {
        return array_key_exists('human', $error) ? $error['human'][0] : null;
    }

    /**
     * @param        $error
     * @param  array $result
     *
     * @return string
     */
    private static function getErrorMessage(array $error, array $result): string
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
