<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	if($session->values("member_type") != 1){
		$query=$db->prepare("SELECT * FROM {$main->usertbl()} WHERE member_type <= ? && account_is_validate=? && refererid=? && id!=? ORDER BY username", [4, 1, (int)$session->values("id"), (int)$session->values("id")]);
		$datas=$query->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$query=$db->prepare("SELECT * FROM {$main->usertbl()} WHERE member_type <= ? && account_is_validate=? && id!=? ORDER BY username", [4, 1, (int)$session->values("id")]);
		$datas=$query->fetchAll(PDO::FETCH_ASSOC);
	}
	$admin->crediteUser($db, $main, $session, $errors);
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

