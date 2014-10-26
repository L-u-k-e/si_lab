<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class DB {
    protected static $pdo = null;

    public static function instance() {
        if(!self::$pdo) {
            self::$pdo = new PDO('mysql:host=localhost;dbname=silab;charset=utf8', 'user', 'pass');
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}