<?php

try {
    
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $i=0;
    
    if(!empty($_POST["site_id"])){
        $datas = $sites->getEditDatasFromSiteslist($db, $main, $session, $errors);
        $_SESSION["site_infos"] = $datas;
        #$errors->setFlash("user.sites-ed", $errors->get($db, "SUCCESS_TARGET_INFOS_IS_READY"));
        $errors->_rdr("user.sites-ed");
        exit;
    }
    if(!empty($_SESSION["site_infos"]) && empty($_POST["site_id"])){ $sites->edit($db, $main, $session, $abuses, $errors); }
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}


