<?php
/**
 * 应用管理
 * @author luoqingbo
 * @version 2016-07-08
 */

class Tbd {

    protected static $api_key = '9df0132c19d20fc08140d4d8285481e1';
    protected static $base_url = 'http://apis.baidu.com/';

    //md5解码
    public static function md5Decode($code) {
        $code = trim($code);
        if (! $code || ! is_string($code)) {
            return '';
        }
        $url = self::$base_url . '/chazhao/md5decod/md5decod?md5=' . $code;

        $ret = Tnet::curl($url, '', 'GET', self::getHeader());

        $ret = json_decode($ret, true);
        if ($ret['error'] == 0) {
            return $ret['data']['md5_src'];
        }
        return '';
    }

    //汉字转拼音
    public static function topy($word)
    {
        if (! $word) {
            return '';
        }
        if (is_array($word)) {
            $word = implode($word, '::;');
        }

        $url = self::$base_url . '/sillystudio/service/topy?words=' . $word;
        $ret = Tnet::curl($url, '', 'GET', self::getHeader());

        $ret = json_decode($ret, true);
        if ($ret['code'] == 200) {
            $ret['py'] = explode('::;', $ret['py']);
            if (count($ret['py']) > 1) {
                return $ret['py'];
            } else {
                return $ret['py'][0];
            }
        }
        return '';
    }

    //生成短链接
    //汉字转拼音
    public static function shortUlr($url)
    {
        if (! $url) {
            return '';
        }
        $param = '';
        if (is_array($url)) {
            $url = array_map(function($a){return urlencode($a);}, $url);
            $param = implode($url, '&url_long=');
        } else {
            $param = urlencode($url);
        }

        $url = self::$base_url . '/3023/shorturl/shorten?url_long=' . $param;
        $ret = Tnet::curl($url, '', 'GET', self::getHeader());

        $ret = json_decode($ret, true);
        if ($ret['urls']) {
            if (count($ret['urls']) > 1) {
                return $ret['urls'];
            } else {
                return $ret['urls'][0]['url_short'];
            }
        }
        return '';
    }

    //公用的header
    protected static function getHeader() {
        return array(
            'apikey:' . self::$api_key,
        );
    }


}
