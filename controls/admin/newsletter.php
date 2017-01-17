<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$admin->newsletter($db, $main, $session, $errors);
	
	if(!$errors->getFlash()){}		
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

