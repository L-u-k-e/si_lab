<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Users extends BasePluralModel {
    protected $table_name = 'users';

    public static function where($where, $where_variables) {
        return new Users($where, $where_variables);
    }

    public static function all() {
        return new Users();
    }
}