<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$abuseslist = $abuses->abusesTypeList($db);
	$abuses->setAbuses($db, $main, $user, $session, $sites, $errors);
	 
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

