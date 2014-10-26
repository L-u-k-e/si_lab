<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Utils {
    static function trimAllValues(&$array) {
        array_walk_recursive($array, function(&$val) { 
            $val = trim($val);
        });
    }

    static function hash($str) {
        return hash('sha512', '$%#%#' . $str . '(*&^'); 
    }

    static function destroyAndClearSession() {
        session_destroy();
        $_SESSION = [];
        session_start();
    }

    static function htmlentities($value) {
        if(is_array($value)) {
            array_walk_recursive($value, function(&$val) { 
                $val = htmlentities($val);
            });
        } elseif(!is_object($value)) {
            $value = htmlentities($value);
        }
        return $value;
    }
}