<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$query=$db->prepare("SELECT id as t_id, name, access  FROM {$admin->textTbl()} ORDER BY name");
	$t_datas = $query->fetchAll(PDO::FETCH_ASSOC);
	$query=$db->prepare("SELECT id as e_id, name, access  FROM {$admin->errorsTbl()} ORDER BY name");
	$e_datas = $query->fetchAll(PDO::FETCH_ASSOC);
	$all = array_merge($t_datas, $e_datas);
	
	$datas = $pagin->setPagin($all, "admin.secure");
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);	   		 
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	
	if(!empty($_POST["submit"])){
		if(count($_POST["secure"]) <= 0){ $errors->setFlash("admin.secure", $errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$main->valid("action")){ $errors->setFlash("admin.secure", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
		foreach($_POST["secure"] as $id){
	   	 parse_str($id, $secure); 
	   	 $new_access = ($secure["access"] == 0) ? 1 : 0;
	   	 if(!$db->prepare("UPDATE {$admin->$secure["tbl"]()} SET access=? WHERE id=?", [$new_access, $secure["id"]])) { $w[] = 1; }
		}
		if(count($w) <= 0){ $errors->setFlash("admin.secure", $errors->get($db, "SUCCESS_DONE")); }
		$errors->setFlash("admin.secure", $errors->get($db, "ERROR_SQL_ERROR"));
	}
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

