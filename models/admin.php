<?php

class admin {
	
	private $textTbl;
	private $newsletterTbl;
	private $errorsTbl;
	private $tblRowsIndex=[];
	
	public function __construct($tablesObj){
		$this->textTbl = $tablesObj->admin_text_tbln();
		$this->errorsTbl = $tablesObj->admin_errors_tbln();
		$this->newsletterTbl = $tablesObj->admin_newsletter_tbln();
	}
	
	public function textTbl(){
		return $this->textTbl;
	}
	
	public function errorsTbl(){
		return $this->errorsTbl;
	}
	
	public function text($db, $name, $key=false){
		if(!$query=$db->prepare("SELECT message, access FROM {$this->textTbl} WHERE name=?", [$name])){ return false; }
		if($query->rowCount() <= 0){ return "< $name ? >"; }
		if(!$datas = $query->fetch(PDO::FETCH_ASSOC)){ return false; }
		return (!$key) ? stripcslashes (html_entity_decode($datas["message"])) : (int)$datas["access"];
	}
	
	public function updateTXT($db, $mainObject, $name, $url, $errors){
		if(empty($mainObject->postAll("contents"))){ return false; } 
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		$message = htmlentities($mainObject->postAll("contents"), ENT_QUOTES, "UTF-8");
		if(!$db->prepare("UPDATE {$this->textTbl} SET message=?, add_at=? WHERE name=?", [$message, time(), $name])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }   
		$errors->setFlash("admin.{$url}", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function topStats($db, $table, $key, $limit=10){
		$options = (trim($key) == "points") ? "member_type!=1 && account_is_validate=1 && account_validation_key IS NULL && " : "";
		if(!$query=$db->prepare("SELECT * FROM {$table} WHERE {$options} $key > ?  ORDER BY {$key} DESC LIMIT 0, {$limit}", [0])){}
	   	 if($query->rowCount() <= 0){ return []; }
		return array_merge($query->fetchAll(PDO::FETCH_ASSOC), [0=>["table"=>$table]]);
	}
	
	public function top10OfMonthReward($db, $mainObject, $datas, $row){
		if(count($datas) <= 0){ return false; }
		$table = $datas[count($datas)-1]["table"];
		unset($datas[count($datas)-1]);
		foreach($datas as $k=>$infos){
	   	 if(!$db->prepare("UPDATE {$table} SET $row=$row+? WHERE id=?", [$bounce = (100 - ($k*10)), $infos["id"]])){ return false; }
	   	 if($table == "sites"){
	   		 $mainObject->setHistory($db, array("name"=>"Sites :: Top 10 Bonus", "content"=>"Bravo. You have received <span class='bold'>" .number_format($bounce)."</span> credits for surf bar. Your site is top 10 this month.", "at"=>time()), $table, $infos["user_id"]);
	   	 } else {
	   		 $mainObject->setHistory($db, array("name"=>"User :: Top 10 Bonus", "content"=>"Bravo. You have received <span class='bold'>" .number_format($bounce)."</span> credits. You are top 10 this month.", "at"=>time()), $table, $infos["id"]);
	   	 }
		}
		return true;
	}
	
	public function newsletter($db, $mainObject, $sessionObj, $errors){ $j=[];
		if(empty($mainObject->postAll())){ return false; }
		if(!$subject=$mainObject->post("subject_newsletter") OR !$message=$mainObject->post("message_newsletter")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }	   	   	
  	  if(!$mainObject->valid("subject_newsletter", "message_newsletter")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$query=$db->prepare("SELECT username, email FROM {$mainObject->usertbl()} WHERE member_type <= ? && account_is_validate=?", [4, 1])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetchAll(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		foreach($datas as $k=>$infos){
	   	 if(!$mail->send(array_merge($infos, ["subject"=>htmlentities($subject, ENT_QUOTES, "UTF-8"), "message"=>htmlentities($message, ENT_QUOTES, "UTF-8")]))){ $j[]=$k; }
		}
		if(count($k) > 0){
	   	 $mainObject->setHistory($db, array("name"=>"Newsletter", "content"=>count($k)." posts have been sent on a total (< 100%)".count($datas), "at"=>time()), $mainObject, $sessionObj);
	   	 $errors->_throw($errors->get($db, "ERROR_NEWSLETTER_NOT_SENT"));
		}
		$mainObject->setHistory($db, array("name"=>"Newsletter", "content"=>"Success !!! All posts have been sent on a total (100%)", "at"=>time()), $mainObject, $sessionObj);
		$errors->setFlash("admin.newsletter", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function errors($db, $name, $key=false){
		if(!$query=$db->prepare("SELECT message, access FROM {$this->errorsTbl} WHERE name=?", [$name])){ return false; }
		if($query->rowCount() <= 0){ return []; }
		if(!$datas = $query->fetch(PDO::FETCH_ASSOC)){  return false; }
		return (!$key) ? html_entity_decode($datas["message"]) : (int)$datas["access"];
	}
	
	public function getErrors($db){
		if(!$query=$db->prepare("SELECT * FROM {$this->errorsTbl} ORDER BY name", [])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function updateERRORS($db, $mainObject, $id, $url, $errors){
		if(empty($mainObject->postAll())){ return false; } 
		if(!$mainObject->valid("message_errors")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if(!$db->prepare("UPDATE {$this->errorsTbl} SET message=?, class_html=?, add_at=? WHERE id=?", [htmlentities($mainObject->post("message_errors"), ENT_QUOTES, "UTF-8"), $mainObject->post("class_html"), time() , $id])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }   
		$errors->setFlash("admin.{$url}", $errors->get($db, "SUCCESS_DONE"));
	}
	
	private function orderby($db, $mainObject){
		$query=$db->prepare("SELECT email, websites_visited, signup_at FROM {$mainObject->usertbl()} ORDER BY id DESC LIMIT 1");
		$this->tblRowsIndex = array_keys($query->fetch(PDO::FETCH_ASSOC));
		$flag = !empty($mainObject->postAll("flag_id")) ? $mainObject->postAll("flag_id") : "DESC";
		return !empty($mainObject->postAll("orderBy")) ? $this->tblRowsIndex[(int)$mainObject->postAll("orderBy")] . " " . $flag : $this->tblRowsIndex[2] . " " . $flag;
	}
	
	public function flags(){
		return $this->tblRowsIndex;
	}
	
	public function getAllUsers($db, $mainObject, $errors, $member_type){
		if(!$query=$db->prepare("SELECT * FROM {$mainObject->usertbl()} WHERE member_type=? ORDER BY {$this->orderBy($db, $mainObject)}", [$member_type])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function crediteUser($db, $mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll())){ return false; }
		if(!preg_match("/^[a-z0-9]{32}$/i", $mainObject->postAll("target")) || !preg_match("/^[0-9-]{1,9}$/i", $mainObject->postAll("points")) || $mainObject->postAll("target")==md5($sessionObj->values("email"))){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!is_numeric($points=$mainObject->postAll("points"))){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if((int)$sessionObj->values("member_type") >= 2) { $points = abs($points); }
		$email = $mainObject->postAll("target");
		$points = intval($points);
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE md5(email)=?", [$points, $email])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", [-($points), $sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$errors->setFlash("user.user-pt", $errors->get($db, "SUCCESS_DONE"));
	}
	
}

