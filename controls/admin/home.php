<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	
	$topUser = $admin->topStats($db, $main->usertbl(), "points", 1);
	$topUser = (count($topUser) <= 0) ? 0 : $topUser[0];
	$allNotActivateUsers = count($admin->getAllUsers($db, $main, $errors, 999));		 
	$allFreeUsers = count($admin->getAllUsers($db, $main, $errors, 4));
	$allVipSilvers = count($admin->getAllUsers($db, $main, $errors, 3));
	$allAdmins = count($admin->getAllUsers($db, $main, $errors, 1));
	$allVipGolds = count($admin->getAllUsers($db, $main, $errors, 2));
	$allBannedUsers = count($admin->getAllUsers($db, $main, $errors, 998));
	
	if(!$errors->getFlash()){}	   
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

