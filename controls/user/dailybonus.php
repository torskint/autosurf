<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$points = false; 
	$all = $bonus->dailybonus_stats($db);
	
	$datas = $pagin->setPagin($all, "user.dailybonus");
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);	   
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0; }
	
	$points = $bonus->dailybonus($db, $main, $session, $errors);
	### if($points){ $errors->_throw($errors->get($db, "SUCCESS_DAILY_BONUS_WON")); }
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 str_replace("#s", "<span class='text-success badge'>".number_format($points)."</span>", $e->getMessage()), 
	   	 $e->getCode()
		);
}
