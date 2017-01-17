<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $Credits_datas = $paypal->getCredits_offers($db);
    
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

