<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	 
	if(!isset($_POST["DE-MANAGER"]) OR !$main->post("id") OR !isset($_POST["tbl"])){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$query=$db->prepare("SELECT id FROM {$_POST['tbl']} WHERE id=?", [$main->post("id")])){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_SQL_ERROR")); }
	if($query->rowCount() <= 0){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$main->post("action")){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
	if(!$query=$db->prepare("DELETE FROM {$_POST['tbl']} WHERE id=?", [$main->post("id")])){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_SQL_ERROR")); }
	$errors->setFlash("{$_GET['from']}", $errors->get($db, "SUCCESS_DONE"));
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

