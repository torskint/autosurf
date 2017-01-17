<?php

try {
	$i=0;
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$datas = $sites->display($db, $session);
	if(!$errors->getFlash()){}
	$sites->allowPointsToUrls($db, $main, $session, $abuses, $errors);
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


