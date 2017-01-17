<?php

try{
	
	if(!$errors->getFlash()){}
	
	if(!empty($_SESSION["pwdreset"])){
		$main->pwdresetComplete($db, $_SESSION["pwdreset"], $errors);
	} else {
	
  	  if($datas = $main->pwdResetStart($db, $mail, $errors)){
	   	 $errors->setFlash("main.reset-pwd", $errors->get($db, "SUCCESS_PASSWORD_SENT"));	   
		} else {
	   	 return false;
	   	 $errors->_throw($errors->get($db, "ERROR_PASSWORD_NOT_SENT"));
		}
		
	}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

