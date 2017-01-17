<?php

try{
	unset($_SESSION["accounts"]);
	if(!$errors->getFlash()){}
	
	$main->register($db, $mail, $errors);
	
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

