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
                if(is_string($val)) {
                    $val = htmlentities($val);
                }
            });
        } elseif(is_string($value)) {
            $value = htmlentities($value);
        }
        return $value;
    }

    static function CSRFProtection() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['__csrf_token'] != $_SESSION['__csrf_token']) {
                die('Wykryto próbę ataku CSRF lub nastąpił błąd systemu. Proszę spróbować później.');
            }
        }
        $_SESSION['__csrf_token'] = self::hash(time() * 12.34);
    }

    static function getCSRFToken() {
        return $_SESSION['__csrf_token'];
    }

    static function getCSRFTokenField() {
        return '<input type="hidden" name="__csrf_token" value="' . $_SESSION['__csrf_token'] . '">';
    }
}

class CSRFException extends Exception { }