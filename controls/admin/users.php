<?php

try {
	
	unset($_SESSION["banishment_datas"]);
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	$types = array(
		1 => "admins",
		2 => "Vip golds",
		3 => "Vip silver",
		4 => "Free Users",
		999 => "Not activated"
	);
	
	if(is_numeric($main->postAll("type"))){ $_SESSION["type"] = (int)$main->postAll("type"); $errors->_rdr("admin.users"); }
	else if(isset($_GET["__ky"], $_GET["type"]) && is_numeric($_GET["type"]) && $_GET["__ky"]==md5($session->values("email").'+'.$session->values("password").'+'.$session->values("signup_at"))){ $_SESSION["type"] = (int)$_GET["type"]; $errors->_rdr("admin.users"); }
	$option = isset($_SESSION["type"]) ? (int)$_SESSION["type"] : 1;
	$all = [];
	foreach($admin->getAllUsers($db, $main, $errors, $option) as $infos){
		if(!$main->isBanned($db, $infos["id"])){ $all[] = $infos; }
	}
	$datas = $pagin->setPagin($all, "admin.users");
	
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);	   		 
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

