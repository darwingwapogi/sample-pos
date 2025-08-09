<?php
// Author: DarCasanova
// Date: 04/03/2025
// Time: 8:35pm

namespace App\Common\Utility;

class ArrayUtil {

    /**
     * Get the value of the specified key in the array.
     * This method checks whether the key exists before getting the value in the array,
     * avoiding the PHP warning raised when you try to access the value of a non-existing key.
     *
     * @param array $array
     * @param string $key
     * @param string|null $defaultValue
     * @return string|array|null
     */
    public static function valueAt(array $array, string $key, string $defaultValue = NULL): string|array|null
    {
        if (array_key_exists($key, (array)$array)) {
            return $array[$key];
        }

        return $defaultValue;
    }

    public static function valueAtPath($array, $keyPath, $defaultValue = NULL) {
        if ($array == null || $keyPath == null) {
            return $defaultValue;
        }

        $keyPath = mb_split('\.', $keyPath);

        $obj = $array;
        foreach ($keyPath as $currentKey) {
            if (is_null($obj)) {
                return $defaultValue;
            }

            if (array_key_exists($currentKey, $obj)) {
                $obj = $obj[$currentKey];
            }
            else {
                return $defaultValue;
            }
        }

        return $obj;
    }

    public static function findMapInArray($array, $keyInMap, $valueOfKey, $defaultValue = NULL) {
        $index = ArrayUtil::findMapIndexInArray($array, $keyInMap, $valueOfKey);

        return $index == -1 ? $defaultValue : $array[$index];
    }

    public static function findMapIndexInArray($array, $keyInMap, $valueOfKey) {
        $index = 0;
        foreach ($array as $map) {
            if (array_key_exists($keyInMap, $map) && $map[$keyInMap] == $valueOfKey) {
                return $index;
            }

            $index++;
        }

        return -1;
    }

    /**
     * Return an array containing the values for the specified $keyPath in the array elements of $arrayOfArray.
     *
     * @param array $arrayOfArray
     * @param unknown $keyPath
     * @param string $defaultValue
     * @return array
     */
    public static function getValuesAt($arrayOfArray, $keyPath, $defaultValue = NULL) {
        if ($arrayOfArray == null || $keyPath == null) {
            return $defaultValue;
        }

        $values = [];
        foreach ($arrayOfArray as $targetArray) {
            $values[] = ArrayUtil::valueAtPath($targetArray, $keyPath, $defaultValue);
        }

        return $values;
    }

    public static function setKeyValuesTo(&$array, $keys, $value) {
        if ($array == null || $keys == null) {
            return;
        }


        foreach ($keys as $key) {
            $array[$key] = $value;
        }

        return $array;
    }

    public static function getArrayFromArrayExceptKeys($sourceArray, $exceptKeys) {
        $keys = array_keys($sourceArray);
        $record = [];
        foreach ($keys as $key) {
            if (in_array($key, $exceptKeys)) {
                continue;
            }

            $record[$key] = $sourceArray[$key];
        }

        return $record;
    }

    /**
     * Create an array of the specified object.
     *
     * @param unknown_type $obj
     * @param unknown_type $count
     * @return an array containing '$count' number of '$obj'
     */
    public static function arrayOf($obj, $count = 1) {
        $array = array();
        for ($i = 0; $i < $count; $i++) {
            $array[] = $obj;
        }
        return $array;
    }

    public static function uniqueMultiDimArray($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
//                $temp_array[$i] = $val;
                array_push($temp_array, $val);
            }
            $i++;
        }

        return $temp_array;
    }

    public static function arrayContainsText($array, $text) {
        $filteredArray = array_filter($array, function ($string) use ($text) {
            return str_contains(strtolower($string) , $text);
        });

        return !empty($filteredArray);
    }

    public static function getAllowedUploadFileExtensionAndMimeTypes($extension, $all = false)
    {
        $list = [
            'jpeg' => ['image/jpeg'],
            'jpg' => ['image/jpeg'],
            'png' => ['image/png'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/CDFV2-encrypted'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/CDFV2-corrupt', 'application/encrypted', 'application/CDFV2-encrypted'],
            'csv' => ['application/vnd.ms-excel', 'text/csv', 'text/plain'],
            'pdf' => ['application/pdf'],
            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
        ];

        if ($all === true) {
            return $list;
        }

        return array_key_exists($extension, $list) ? $list[$extension] : [];
    }

    public static function check_diff_multi($array1, $array2){
        foreach($array1 as $key => $val) {
            foreach ($array2 as $val2) {
                if ($val['type'] == $val2['type'] && $val['amount'] == $val2['amount']) {
                    unset($array1[$key]);
                }
            }
        }

        return $array1;
    }

    public static function sanitizeArray($array, $text) {
        $tempArray = [];
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] != $text)
                $tempArray[] = $array[$i];
        }

        return $tempArray;
    }
}
?>
