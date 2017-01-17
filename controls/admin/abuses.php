<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors, true);
	$datas = [];
	
	$query=$db->prepare("SELECT * FROM {$abuses->blacklistTbl()}");
	if($query->rowCount() <= 0){ return []; }
    $all = array();
    foreach($query->fetchAll() as $infos){
        $datas = unserialize($infos["content"]);
        foreach($datas as $v){
            $all[$v["site_id"]][] = $v["abuse_id"];
        }
    }
    
    $datas = $pagin->setPagin($all, "admin.abuses");
    
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

