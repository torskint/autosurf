<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $abuses->cancelAbuses($db, $main, $session, $sites, $errors);
   
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

