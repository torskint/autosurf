<?php

try {
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$allReferrals = $user->myReferralsList($db, $session, $errors);
	$myReferralsList=$pagin->setPagin($allReferrals, "user.referrals");
	$nbrpg = (round(count($allReferrals)/10) < count($allReferrals)/10) ? round(count($allReferrals)/10)+1 : round(count($allReferrals)/10);
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
	
	#$search = $pagin->search($db, $main, $errors);
	
	if(!empty($_POST["lost_ref"])){
		if(count($_POST["ref_infos"]) <= 0){ $errors->setFlash("user.referrals", $errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$main->valid("action")){ $errors->setFlash("user.referrals", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
		parse_str($_POST["ref_infos"], $new_ref); 
		if(!$query=$db->prepare("SELECT id, points_for_referer FROM {$main->usertbl()} WHERE username=? && uniqid=? && refererid=?", [$new_ref["name"], $new_ref["uid"], $session->values("id")])){ $errors->setFlash("user.referrals", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ $errors->setFlash("user.referrals", $errors->get($db, "ERROR_INVALID_FIELD")); }
		$rid = $query->fetch(PDO::FETCH_ASSOC)["id"];
		$pfr = $query->fetch(PDO::FETCH_ASSOC)["points_for_referer"];
		if(!$lrp = $reward->lostReferralsPrice($db, $main, $rid, $pfr)){ $errors->setFlash("user.referrals", $errors->get($db, "ERROR_SQL_ERROR")); }
		$db->prepare("UPDATE {$main->usertbl()} SET points=points+? WHERE id=?", [$lrp, $session->values("id")]);
		$db->prepare("UPDATE {$main->usertbl()} SET refererid=?, points_for_referer=? WHERE id=?", [NULL, 0, $rid]);
		$main->setHistory($db, array("name"=>"Referrals released", "content"=>"You have removed < {$new_ref["name"]} > from your referrals.", "at"=>time()), $main->usertbl(), $session);
		$main->setHistory($db, array("name"=>"Without referer", "content"=>"You're currently no sponsor.", "at"=>time()), $main->usertbl(), $rid);
		$errors->setFlash("user.referrals", $errors->get($db, "SUCCESS_DONE"));
	}
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}


