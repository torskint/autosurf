<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$access = isset($_SESSION["ERRORS"]["access"]) ? true: false;
	if(!empty($_POST["ERRORS"]) && $main->post("id_errors")){
		$query=$db->prepare("SELECT * FROM {$admin->errorsTbl()} WHERE id=?", [$main->post("id_errors")]);
		if($query->rowCount() <= 0){ $errors->setFlash("admin.errors", $errors->get($db, "ERROR_INVALID_FIELD")); }
		$_SESSION["ERRORS"] = $query->fetch(PDO::FETCH_ASSOC);
		if($_SESSION["ERRORS"]["access"] == 0){ unset($_SESSION["ERRORS"]["access"]); }
		$errors->setFlash("admin.errors-ed", $errors->get($db, "SUCCESS_TARGET_INFOS_IS_READY"));
	}
 	
	if(empty($_SESSION["ERRORS"])){ $errors->setFlash("admin.errors", $errors->get($db, "ERROR_ACCESS_DENIED")); }
	if(!$contents = $admin->errors($db, $_SESSION["ERRORS"]["name"])){ $errors->setFlash("admin.errors", $errors->get($db, "ERROR_SQL_ERROR")); }
	
	if(!empty($main->postAll("Update"))){
		if(!$admin->updateERRORS($db, $main, $_SESSION["ERRORS"]["id"], "errors", $errors)){
	   	  $errors->setFlash("admin.errors", $errors->get($db, "ERROR_INVALID_FIELD"));
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

