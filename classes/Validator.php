<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Validator {
    protected $fields = [];

    public function addField($name, $filter, &$value, $error_message) {
        $this->fields[$name] = [
            'filter' => $filter,
            'value' => &$value,
            'error_message' => $error_message
        ];
        return $this;
    }

    public function validate() {
        $were_errors = false;
        $errors = [];
        foreach($this->fields as $field) {
            $were_field_errors = false;
            $methods_list = explode('_and_', $field['filter']);
            foreach($methods_list as $method_name) {
                if(strpos($method_name, 'equals_') === 0) {
                    $method_arguments = [ $field['value'], substr($method_name, 7) ];
                    $method_name = 'is_equals';
                } elseif(preg_match_all('#(.+)\((.*)\)#', $method_name, $matches)) {
                    $method_name = 'is_' . $matches[1][0];
                    $method_arguments = [ $field['value'], $matches[2][0] ];
                } else {
                    $method_arguments = [ $field['value'] ];
                    $method_name = 'is_' . $method_name;
                }
                if(method_exists('Validator', $method_name)) {
                    if(!call_user_func_array(['Validator', $method_name], $method_arguments)) {
                        $were_errors = true;
                        $were_field_errors = true;
                    }
                } else {
                    throw new Exception('No validation method specified: ' . $method_name);
                }
            }
            if($were_field_errors) {
                $errors[] = $field['error_message'];
            }
        }
        if($were_errors) {
            throw new ValidationException($errors);
        }
    }

    private function is_not_empty($value) {
        return $value != '';
    }

    private function is_min_value($value, $min_value) {
        return $value >= $min_value;
    }

    private function is_max_value($value, $max_value) {
        return $value <= $max_value;
    }

    private function is_min_length($str, $min_length) {
        return strlen($str) >= $min_length;
    }

    private function is_equal_to($first, $second) {
        return $first == $second;
    }

    private function is_equals($given, $expected) {
        $expected_arr = explode('_or_', $expected);
        return in_array($given, $expected_arr);
    }

    private function is_email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) || $value == '';
    }

    private function is_postal_code($value) {
        return preg_match('/^[0-9]{2}-[0-9]{3}?$/', $value) || $value = '';
    }
}

class ValidationException extends Exception {
    public $errors;

    function __construct($errors) {
        $this->errors = $errors;
    }
}