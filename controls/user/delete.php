<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	if(!$errors->getFlash()){}
	
	$user->delete($db, $main, $session, $errors);

} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

