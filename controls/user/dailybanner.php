<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	if(!$errors->getFlash()){}
	
	$points = false;
	if(!$errors->getFlash()){}
	if($points = $user->dailybannerbonus($db, $main, $session, $errors)){
		$errors->_throw("Vous avez gagner un bonus de <h1 class='success'>{$points} points</h1> aujourd'hui. Veuillez réessayer dans " . $main->reboursToDate() . " secondes", 2);
	}

} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
