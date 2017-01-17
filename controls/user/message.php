<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$message_content = array();
	
	if(isset($_GET["_bz"])){
		parse_str(base64_decode($_GET["_bz"]), $GET);
		
		if(isset($GET["cid"], $GET["sum"])){
	   	 $message_id = (int)$GET["cid"];
	   	 $message_sum = md5($GET["sum"]);
	   	 $message_content = $messenger->messages_cleaner($db, $session, $message_id, $message_sum, $errors);
		}
		
		if(isset($GET["mid"], $GET["sum"])){
	   	 $message_id = (int)$GET["mid"];
	   	 $message_sum = md5($GET["sum"]);
	   	 $message_content = $messenger->messages_view($db, $session, $message_id, $message_sum, $errors);
	   	 $messenger->messages_is_read($db, $session, $message_id, $message_sum);
		}
		
	}
	
	if(empty($message_content)){ $messenger->updateChecksum($db, $session); }
	$all = $messenger->messages_list($db, $session);
	$datas = $pagin->setPagin($all, "user.message");
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);	   
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0; }
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

