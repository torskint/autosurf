<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	$w = [];
	$from = isset($_POST["from"]) ? "admin.".$_POST["from"] : "admin.home";
	if(count($_POST["user_ids"]) <= 0){ $errors->setFlash($from, $errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$main->valid("action")){ $errors->setFlash($from, $errors->get($db, "ERROR_CONFIRM_ACTION")); }
	
	## ACCOUNT DELETE
	if(!empty($_POST["submit_delete_account"])){ 
		foreach($_POST["user_ids"] as $id){
	   	 foreach($tables->tblArray() as $tbl){
	   		 if(!$db->prepare("DELETE FROM {$tbl} WHERE user_id=?", array($id))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 }
	   	 if(!$db->prepare("UPDATE {$main->usertbl()} SET refererid=?, points_for_referer=? WHERE refererid=?", array(NULL, 0, $id))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if(!$db->prepare("DELETE FROM {$main->usertbl()} WHERE id=?", [$id])) { $w[] = 1; }
		}
	}
	
	## ACCOUNT BANISHMENT
	else if(!empty($_POST["submit_account_banishment"])){
		foreach($_POST["user_ids"] as $id){
	   	 $query=$db->prepare("SELECT id FROM {$main->usertbl()} WHERE id=?", [(int)$id]);
	   	 if($query->rowCount() <= 0){ $w[] = 1; }
	   	 else { $_SESSION["banishment_datas"] = $_POST; }
		}
		if(!empty($_SESSION["banishment_datas"])){ $errors->setFlash("admin.banishment-add", $errors->get($db, "SUCCESS_TARGET_INFOS_IS_READY")); }
	}
	
	## ACCOUNT VALIDATION
	else if(!empty($_POST["submit_account_validation"])){
		foreach($_POST["user_ids"] as $id){
	   	 $query=$db->prepare("SELECT id FROM {$main->usertbl()} WHERE id=?", [$id]);
	   	 if($query->rowCount() <= 0){ $w[] = 1; }
	   	 else { $_SESSION["validation_datas"] = $_POST; }
		}
		if(!empty($_SESSION["validation_datas"])){ $errors->setFlash("admin.account-validation", $errors->get($db, "SUCCESS_TARGET_INFOS_IS_READY")); }
	}
	
	if(isset($w) && count($w) <= 0){ $errors->setFlash($from, $errors->get($db, "SUCCESS_DONE")); }
	else if(isset($w) && count($w) > 0){ $errors->setFlash($from, $errors->get($db, "ERROR_SQL_ERROR")); }
	else { $errors->setFlash($from, $errors->get($db, "ERROR_INVALID_FIELD")); }
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

