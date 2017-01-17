<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors, true);
    
    $QUIZ = $admin->text($db, "SITE_QUIZ");
    $access = $admin->text($db, "SITE_QUIZ", true);
    
    if(!$errors->getFlash()){}
    $admin->updateTXT($db, $main, "SITE_QUIZ", "quiz", $errors);        
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

