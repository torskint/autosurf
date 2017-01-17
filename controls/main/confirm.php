<?php

try{
	 
	if($datas=$main->confirm($db, $mail, $messenger, $errors)){
		$session->refresher($db, $datas, $main, $errors);
	} else {
		$errors->setFlash("main.home", $errors->get($db, "ERROR_CONFIRMATION_KEY_UNAVAILABLE"));
	}
	if(!$errors->getFlash()){}
	
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
