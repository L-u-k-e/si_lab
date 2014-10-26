<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

session_start();
error_reporting(E_ALL & ~E_NOTICE);

include 'classes/autoloader.php';

Utils::trimAllValues($_GET);
Utils::trimAllValues($_POST);

$router = new Router(
    [
        'employee(?:/(.+))?' => 'EmployeeController',
        'user(?:/(.+))?' => 'UserController'
    ],
    'StaticController'
);

echo $router->route($_GET['s']);

?>