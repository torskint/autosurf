<?php

class reward {
	
	private $table;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->admin_points_tbln();
	}
	
	public function status($member_type){
		$member_type = (int)$member_type;
		if($member_type == 3){ return "silver"; }
		if($member_type == 2){ return "gold"; }
		if($member_type == 1){ return "admin"; }
		else { return "free"; }
	}
	
	public function reward($db, $name, $errors, $member_type=false){
		if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE name=?", [$name])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }	   	
  	  if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$member_type){ return $datas; }
		return $datas[$this->status((int)$member_type)];
	}
	
	//ok
	public function userToVip_RefererRewardPercentage($db, $mainObject, $id, $errors){
		if(!$query=$db->prepare("SELECT member_type FROM {$mainObject->usertbl()} WHERE id=?", array($id))){ return 0; }
		if(!$pe=$query->fetch(PDO::FETCH_ASSOC)["member_type"]){ return 0; }
		return $point = $this->reward($db, "user_to_vip_referer_reward", $errors, $pe);
	}
	//ok
	public function getSurfbarPoints($db, $sessionObj, $errors){
		$ge = $sessionObj->values("member_type");
		return $point = $this->reward($db, "get_surfbar_points", $errors, $ge);
	}
	//ok
	public function lostSurfbarPoints($db, $sessionObj, $errors){
		$lo = $sessionObj->values("member_type");
		return $point = $this->reward($db, "lost_surfbar_points", $errors, $lo);
	}
	//ok
	public function dailySurfbarBonus($db, $content){
		$content = (int)$content;
		if($content<100){ return 0; }
		if($content>=100 && $content<500){ return 100; }
		if($content>=500 && $content<1000){ return 500; }
		if($content>=1000 && $content<5000){ return 1000; }
		if($content>=5000){ return 10000; }
	}
	//NON
	public function monthlySurfbarBonus($db, $mainObject, $id){
		if(!$query=$db->prepare("SELECT member_type FROM {$mainObject->usertbl()} WHERE id=?", array($id))){ return 5/100; }
		if(!$pe=$query->fetch(PDO::FETCH_ASSOC)["member_type"]){ return 5/100; }
		if($pe == 3){ return 10/100; } 
		if($pe == 2){ return 25/100; } 
		if($pe == 1){ return 50/100; }
		return 5/100;
	}
	
	public function getReferralsPrice($db, $mainObject, $rid){
		$rid = (int)$rid;
		if(!$query=$db->prepare("SELECT member_type FROM {$mainObject->usertbl()} WHERE id=?", array($rid))){ return false; }
		if($query->rowCount() <= 0){ return false; }
		$gr = (int)$query->fetch(PDO::FETCH_ASSOC)["member_type"];
		if($gr == 3){ return 5000; } 
		if($gr == 2){ return 8000; } 
		return 2000;
	}
	
	public function lostReferralsPrice($db, $mainObject, $rid, $points_for_referer){
		//A l'achat , le prix du filleuls baisse immédiatement 
		// a 95%. Si en plus de cela il fait gagner au parrain 95% de son prix
		// alors le parrain ne gagnera plus rien lorsqu'il va le libérer.
		$rid = (int)$rid;
		if(!$gr = $this->getReferralsPrice($db, $mainObject, $rid)){ return 0; }
		if((int)$points_for_referer >= $gr*0.95){ return 0; } 
		return ($gr*0.95) - (int)$points_for_referer;
	}
	
	public function newStats_display($v){
		if($v){ echo " ~ <strong class='badge'>". (($v > 0) ? "+".(int)$v : (int)$v)  . "</strong>"; } 
	}
	
}



