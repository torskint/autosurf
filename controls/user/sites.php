<?php

try {
	$i=0;
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$allSites = $sites->display($db, $session);
	$datas=$pagin->setPagin($allSites, "user.sites");
	
	$nbrpg = (round(count($allSites)/10) < count($allSites)/10) ? round(count($allSites)/10)+1 : round(count($allSites)/10);		 
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


