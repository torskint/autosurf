<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$member_grades = [1=>"administrator", 2=>"gold user", 3=>"Silver user", 4=>"Free user", 999=>"Not active"];
	$refererid=($main->postAll("me_rf") == 1) ? intval($session->values("id")) : NULL;
	
	if(!$errors->getFlash()){}
	
	if(empty($main->postAll())){ return false; }
	if(!$main->valid("username", "email", "country", "password")){ $errors->_throw($errors->get($db,"ERROR_INVALID_FIELD")); }
	if($main->indb($db, $main->post("email"), "id", true)){ $errors->_throw($errors->get($db, "ERROR_EMAIL_UNAVAILABLE")); }
	if($main->username_indb($db, $main->post("username"), true)){ $errors->_throw($errors->get($db, "ERROR_USERNAME_UNAVAILABLE")); }
	if(!is_numeric($points=$main->postAll("points")) OR $main->postAll("points") <= 0 ){ $errors->_throw($errors->get($db,"ERROR_INVALID_FIELDS")); }
	if(!is_numeric($member_type=$main->postAll("member_type")) OR !array_key_exists($main->postAll("member_type"), $member_grades)){ $errors->_throw($errors->get($db,"ERROR_INVALID_FIELD")); }
	$country = $main->country($main->post("country"));
	
	if($member_type == 999){
		
		if(!$db->prepare("INSERT INTO {$main->usertbl()}(username, email, country, password, points, member_type, account_is_validate, account_validation_key, uniqid, refererid, points_for_referer, signup_at) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($main->post("username"), $main->post("email"), $country, $main->hash($main->post("password")), $points, $member_type, NULL, $x=$main->confirmation_key(), $u=$main->uniqid($db, 7), $refererid, NULL, time()))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		$mail->send(array("email"=>$main->post("email"), "username"=>$main->post("username") , "confirmation_link"=>"#ROOT#/?src=main.confirm&key=".base64_encode($x."2".$u)), "registred_mail");
	
	} else {
		if(!$db->prepare("INSERT INTO {$main->usertbl()}(username, email, country, password, points, member_type, account_is_validate, account_validation_key, uniqid, refererid, points_for_referer, signup_at) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($main->post("username"), $main->post("email"), $country, $main->hash($main->post("password")), $points, $member_type, 1, NULL, $main->uniqid($db, 7), $refererid, 0, time()))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
	}
	
	$main->setHistory($db, array("name"=>"[By Admin] Registration", "content"=>"You have been registred successfuly on our site.", "at"=>time()), $main, $db->lastInsertId());
	$errors->setFlash("admin.users-add", $errors->get($db,"SUCCESS_DONE"));		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


