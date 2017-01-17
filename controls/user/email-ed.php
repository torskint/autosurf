<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    if(!$query=$db->prepare("SELECT * FROM {$main->usertbl()} WHERE id=?", array($session->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
    $datas = $query->fetch(PDO::FETCH_ASSOC);
    
     $user->editEmail($db, $main, $session, $errors);
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


