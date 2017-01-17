<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors, true);
    
    $query=$db->prepare("SELECT *  FROM {$main->banishmentTbl()} ORDER BY add_at DESC");
    $all = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $datas = $pagin->setPagin($all, "admin.banishment");
    $nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);                
    if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
    
    if(!empty($_POST["submit"])){
        if(count($_POST["user_ids"]) <= 0){ $errors->setFlash("admin.banishment", $errors->get($db, "ERROR_INVALID_FIELD")); }
        if(!$main->valid("action")){ $errors->setFlash("admin.banishment", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
        foreach($_POST["user_ids"] as $id){
            $query=$db->prepare("SELECT id, old_member_type FROM {$main->banishmentTbl()} WHERE user_id=?", [$id]);
            if($query->rowCount() <= 0){ $w[] = 1; }
            else {
                $datas = $query->fetch(PDO::FETCH_ASSOC);
                $db->prepare("UPDATE {$tables->user_tbln()} SET member_type=? WHERE id=?", [(int)$datas["old_member_type"], (int)$id]);
                $db->prepare("DELETE FROM {$main->banishmentTbl()} WHERE user_id=?&& id=?", [(int)$id, (int)$datas["id"]]); }
        }
        if(count($w) <= 0){
            $query=$db->prepare("SELECT id FROM {$main->banishmentTbl()}");
            if($query->rowCount() <= 0){ $db->tblTruncate($main->banishmentTbl()); }
            $errors->setFlash("admin.banishment", $errors->get($db, "SUCCESS_DONE"));
        }
        $errors->setFlash("admin.banishment", $errors->get($db, "ERROR_SQL_ERROR"));
    }
    
    if(!$errors->getFlash()){}        
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

