<?php

try {
	
	$TOP_USERS = $admin->topStats($db, $main->usertbl(), "points");
	$TOP_SITES = $admin->topStats($db, $sites->sitestbl(), "total_views_receive");	   
	
	if($conditions=false/*button a soumettre par exemple*/){
		$admin->top10OfMonthReward($db, $main, $TOP_SITES, "actual_surfbar_points");
		$admin->top10OfMonthReward($db, $main, $TOP_USERS, "points");
	}
	
	unset($TOP_SITES[count($TOP_SITES)-1]);
	unset($TOP_USERS[count($TOP_USERS)-1]);
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

