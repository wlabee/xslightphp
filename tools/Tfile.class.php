<?php

class Tfile extends TBase {

    /**
     * 验证目录名是否有效 (只允许输入数字和字母)
     * @param string $dirname 目录名
     * @return boolean
     */
    public static function isDirName($dirname) {
        $patn = '/^[a-zA-Z]+[a-zA-Z0-9]+$/';
        return preg_match($patn, $dirname);
    }

    /**
     * 获取文件后缀名
     * @param string $filename 文件名
     * @return string 字符串
     */
    public static function getFileExt($filename) {
        $info = pathinfo($filename);
        return $info['extension'];
    }

    /**
     * 编码转换
     * @param string $str 需要转换的字符
     * @param string $out_charset 转换的编码格式
     * @param string $in_charset 默认的编码格式
     * @return string 字符串
     */
    public static function siconv($str, $out_charset, $in_charset = '') {
        global $_SC;

        $in_charset = empty($in_charset) ? strtoupper($_SC['charset']) : strtoupper($in_charset);
        $out_charset = strtoupper($out_charset);
        if ($in_charset != $out_charset) {
            if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
                return $outstr;
            } elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
                return $outstr;
            }
        }
        return $str; // 转换失败
    }

    public static function isImg($file) {
        $ext = array(
            'gif',
            'jpg',
            'jpeg',
            'png',
            'bmp',
        );
        if (in_array(self::getFileExt($file), $ext)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 上传
     * 配合smarty插件file显示
     * @param array $files 上传的文件
     * @param string $dir 上传到哪个目录
     * @param bool $isImg 是否是图片（默认是）
     * @param int $maxSize 文件大小（默认500KB）
     * @return array('oldname'=>'上传时的文件名','url'=>'文件地址')
     */
    public static function upload($files, $dir = null, $isImg = true, $maxSize = 5000000) {
        if (!$files || !is_array($files)) {
            self::setError("未上传文件");
            return false;
        }
        $uploadFiles = array();
        if (is_array($files['name'])) {//批量上传
            foreach ($files as $key => $file) {
                foreach ($file as $idx => $value) {
                    $uploadFiles[$idx][$key] = $value;
                }
            }
        } else {
            $uploadFiles = $files;
        }
        //上传路径
        if ($dir === null) {
            $dir = date('Ym') . '/' . date('d');
        }
        $path = ROOT_DIR . UPLOAD_DIR . '/' . $dir;
        //
        $sFiles = array();
        foreach ($uploadFiles as $file) {
            if ($file['error'] != UPLOAD_ERR_OK) {
                continue;
            }
            //使用日期做文件名
            $fileName = sprintf("%s%04d.%s", date("YmdHis"), Tstring::random(4, 1), self::getFileExt($file['name']));
            if ($file['size'] > $maxSize || $file['size'] <= 0) {
                self::setError($file['name'] . "：文件过大");
                continue;
            }
            if (!is_dir($path)) {
                if (!mkdir($path, 0777, true)) {
                    self::setError("文件夹创建失败");
                    return false;
                }
            }
            if (move_uploaded_file($file['tmp_name'], $path . '/' . $fileName)) {
                $sFiles[] = array(
                    'oldname' => $file['name'],
                    'url' => '/' . $dir . '/' . $fileName,
                );
            } else {
                self::setError("上传失败");
                return false;
            }
        }
        return $sFiles;
    }
}
