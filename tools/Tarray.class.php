<?php

class Tarray {

    /**
     * 冒泡排序（数组排序）
     * @param $array 需要排序的数组
     * @return 排序后数组
     */
    public static function bubble_sort($array) {
        $count = count($array);
        if ($count <= 0) {
            return false;
        } else {
            for ($i = 0; $i < $count; $i++) {
                for ($j = $count - 1; $j > $i; $j--) {
                    if ($array[$j] < $array[$j - 1]) {
                        $temp = $array[$j];
                        $array[$j] = $array[$j - 1];
                        $array[$j - 1] = $temp;
                    }
                }
            }
            return $array;
        }
    }

    /**
     * 把数组中的所有数字变成字符串
     */
    public function int2str($arr) {
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                if (is_array($value)) {
                    $arr[$key] = self::array_int2str($value);
                } elseif (is_numeric($value)) {
                    $arr[$key] = (string)$value;
                }
            }
            return $arr;
        } else {
            return $arr;
        }
    }

    /**
     * 用数组中某个元素的值作为该数组的键
     * @param $arr array 要被改变二维数组
     * @param $key string 作为数组键的元素
     * @param $isremove boolean 是否删除作为标识的元素
     */
    public static function changeKey($arr, $key, $isremove = false) {
        $result = array();
        if ($arr) {
            foreach ($arr as $value) {
                $k = $value[$key];
                if ($isremove) {
                    unset($value[$key]);
                }
                $result[$k] = $value;
            }
        }
        return $result;
    }

    public static function toHash($arr, $key, $value) {
        $result = array();
        if ($arr) {
            foreach ($arr as $e) {
                $result[$e[$key]] = $e[$value];
            }
        }
        return $result;
    }

    public static function getSinge($arr, $field, $retrunType = "string") {
        if (!$arr) {
            if ($retrunType == "string") {
                return '';
            } else {
                return array();
            }
        }
        $ids = array();
        foreach ($arr as $key => $value) {
            if (!is_array($value)) {
                break;
            }
            if (array_key_exists($field, $value)) {
                $ids[$key] = $value[$field];
            }
        }
        if ($retrunType == "string") {
            return implode(',', $ids);
        } else {
            return $ids;
        }
    }

    public static function getSub($arr, $fields)
    {
        if (empty($arr) || empty($fields)) {
            return array();
        }
        $ret = array();
        if (! is_array($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields as $field) {
            $ret[$field] = $arr[$field]?:'';
        }

        return $ret;
    }

}
