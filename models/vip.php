<?php

class vip {
	
	private $table;
	private $adminVipTbl;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->vip_tbln();
		$this->adminVipTbl = $tablesObj->adminVip_tbln();
	}
	
	public function formulesList($db, $key=false){
		if(!$query = $db->prepare("SELECT * FROM {$this->adminVipTbl} ORDER BY price", array())){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas = $query->fetchAll(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$key){ return $datas; }
		foreach($datas as $k=>$v){
	   	 if($key == $v["id"]){ return $v; }
		}
	}
	
	public function vipCode($price){
		$price = (int)$price;
		if($price == 8000){ return 3; }
		if($price == 15000){ return 2; }
		return false;
	}
	
	public function vipExpiration($db, $sessionObj){
		$id = $sessionObj->values("member_type");
		if($id > 3 && $id < 2){ return "++"; }
		if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=?", [$sessionObj->values("id")])){ return "++"; }
		if($query->rowCount() <= 0){ return "++"; }
		$datas=$query->fetch(PDO::FETCH_ASSOC);
		return "<strong class='text-danger'>".date("d/m/y H:i", ($datas["add_at"] + $datas["timeout"])) . "</strong>";
	}
	
	public function upgrade($db, $mainObject, $sessionObj, $rewardObj, $errors){
		if(empty($mainObject->postAll("upgrade"))){ return false; }
		if((int)$sessionObj->values("member_type") == 1){ $errors->setFlash("user.upgrade", array("type"=>0, "message"=>"Admin is not authorized for this action")); }
		if(!is_numeric($mainObject->postAll("formule_id")) OR $mainObject->postAll("formule_id") < 0  OR $mainObject->postAll("action")!="CONFIRM_PROCESS" OR empty($mainObject->postAll("PAYOUT")) OR empty($mainObject->postAll("OVERFLY"))){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		$datas = $this->formulesList($db, $mainObject->postAll("formule_id"));
		if(!$datas OR empty($datas)){ $errors->_throw($errors->get($db, "ERROR_INVALID_VIP_FORMULE")); }
		if(!$query=$db->prepare("SELECT points, refererid FROM {$mainObject->usertbl()} WHERE id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$d=$query->fetch(PDO::FETCH_ASSOC);
		if($d["points"] < $datas["price"]){ $errors->_throw($errors->get($db, "ERROR_NO_ENOUGHT_POINTS")); }
		$refererid = !empty($d["refererid"]) ? (int)$d["refererid"] : false;
		if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		
		if($query->rowCount() > 0){
	   	 if(!$vip = $query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($vip["value"] > $datas["price"]){ $errors->_throw($errors->get($db, "ERROR_LOW_VIP_FORMULE")); }
	   	 if(!$db->prepare("UPDATE {$this->table} SET name=?, value=?, timeout=?, add_at=? WHERE user_id=?", array($datas["name"], $datas["price"], $datas["timeout"], time(), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		} else {
	   	 if(!$db->prepare("INSERT INTO {$this->table} SET name=?, value=?, timeout=?, add_at=?, user_id=?", array($datas["name"], $datas["price"], $datas["timeout"], time(), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($refererid){
	   		 if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", array($points=$datas["price"]*$rewardObj->userToVip_RefererRewardPercentage($db, $mainObject, $refererid, $errors), $refererid))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   		 $mainObject->referralsRewardForReferer($db, $sessionObj->values("id"), $points, $errors);
	   	 }
		}
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points-?, member_type=? WHERE id=?", array($datas["price"], $this->vipCode($datas["price"]), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		
		if(!$query=$db->prepare("SELECT * FROM {$mainObject->usertbl()} WHERE id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$_SESSION["accounts"]["member_type"] = $query->fetch(PDO::FETCH_ASSOC)["member_type"]; 
		
		$mainObject->setHistory($db, array("name"=>"Vip", "content"=>"Congratulations!!! . You are vip.", "at"=>time()), str_replace("vip_", "", $this->table), $sessionObj);
		$errors->setFlash("user.home", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function upgradeClean($db, $mainObject){
		if(!$query=$db->prepare("SELECT id, user_id FROM {$this->table} WHERE add_at+timeout <= ?", array(time()))){ return false; }
		if($query->rowCount() <= 0){ return true; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) AS $infos){
	   	 if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET member_type=? WHERE id=?", [4, (int)$infos["user_id"]])){ return false; }
	   	 if(!$db->prepare("DELETE FROM {$this->table} WHERE id=?", array($infos["id"]))){ return false; }
		}
		if(!$query=$db->prepare("SELECT id FROM {$this->table}")){ return false; }
		if($query->rowCount() <= 0){ $db->tblTruncate($this->table); }
		return true;
	}
}