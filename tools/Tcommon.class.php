<?php

class Tcommon {

    /**
     * 获取当前在线IP地址
     * @param $format 0 返回IP地址：127.0.0.1,1 返回IP长整形：2130706433
     * @return string|int
     */
    public static function getIp($format = 0) {
        global $_SGLOBAL;
        if (empty($_SGLOBAL['onlineip'])) {
            if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
                $onlineip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
                $onlineip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
                $onlineip = getenv('REMOTE_ADDR');
            } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
                $onlineip = $_SERVER['REMOTE_ADDR'];
            } else {
                return false;
            }
            preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
            $_SGLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
        }
        if (!$format) {
            return $_SGLOBAL['onlineip'];
        } else {
            return sprintf('%u', ip2long($_SGLOBAL['onlineip']));
        }
    }

    /**
     * 查找指定ip的地区
     * @param string $ip
     * @return array
     */
    public static function getLocationByIP($ip = '') {
        if (!$ip) {
            $ip = self::getIp();
        }
        include_once "ip.php";
        $oip = new iplocation(dirname(__FILE__) . "/qqwry.dat");
        $separator = $oip->separate(1000); //分成1000块
        $location = $oip->getlocation($ip, $separator);
        if ($location) {
            foreach ($location as $key => $value) {
                if (is_string($value)) {
                    $location[$key] = iconv('gb2312', 'UTF-8', $value);
                }
            }
        }
        return $location;
    }

    /**
     * 格式化大小函数
     * @param int $size 为文件大小
     * @return string 文件大家加单位
     */
    public static function formatsize($size) {
        $prec = 3;
        $size = round(abs($size));
        $units = array(
            0 => " B ",
            1 => " KB",
            2 => " MB",
            3 => " GB",
            4 => " TB"
        );
        if ($size == 0) return str_repeat(" ", $prec) . "0$units[0]";
        $unit = min(4, floor(log($size) / log(2) / 10));
        $size = $size * pow(2, -10 * $unit);
        $digi = $prec - 1 - floor(log($size) / log(10));
        $size = round($size * pow(10, $digi)) * pow(10, -$digi);
        return $size . $units[$unit];
    }

    /**
     * cookie设置
     * @param string $name 设置的cookie名
     * @param mixed $value 设置的cookie值
     * @param int $life 设置的过期时间(秒)
     * @param string $path 设置的cookie作用路径
     * @param string $domain 设置的cookie作用域名
     */
    public static function setCookie($name, $value = '', $life = 0, $path = '/', $domain = '') {
        $_COOKIE[$name] = $value;
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                setcookie($name . "[$k]", $v, $life, $path, $domain, 0);
            }
        } else {
            setcookie($name, $value, time() + $life, $path, $domain, 0);
        }
    }

    /**
     * 验证验证码
     */
    public function checkCatcha($seed, $catcha) {
        //session_start();
        //$oSC = new SCaptcha();
        //return $oSC->check($s);
        $c = new SCaptchalu();
        return $c->verify($seed, $catcha);
    }
}

