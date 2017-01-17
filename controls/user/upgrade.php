<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$formuleslist = $vip->formulesList($db);
	
	if(!$errors->getFlash()){}
	$vip->upgrade($db, $main, $session, $reward, $errors);
	
  	  
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

