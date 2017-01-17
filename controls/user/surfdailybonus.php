<?php

try {
	#$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	if(!$surfbar->bonusSurfbar($db, $main, $reward, $errors)){
		//mail a ladmin
	}	 

} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
