<?php

class Tstring {
    /**
     * 产生随机数
     * @param int $length 产生随机数长度
     * @param int $type 返回字符串类型
     * @param string $hash  是否由前缀，默认为空. 如:$hash = 'zz-'  结果zz-823klis
     * @return string 随机字符串 $type = 0：数字+字母
     * $type = 1：数字
     * $type = 2：字符
     */
    public static function random($length, $type = 0, $hash = '') {
        switch ($type) {
            case 1:
                $chars = '0123456789';
                break;
            case 2:
                $chars = 'abcdefghijklmnopqrstuvwxyz';
                break;
            default:
                $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        }
        $max = strlen($chars) - 1;
        mt_srand((double)microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 判断字符串是否存在
     * @param $haystack 被查找的字符串
     * @param $needle 需要查找的字符串
     * @return true or false
     */
    public static function strexists($haystack, $needle) {
        return !(strpos($haystack, $needle) === FALSE);
    }

    /**
     * 截取字符函数
     * @param $string 要截取的字符串
     * @param $len 截取长度
     * @param $code 字符编码
     * @param $prefix 新截取字符的前缀
     * @param $add 处理后字符串加的后缀,如'...'
     */
    public static function cutstr($string, $len, $code = 'utf-8', $prefix = '', $add = '') {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                if (mb_strlen($val, $code) > $len) {
                    $key = $prefix . $key;
                    $string[$key] = mb_substr($val, 0, $len, $code);
                    $string[$key] .= $add;
                } else {
                    $key = $prefix . $key;
                    $string[$key] = $val;
                }
            }
        } else {
            if (mb_strlen($string, $code) > $len) {
                $string = mb_substr($string, 0, $len, $code);
                $string .= $add;
            }
        }
        return $string;
    }

    /*
     * 去掉UBB标签,返回指定长度字符
     */
    public function getUbbStr($string, $strlen) {
        //过滤UBB
        $string = str_replace("\n", "", $string);
        $string = str_replace("\r", "", $string);
        $string = preg_replace("/\[.*\](.*)\[.*\]/i", "$1", $string);
        return lib_BaseUtils::cutstr($string, $strlen) . '...';
    }

}
