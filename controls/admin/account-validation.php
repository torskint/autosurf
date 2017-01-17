<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$member_grades = [1=>"administrator", 2=>"gold user", 3=>"Silver user", 4=>"Free user"];
	$w = []; $grd = [];
	$from = isset($_SESSION["from"]) ? "admin.".$_SESSION["from"] : "admin.users";
	if(!isset($_SESSION["validation_datas"])){ $errors->setFlash($from, $errors->get($db, "ERROR_INVALID_FIELD")); };
	$datas = $_SESSION["validation_datas"]["user_ids"];
	
	if(empty($_POST["complete_validation"])){ return false; }
	if(count($_POST["validation"]) <= 0){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(count($_POST["validation_points"]) <= 0){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
	if(!$main->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
	
	foreach($datas as $id){
		if(array_key_exists($id, $_POST["validation_points"]) && is_numeric($points=$_POST["validation_points"][$id][0]) && array_key_exists($id, $_POST["validation"]) && is_numeric($grade = $_POST["validation"][$id][0])){
	   	 $grd[] = $grade;
	   	 if(array_key_exists($grade, $member_grades)){
	   		 if(!$db->prepare("UPDATE {$main->usertbl()} SET points=?, member_type=?, account_is_validate=?, account_validation_key=? WHERE id=?", [$points, $grade, 1, NULL, $id])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   		 $w[]=$id;
	   	 }
		}
	}
	if(count($w) == count($grd)){ $errors->setFlash($from, $errors->get($db, "SUCCESS_DONE")); }
	$errors->setFlash($from, $errors->get($db, "ERROR_SQL_ERROR"));
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
