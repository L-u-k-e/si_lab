<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Employees extends BasePluralModel {
    protected $table_name = 'employees';

    public static function where($where, $where_variables) {
        return new Employees($where, $where_variables);
    }

    public static function all() {
        return new Employees();
    }
}