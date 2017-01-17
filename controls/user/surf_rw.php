<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	/*
	if(empty($_POST["SURF_VALIDATE"])){ echo ""; die(); }
	$end = time(); $y = unserialize($_POST["SURF_VALIDATE"]);
	$id = (int)$y["id"]; $start = (int)$y["start"];
	if(!isset($id) OR $end-$start < 15){ echo ""; die(); }
	
	if(!$query=$db->prepare("SELECT * FROM {$surfbar->surfbarTbl()} WHERE user_id=?", [$session->values("id")])){ echo ""; die(); }
	if($query->rowCount() > 0){
		if(!$query=$db->prepare("SELECT * FROM {$surfbar->surfbarTbl()} WHERE user_id=? && last_at <= ? ", [$session->values("id"), $end-15])){ echo ""; die(); }	
  	  if(!$infos=$query->fetch(PDO::FETCH_ASSOC)){ echo ""; die(); }
		if($query->rowCount() <= 0){ echo ""; die(); }
	} //0+15 >= end
	
	$surfbar->rewarder($db, ["id"=> $id], $main, $sites, $session, $reward, $errors);
	echo "success"; die();
	*/
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


