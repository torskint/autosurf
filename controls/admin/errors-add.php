<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	 if(!$errors->getFlash()){}
	
	if(!isset($_POST["ERRORS-ADD"])){ return false; }
	if(!$main->post("subject_errors") OR !$main->post("message_errors")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$main->valid("subject_errors") OR !$main->valid("message_errors")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$query=$db->prepare("SELECT id FROM {$admin->errorsTbl()} WHERE name=?", [$main->post("subject_errors")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	if($query->rowCount() >= 1){ $errors->_throw($errors->get($db, "ERROR_EXISTS_NAME")); }
	if(!$main->post("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if(!$db->prepare("INSERT INTO {$admin->errorsTbl()}(name, message, add_at, class_html, access) VALUES(?, ?, ?, ?, ?)", [htmlentities($main->post("subject_errors"), ENT_QUOTES, "UTF-8"), htmlentities($main->post("message_errors"), ENT_QUOTES, "UTF-8"), time(), (int)$main->post("id_errors"), 1])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	$errors->setFlash("{$_GET['src']}", $errors->get($db, "SUCCESS_DONE")); 
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

