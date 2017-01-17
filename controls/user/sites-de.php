<?php

try {
    
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $i=0;
    
    $datas = $sites->display($db, $session);
    $sites->delete($db, $main, $session, $errors);
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


