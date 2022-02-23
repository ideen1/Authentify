<?php


function defaultErrorHandler($errno, $errstr, $errfile, $errline){
    $debugMode = true;
    if ($debugMode == true){
    $errstr = "<b>$errno</b> [$errno] $errstr<br /> on line $errline of $errfile\n";
    $error = new AuthentifyError("DEH", $errstr);
    $error->printError();
    die();
    }else{
    $error = new AuthentifyError("FUE");
    $error->printError();
    die();
    }
    
}
set_error_handler("defaultErrorHandler");
