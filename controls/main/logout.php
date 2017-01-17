<?php

try{
    session_destroy();
    setCookie("TRACK-ID", "1", time()-7*24*3600, "/");
    setCookie("PHPSESS-ID", "1", time()-7*24*3600, "/");
    session_start();
    if(isset($_GET["timeout"])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SESSION_TIMEOUT")); }
    $errors->setFlash("main.home", $errors->get($db, "SUCCESS_LOGOUT"));
}
catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}
