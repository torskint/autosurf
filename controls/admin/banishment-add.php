<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors, true);
    
    $banishment_causes = ["insulté", "gentil", "morveux", "salo"];
    $banishment_durations = [1, 7, 15, 30, 90, 180];
    $w = [];
    $from = isset($_SESSION["from"]) ? "admin.".$_SESSION["from"] : "admin.users";
    if(!isset($_SESSION["banishment_datas"])){ $errors->setFlash($from, $errors->get($db, "ERROR_INVALID_FIELD")); };
    $datas = $_SESSION["banishment_datas"]["user_ids"];
    if(empty($_POST["complete_banishment"])){ return false; }
    if(count($_POST["banishment"]) <= 0){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
    if(!$main->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
    foreach($datas as $id){ $id = (int)$id;
        if(array_key_exists($id, $_POST["banishment"]) && in_array($id, $datas) && count($infos = $_POST["banishment"][$id]) >= 2){
            if(array_key_exists($infos[0], $banishment_causes) && array_key_exists($infos[1], $banishment_durations)){
                $member_type=$db->prepare("SELECT member_type FROM {$main->usertbl()} WHERE id=?", [$id])->fetch(PDO::FETCH_ASSOC)["member_type"];
                if(!$db->prepare("INSERT INTO {$main->banishmentTbl()}(user_id, old_member_type, cause, timeout, add_at) VALUES(?, ?, ?, ?, ?)", [(int)$id, (int)$member_type, $banishment_causes[(int)$infos[0]], ($banishment_durations[(int)$infos[1]]*3600), time()])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
                if(!$db->prepare("UPDATE {$main->usertbl()} SET member_type=? WHERE id=?", [998, $id])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
                $w[]=$id;
            }
        }
    }
    if(count($w) == count($_POST["banishment"])){
        $errors->setFlash($from, $errors->get($db, "SUCCESS_DONE"));
    }
    $errors->setFlash($from, $errors->get($db, "ERROR_SQL_ERROR"));
    
    if(!$errors->getFlash()){}        
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}
