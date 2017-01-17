<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $all = $abuses->getAbuses($db, $session, $sites, $errors) ? $abuses->getAbuses($db, $session, $sites, $errors) : array();
    $blacklist = $pagin->setPagin($all, "user.blacklist");
    
    $nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);       
    if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0; }
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


