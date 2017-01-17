<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	if(!$errors->getFlash()){}
	
	if(!$query=$db->prepare("SELECT * FROM {$main->usertbl()} WHERE id=?", array($session->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	$datas = $query->fetch(PDO::FETCH_ASSOC);
	
	$user->editPassword($db, $main, $session, $errors);

} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


