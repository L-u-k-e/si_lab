<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class View {

    private $filename;
    private $vars = [];

    function __construct($template_name) {
        $this->filename = "templates/$template_name.php";
    }

    function set($name, $value, $safe = false) {
        if($value instanceof View) {
            $this->{$name} = $value;
        }
        $this->vars[$name] = Utils::htmlentities($value);
        return $this;
    }

    function render($minify = false) {
        extract($this->vars);
        ob_start();
        include($this->filename);
        $html = ob_get_clean();
        if($minify) {
            $html = preg_replace('#>\s+<#U', '><', $html);
        }
        return $html;
    }

    function __toString() {
        return $this->render();
    }
}