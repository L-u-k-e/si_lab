<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class View {

    private $filename, $parent = null;
    private $vars = [];

    function __construct($template_name) {
        $this->filename = "templates/$template_name.php";
    }

    public function setParent(View $parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getVars() {
        return $this->vars;
    }

    public function set($name, $value, $safe = false) {
        if($value instanceof View) {
            if(!$value->getParent()) {
                $value->setParent($this);
            }
            $this->{$name} = $value;
        }
        $this->vars[$name] = Utils::htmlentities($value);
        return $this;
    }

    public function render($minify = false) {
        if($parent = $this->getParent()) {
            extract($parent->getVars());
        }
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