<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class Router {
    protected $routing_table, $default_controller;

    function __construct($routing_table, $default_controller) {
        $this->routing_table = $routing_table;
        $this->default_controller = $default_controller;
    }

    private function run404Action($controller_name) {
        if(class_exists($controller_name)) {
            $controller = new $controller_name();
            if(method_exists($controller, 'error404')
                    && (new ReflectionMethod($controller, 'error404'))->isPublic()) {
                return call_user_func([$controller, 'error404']);
            }
        }
        return call_user_func(new $this->default_controller(), 'error404');
    }

    private function runAction($controller_name, $action_name, $params = []) {
        try {
            if(class_exists($controller_name)) {
                $controller = new $controller_name();
                if(method_exists($controller, $action_name)
                        && (new ReflectionMethod($controller, $action_name))->isPublic()) {
                    return call_user_func_array([$controller, $action_name], $params);
                }
            }
            return false;
        } catch(PDOException $e) {
            return $this->runAction($this->default_controller, 'PDOException', [$e]);
        } catch(Exception $e) {
            return $this->runAction($this->default_controller, 'Exception', [$e]);
        }
    }

    public function route($uri) {
        foreach($this->routing_table as $uri_pattern => $controller_name) {
            if(preg_match_all("#$uri_pattern#", $uri, $matches)) {
                $action_name = $matches[1][0] ? str_replace('-', '_', $matches[1][0]) : 'index';
                $view = $this->runAction($controller_name, $action_name);
                if($view) {
                    return $view;
                }
                return $this->run404Action($controller_name);
            }
        }
        $action_name = $uri ? str_replace('-', '_', $uri) : 'index';
        $view = $this->runAction($this->default_controller, $action_name);
        if($view) {
            return $view;
        }
        return $this->run404Action($this->default_controller);
    }
}