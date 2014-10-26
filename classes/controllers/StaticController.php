<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

class StaticController extends BaseController {
    public function index() {
        return $this->defaultView()->set('main_content', new View('partials/main'));
    }
}