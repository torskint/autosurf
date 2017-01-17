<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	 if(!$errors->getFlash()){}
	
	if(!isset($_POST["TEXT-ADD"])){ return false; }
	if(!$main->post("subject_text") OR !$main->post("message_text")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$main->valid("subject_text") OR !$main->valid("message_text")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$query=$db->prepare("SELECT id FROM {$admin->textTbl()} WHERE name=?", [$main->post("subject_text")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	if($query->rowCount() >= 1){ $errors->_throw($errors->get($db, "ERROR_EXISTS_NAME")); }
	if(!$main->post("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if(!$db->prepare("INSERT INTO {$admin->textTbl()}(name, message, add_at, access) VALUES(?, ?, ?, ?)", [$main->post("subject_text"), $main->post("message_text"), time(), 1])){ $errors->setFlash("{$_GET['from']}", $errors->get($db, "ERROR_SQL_ERROR")); }
	$errors->setFlash("{$_GET['src']}", $errors->get($db, "SUCCESS_DONE")); 
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

