<?php

class Tnet {

    public static function throw404() {
        $SGui = new SGui();
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        $tpl = ROOT_DIR . '/app/templates/store/404.tpl';
        echo $SGui->render($tpl, null, '%');
        exit;
    }

    /**
     * 通过域名获取根域名
     */
    public static function getDomainFromHost() {
        $hosts = explode('.', $_SERVER['HTTP_HOST']);
        return join('.', array_slice($hosts, -2));
    }

    /**
     * 取得阅读器名称和版本
     *
     * @return string
     */
    public static function getBrowser() {
        global $_SERVER;

        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = '';
        $browser_ver = '';
        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'OmniWeb';
            $browser_ver = $regs[2];
        }
        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Netscape';
            $browser_ver = $regs[2];
        }
        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Safari';
            $browser_ver = $regs[1];
        }
        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'Internet Explorer';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser = 'Opera';
            $browser_ver = $regs[1];
        }
        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = '(Internet Explorer ' . $browser_ver . ') NetCaptor';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser = '(Internet Explorer ' . $browser_ver . ') Maxthon';
            $browser_ver = '';
        }
        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'FireFox';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Lynx';
            $browser_ver = $regs[1];
        }
        if ($browser != '') {
            return $browser . ' ' . $browser_ver;
        } else {
            return 'Unknow browser';
        }
    }

    /**
     * 取得客户真个操作体系
     *
     * @return string
     */
    public static function getOS() {
        $agent = $_SERVER['HTTP_USER_AGENT'];

        if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
            $os = 'Windows ME';
        } else if (preg_match('/win/i', $agent) && preg_match('/98/', $agent)) {
            $os = 'Windows 98';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
            $os = 'Windows Vista';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
            $os = 'Windows 7';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
            $os = 'Windows 8';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.3/i', $agent)) {
            $os = 'Windows 8.1';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
            $os = 'Windows XP';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
            $os = 'Windows 2000';
        } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
            $os = 'Windows NT';
        } else if (preg_match('/win/i', $agent) && preg_match('/32/', $agent)) {
            $os = 'Windows 32';
        } else if (preg_match('/linux/i', $agent)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $agent)) {
            $os = 'Unix';
        } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'SunOS';
        } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
            $os = 'IBM OS/2';
        } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
            $os = 'Macintosh';
        } else if (preg_match('/PowerPC/i', $agent)) {
            $os = 'PowerPC';
        } else if (preg_match('/AIX/i', $agent)) {
            $os = 'AIX';
        } else if (preg_match('/HPUX/i', $agent)) {
            $os = 'HPUX';
        } else if (preg_match('/NetBSD/i', $agent)) {
            $os = 'NetBSD';
        } else if (preg_match('/BSD/i', $agent)) {
            $os = 'BSD';
        } else if (preg_match('/OSF1/', $agent)) {
            $os = 'OSF1';
        } else if (preg_match('/IRIX/', $agent)) {
            $os = 'IRIX';
        } else if (preg_match('/FreeBSD/i', $agent)) {
            $os = 'FreeBSD';
        } else if (preg_match('/teleport/i', $agent)) {
            $os = 'teleport';
        } else if (preg_match('/flashget/i', $agent)) {
            $os = 'flashget';
        } else if (preg_match('/webzip/i', $agent)) {
            $os = 'webzip';
        } else if (preg_match('/offline/i', $agent)) {
            $os = 'offline';
        } else {
            $os = 'Unknown';
        }
        return $os;
    }

    public static function asynRequest($url, $data = array(), $method = 'get') {
        $host = $_SERVER['HTTP_HOST'];
        $fp = fsockopen($host, 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            if ($method == 'get') {
                $url .= (strpos('?', $url) ? '' : '?') . http_build_query($data);
                $header = "GET $url HTTP/1.1\r\n";
                $header .= "Host: $host\r\n";
                $header .= "Connection: Close\r\n\r\n";
            } else {
//                $data_string = http_build_query($data);
                $data_string = '';
                if ($data) {
                    $values = array();
                    foreach ($data as $key => $value) {
                        $values[] = "$key=" . urlencode($value);
                    }
                    $data_string = implode("&", $values);
                }
                $header = "POST " . $url . " HTTP/1.1\n";
                $header .= "Host: " . $host . "\n";
                $header .= "Content-type: application/x-www-form-urlencoded\n";
                $header .= "User-Agent:$_SERVER[HTTP_USER_AGENT]\r\n";
                $header .= "Content-length: " . strlen($data_string) . "\n";
                $header .= "Connection: close\n";
                $header .= "\n";
                $header .= $data_string . "\n";
            }
            fwrite($fp, $header);
            stream_set_blocking($fp, true);
            stream_set_timeout($fp, 1);
//            while (!feof($fp)) {
//                echo fgets($fp, 128);
//            }
            fclose($fp);
        }
    }

    public static function isAjax() {
        if (isset ($_SERVER ['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER ['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { //是否ajax请求
            return true;
        } else {
            return false;
        }
    }

    public static function getLocationUrl() {
        if (array_key_exists('HTTP_X_REWRITE_URL', $_SERVER)) {
            return $_SERVER['HTTP_X_REWRITE_URL'];
        } else {
            return $_SERVER['REQUEST_URI'];
        }
    }

    public static function curl($url, $message, $method = 'POST', $header=NULL)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if(!is_null($header)){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $curl_get = curl_exec($curl);

        $curl = null;
        return $curl_get;
    }
}
