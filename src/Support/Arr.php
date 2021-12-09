<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Support;

class Arr extends \Illuminate\Support\Arr
{
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does. I.e., with array_merge_recursive,
     * this happens (documented behavior):
     *
     * array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('org value', 'new value'));
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge, i.e.:
     *
     * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
     *     => array('key' => array('new value'));
     *
     * Parameters are passed by reference, though only for performance reasons. They're not
     * altered by this function.
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     * @link https://www.php.net/manual/en/function.array-merge-recursive.php#92195
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * @param mixed $array1
     * @param mixed $array2
     *
     * @return array
     */
    public static function mergeAfterEachOther($array1, $array2): array
    {
        $result = [];
        $array1 = array_values($array1);
        $array2 = array_values($array2);

        foreach ($array1 as $index => $value1) {
            $result[] = $value1;
            $result[] = $array2[$index];
        }

        return $result;
    }

    /**
     * @param array|object $object
     *
     * @return array
     */
    public static function fromObject($object): array
    {
        $array = (array) $object;
        foreach ($array as &$var) {
            if (is_object($var)) {
                $var = self::fromObject($var);
            }
        }

        return $array;
    }
}
