<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$validitys = [5*60, 3600, 3600*24, 3600*24*7, 3600*24*30, 3600*24*365];
	$query=$db->prepare("SELECT id, username FROM {$main->usertbl()} ORDER BY username");
	$users=$query->fetchAll(PDO::FETCH_ASSOC);
	
	if(!empty($main->postAll("mp"))){
		if(!$main->valid("subject_mp", "message_mp")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$main->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if(!array_key_exists("receiver", $main->postAll()) OR count($j = $main->postAll("receiver")) <= 0){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if($j[0]["id"] == "*"){ $datas = $users; } else { $datas = $j; }
		$validity = array_key_exists((int)$main->postAll("validity"), $validitys) ? (int)$validitys[(int)$main->postAll("validity")] : 3600*24*7;
		foreach($datas as $infos){ $messenger->send($db, $main, $session, array("receiver_id"=>$infos["id"], "message_timeout"=>$validity, "message_mp"=>$main->postAll("message_mp"), "subject_mp"=>$main->postAll("subject_mp")), $errors); }
		$errors->setFlash("admin.message", $errors->get($db, "SUCCESS_DONE"));
	}
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

