<?php 

require_once __DIR__ . "/../vendor/autoload.php";

session_start();

spl_autoload_register(function($claseName){
    
    // Saltear el namespace App\
    $claseName = substr($claseName, 4);

    $filename = __DIR__ .  "/../classes/". $claseName . ".php";
    
    // Normalizar las barras
    $filename = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $filename);
    
    if (file_exists($filename)) {
        require_once $filename;
    }

});



