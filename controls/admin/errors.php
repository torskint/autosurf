<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	unset($_SESSION["ERRORS"]);
	
	$all=$admin->getErrors($db);
	$datas = $pagin->setPagin($all, "admin.errors");
	
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);	   		 
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	if(!$errors->getFlash()){}
	
  	  
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

