<?php

class user {
	
	private $usertbl;
	private $countryTbl;
	private $tables_instance;
	
	public function __construct($tablesObj){
		$this->tables_instance = $tablesObj;
		$this->usertbl = $tablesObj->user_tbln();
		$this->countryTbl = $tablesObj->admin_country_tbln();
	}
	
	public function count($db){
		if(!$query=$db->prepare("SELECT id FROM {$this->usertbl} WHERE account_is_validate=? && member_type <=?", array(1, 4))){ return false; }
		return $query->rowCount();
	}
	
	public function newUsers($db, $limit=10){
		if(!$query = $db->prepare("SELECT uniqid, refererid FROM {$this->usertbl} JOIN uniqid WHERE id=refererid ORDER BY signup_at DESC LIMIT ?", array($limit))){ return false; }
		return $query;
	}
	
	public function editEmail($db, $mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll("update_email_form"))){ return false; }
		if(!$mainObject->valid("email_update", "password_update_email")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		$query=$db->prepare("SELECT id FROM {$this->usertbl} WHERE email=?", array($mainObject->post("email_update")));
		if($query->rowCount() > 0){ $errors->_throw($errors->get($db, "ERROR_EMAIL_UNAVAILABLE")); }
		if(!$query=$db->prepare("SELECT password FROM {$this->usertbl} WHERE id=?", [(int)$sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$mainObject->pwdverify($mainObject->post("password_update_email"), $datas["password"])){ $errors->_throw($errors->get($db,"ERROR_BAD_IDS")); }
		if(!$db->prepare("UPDATE {$this->usertbl} SET email=? WHERE id=? && email=?", array($mainObject->post("email_update"), (int)$sessionObj->values("id"), $sessionObj->values("email")))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		$errors->setFlash("main.home", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function editPassword($db, $mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll("update_password_form"))){ return false; }
		if(!$mainObject->valid("password_new", "password_new_confirm", "password_actual")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if($mainObject->post("password_new")!=$mainObject->post("password_new_confirm")){ $errors->_throw($errors->get($db, "ERROR_PASSWORD_NOT_CONFIRMED")); }
		if($mainObject->post("password_new")==$mainObject->post("password_actual") ){ $errors->_throw($errors->get($db, "ERROR_OLD_EQUAL_NEW_PASSWORD")); }
		if(!$query=$db->prepare("SELECT password FROM {$this->usertbl} WHERE id=?", [(int)$sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$mainObject->pwdverify($mainObject->post("password_actual"), $datas["password"])){ $errors->_throw($errors->get($db,"ERROR_INVALID_FIELD") , 0); }
		if(!$db->prepare("UPDATE {$this->usertbl} SET password=? WHERE id=? && password=? && email=?", array($mainObject->post("password_new"), $sessionObj->values("id"), $mainObject->post("password_actual"), $sessionObj->values("email")))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		$errors->setFlash("main.home", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function delete($db, $mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll("delete_account"))){ return false; }
		if(!$mainObject->valid("password_delete")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD") ); }
		if(!$query=$db->prepare("SELECT password FROM {$this->usertbl} WHERE id=?", [(int)$sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$mainObject->pwdverify($mainObject->post("password_delete"), $datas["password"])){ $errors->_throw($errors->get($db,"ERROR_BAD_IDS")); }
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if($sessionObj->values("member_type") == 1){
	   	 if(!$query=$db->prepare("SELECT id FROM {$this->usertbl} WHERE member_type=?", [1])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($query->rowCount()<= 100){ $errors->setFlash("user.delete", ["type"=>3, "message"=>"This only admin would not be deleted."]); }
		}
		foreach($this->tables_instance->tblArray() as $tbl){
	   	 if(!$db->prepare("DELETE FROM {$tbl} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		}
		if(!$db->prepare("UPDATE {$this->usertbl} SET refererid=?, points_for_referer=? WHERE refererid=?", array(NULL, 0, $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$db->prepare("DELETE FROM {$this->usertbl} WHERE email=? && id=?", array($sessionObj->values("email"), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		session_destroy(); session_start();
		$errors->setFlash("main.home", $errors->get($db, "SUCCESS_ACCOUNT_DELETED"));
	}
	
	public function userDatas($db, $sessionObj, $errors){
		if(!$query =$db->prepare("SELECT * FROM {$this->usertbl} WHERE id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return []; }
		$datas=$query->fetch(PDO::FETCH_ASSOC);
		return is_array( $datas ) ? $datas : array();
	}
	
	public function countActiveReferrals($db, $sessionObj, $errors){
		if(!$query = $db->prepare("SELECT id FROM {$this->usertbl} WHERE refererid=? && member_type<=? && account_is_validate=?", array($sessionObj->values("id"), 4, 1))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		return $query->rowCount();
	}
	
	public function myReferralsList($db, $sessionObj, $errors){
		if(!$query = $db->prepare("SELECT * FROM {$this->usertbl} WHERE refererid=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function idToName($db, $id){
		if(!$query = $db->prepare("SELECT username FROM {$this->usertbl} WHERE id=?", array((int)$id))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return "--- ---"; }
		return $query->fetch(PDO::FETCH_ASSOC)["username"];
	}
	
	public function idToCountry($db, $id){
		if(!$query = $db->prepare("SELECT * FROM {$this->countryTbl} WHERE id=?", array((int)$id))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return "--- ---"; }
		$flag = $query->fetch(PDO::FETCH_ASSOC);
		return "<img src='data:image/png;base64," .base64_encode($flag['flag'])."' alt='".$flag['country']."'/>";
	}
	
	public function accountType($lw){
		$lw = (int)$lw;
		if($lw == 4){ return "FREE"; }
		else if($lw == 3){ return "SILVER"; }
		else if($lw == 2){ return "GOLD"; }
		else if($lw == 1){ return "ADMIN"; }
		else if($lw == 998){ return "BANNED"; }
		else { return "wait..."; }
	}
	
	public function total_referer_referrals_points($db, $sessionObj){
		if(!$query=$db->prepare("SELECT points_for_referer FROM {$this->usertbl} WHERE refererid=?", [(int)$sessionObj->values("id")])){ return 0; }
		if($query->rowCount() <= 0){ return 0; }
		$total_points_for_referer = 0;
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $re){
	   	 $total_points_for_referer += $re["points_for_referer"];
		}
		return $total_points_for_referer;
	}
	
	public function getStatistics($db, $status, $type=false, $return_all=false){ $d = [];
		$query=$db->prepare("SELECT id, member_type, account_is_validate FROM {$this->usertbl}");
		if($query->rowCount() <= 0){ return 1; }
		foreach($all=$query->fetchAll(PDO::FETCH_ASSOC) as $infos){
	   	 if($infos["account_is_validate"] == $status){
	   		 if(!$type){ $d[] = $infos["id"]; }
	   		 else {
	   	   	  if($infos["member_type"] == $type){ $d[] = $infos["id"]; }
	   		  }
	   	 }
		}
		return (!$return_all) ? count($d) : count($all);
	}
}