<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $sites->add($db, $main, $session, $reward, $abuses, $errors);
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


