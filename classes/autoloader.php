<?php

/**
 * @author Jakub Młokosiewicz
 * @copyright Copyright (c) 2014 Jakub Młokosiewicz
 */

function __autoload($classname) {
    $filenames = [
        "classes/$classname.php",
        "classes/controllers/$classname.php",
        "classes/models/$classname.php"
    ];
    foreach($filenames as $filename) {
        if(file_exists($filename)) {
            include($filename);
            return;
        }
    }
    throw new Exception("Wystąpił problem z załadowaniem pliku dla klasy $classname.");
}