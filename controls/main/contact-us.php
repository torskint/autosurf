<?php

try{
	
	$mail-> contact_us($main, $session, $errors);
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


