<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors, true);
    
    $access = isset($_SESSION["TEXT"]["access"]) ? true: false;
    if(!empty($_POST["TEXT"]) && $main->post("id")){
        $query=$db->prepare("SELECT * FROM {$admin->textTbl()} WHERE id=?", [$main->post("id")]);
        if($query->rowCount() <= 0){ $errors->setFlash("admin.text", $errors->get($db, "ERROR_INVALID_FIELD")); }
        $_SESSION["TEXT"] = $query->fetch(PDO::FETCH_ASSOC);
        if($_SESSION["TEXT"]["access"] == 0){ unset($_SESSION["TEXT"]["access"]); }
        $errors->setFlash("admin.text-ed", $errors->get($db, "SUCCESS_TARGET_INFOS_IS_READY"));
    }
     
    if(empty($_SESSION["TEXT"])){ $errors->setFlash("admin.text", $errors->get($db, "ERROR_ACCESS_DENIED")); }
    $contents = $admin->text($db, $_SESSION["TEXT"]["name"]);
    if(!$contents){ $errors->setFlash("admin.text", $errors->get($db, "ERROR_SQL_ERROR")); }
    
    if(!empty($main->postAll("Update"))){
        if(!$admin->updateTXT($db, $main, $_SESSION["TEXT"]["name"], "text", $errors)){
            $errors->setFlash("admin.text", $errors->get($db, "ERROR_INVALID_FIELD"));
        }
    }
    
    if(!$errors->getFlash()){}       
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

