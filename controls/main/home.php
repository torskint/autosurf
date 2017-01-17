<?php

try{
	#unset($_SESSION["accounts"]);
	
	if($datas=$main->login($db, $errors)){
		$session->refresher($db, $datas, $main, $errors);
	}
	
	if(!$errors->getFlash()){}
	
}
catch(Exception $e){
	 $errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
