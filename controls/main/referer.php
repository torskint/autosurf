<?php

try{
	session_destroy();
	session_start();
	
	if(!isset($_GET["ruid"]) OR !preg_match("/^[a-z0-9-_]{6,}$/i", $_GET["ruid"])){ $errors->setFlash("main.register", ["type"=>0, "message"=>"The Godfather chosen is not currently available."]); }
	
	if(!$query=$db->prepare("SELECT id FROM {$tables->user_tbln()} WHERE username=? && member_type<=? && account_is_validate=? && signup_at <=?", [$_GET["ruid"], 4, 1, time() - 3600*24*7])){ $errors->setFlash("main.register", ["type"=>0, "message"=>"Our system to detect an error in the request submitted."]); }
	if($query->rowCount() <= 0){ $errors->setFlash("main.register", ["type"=>0, "message"=>"The Godfather chosen is not currently allowed to receive referrals."]); }
	if(!$datas = $query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("main.register", ["type"=>0, "message"=>"Our system to detect an error in the request submitted."]); }
	
	if(!$query=$db->prepare("SELECT id FROM {$tables->banishment_tbln()} WHERE user_id=?", [$datas["id"]])){ $errors->setFlash("main.register", ["type"=>0, "message"=>"The Godfather chosen is currently banned."]); }
	if($query->rowCount() > 0){ $errors->setFlash("main.register", ["type"=>0, "message"=>"The Godfather chosen is currently banned."]); }
	
	$_SESSION["refererid"] = ((int)$datas["id"] > 0) ? (int)$datas["id"] : NULL;
	if(!is_null($_SESSION["refererid"])){ $errors->setFlash("main.register", ["type"=>1, "message"=>"Success , The Godfather chosen is available."]); }
	
	$errors->setFlash("main.register", ["type"=>0, "message"=>"The Godfather chosen is not currently available."]);
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

