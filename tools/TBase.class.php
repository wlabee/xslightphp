<?php

class TBase {
    public static $error = array();

    public static function setError($error) {
        self::$error[] = $error;
    }

    public static function getError() {
        return implode(',', self::$error);
    }
}
