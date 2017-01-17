<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$query=$db->prepare("SELECT * FROM {$main->usertbl()} WHERE account_is_validate=? && member_type >= ? && member_type<=? && id!=? && refererid IS NULL", [1, 2, 4, $session->values("id")]);
	$all = $query->fetchAll(PDO::FETCH_ASSOC);
	$datas=$pagin->setPagin($all, "user.getReferrals");
	
	$nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	if(!empty($_POST["get_ref"])){
		if(count($_POST["ref_infos"]) <= 0){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$main->valid("action")){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
		parse_str($_POST["ref_infos"], $new_ref); 
		if(!$query=$db->prepare("SELECT id FROM {$main->usertbl()} WHERE username=? && uniqid=? && account_is_validate=? && member_type<=? && refererid IS NULL", [$new_ref["name"], $new_ref["uid"], 1, 4])){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_INVALID_FIELD")); }
		$rid = $query->fetch(PDO::FETCH_ASSOC)["id"];
		if(!$query=$db->prepare("SELECT points FROM {$main->usertbl()} WHERE id=?", [$session->values("id")])){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$reward->getReferralsPrice($db, $main, $rid)){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->fetch(PDO::FETCH_ASSOC)["points"] < $reward->getReferralsPrice($db, $main, $rid)){ $errors->setFlash("user.getReferrals", $errors->get($db, "ERROR_LOW_POINTS")); }
		$db->prepare("UPDATE {$main->usertbl()} SET points=points-? WHERE id=?", [$gr = $reward->getReferralsPrice($db, $main, $rid), $session->values("id")]);
		$db->prepare("UPDATE {$main->usertbl()} SET points=points+?, refererid=?, points_for_referer=? WHERE id=?", [$pt=(int)($gr*0.025), $session->values("id"), 0, $rid]);
		$main->setHistory($db, array("name"=>"Referrals added", "content"=>"You have added < {$new_ref["name"]} > to your referrals list.", "at"=>time()), $main->usertbl(), $session);
		$messenger->send_auto($db, array("receiver_id"=>$rid, "referer_name"=>$session->values("username"), "bonus"=>$pt), "mp_isGotenAsReferrals");
		$errors->setFlash("user.getReferrals", $errors->get($db, "SUCCESS_DONE"));
	}
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


