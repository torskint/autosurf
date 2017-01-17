<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$datas = array(
		0=>"accounts",
		1=>"bonus",
		2=>"sessions",
		3=>"sites",
		4=>"surfbar",
		5=>"blacklist",
	);
	
	$displayAll = false; 
	if(is_numeric($main->postAll("historic_id"))){ $_SESSION["historic_id"] = (int)$main->postAll("historic_id"); $errors->_rdr("user.historic"); }
	$option = isset($_SESSION["historic_id"]) ? (int)$_SESSION["historic_id"] : 0;
	if($session->values("member_type") <= 3){
	$allHistoric = $main->getHistory($db, $datas[$option], $session);
	} else {
		$allHistoric = [];
		$displayAll = true; 
		foreach($datas as $option){
	   	 $allHistoric = array_merge($allHistoric, $main->getHistory($db, $option, $session));
		}
	}
	$historic = $pagin->setPagin($allHistoric, "user.historic");
	$nbrpg = (round(count($allHistoric)/10) < count($allHistoric)/10) ? round(count($allHistoric)/10)+1 : round(count($allHistoric)/10);	  
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	if(!empty($main->postAll("TRUNCATE_HISTORIC")) && array_key_exists($main->postAll("HISTORIC_ID"), $datas)){
		if(!$db->prepare("DELETE FROM histo_{$datas[$main->postAll("HISTORIC_ID")]} WHERE user_id=?", [$session->values("id")])){ $errors->setFlash("user.historic", $errors->get($db, "ERROR_SQL_ERROR")); }
		$errors->setFlash("user.historic", $errors->get($db, "SUCCESS_HISTORIC_TRUNCATED"));
	}
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


