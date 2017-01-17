<?php

try {
    
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    if(!$errors->getFlash()){}
    $i=0;
    
    $sites->transfertPointsToAnotherUser($db, $main, $session, $abuses, $errors);
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


