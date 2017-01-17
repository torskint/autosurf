<?php

class sess {
	
	public $table;
	private $MAXIMUM_TOTAL_OFFLINE_TTS=12960000; //5 mois d'inactivité supprime le compte
	private $MAXIMUM_OFFLINE_TTS=3600; //1h d'inactivité supprime la session
	private $location=null;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->sessions_tbln();
		$this->tables_instance = $tablesObj;
		
		$this->location = !empty($_GET["src"]) ? $_GET["src"] : "main.home";
		$ua = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "$##";
		$ip = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "$##";
		$empreintes = md5($ua)."_".md5($ip);
		
		if(!isset($_COOKIE["TRACK-ID"]) OR !isset($_COOKIE["PHPSESS-ID"])){
	   	 setCookie("TRACK-ID", $empreintes, time()+7*24*3600, "/");
	   	 setCookie("PHPSESS-ID", bin2hex(openssl_random_pseudo_bytes(64))."_TORSKINT", time()+7*24*3600, "/");
		}
		$this->trackid = !empty($_COOKIE["TRACK-ID"]) ? $_COOKIE["TRACK-ID"] : null;
		$this->phpsessid = !empty($_COOKIE["PHPSESS-ID"]) ? $_COOKIE["PHPSESS-ID"] : null;
	}
	
	public function values($field, $key="accounts"){
		return (isset($_SESSION[$key][$field]) && !empty($_SESSION[$key][$field])) ? $_SESSION[$key][$field] : false;
	}
	
	private function cleanExpiredAccounts($db, $tablesObj){
		$query=$db->prepare("SELECT user_id FROM {$this->table} WHERE last_at < ?", array(time()-$this->MAXIMUM_TOTAL_OFFLINE_TTS));
		if($query->rowCount() <= 0){ return true; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $user_id){
	   	 foreach($tablesObj->tblArray() as $tbl){
	   		 $db->prepare("DELETE FROM {$tbl} WHERE user_id=?", array((int)$user_id));
	   	 }
	   	 $db->prepare("DELETE FROM {$mainObject->usertbl()} WHERE id=?", array((int)$user_id));
		}
		return true;
	}
	
	public function refresher($db, $userdatas, $mainObject, $errors){
		$this->cleanExpiredAccounts($db, $this->tables_instance); $session_id = $mainObject->key(20);
		if(!in_array($userdatas["member_type"], $mainObject->access_interval())){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCESS_DENIED")); }
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=?", array($userdatas["id"]))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() > 0){
	   	 if(!$db->prepare("UPDATE {$this->table} SET session_id=?, trackid=?, phpsessid=?, session_at=?, last_at=? WHERE user_id=?", array($session_id, $this->trackid, $this->phpsessid, time(), time(), $userdatas['id']))){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
		} else {
	   	 if(!$query=$db->prepare("SELECT id FROM {$this->table}")){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($query->rowCount() <= 0){ $db->tblTruncate($this->table); }
	   	 if(!$db->prepare("INSERT INTO {$this->table}(user_id, session_id, trackid, phpsessid, session_at, last_at) VALUES(?, ?, ?, ?, ?, ?)", array($userdatas["id"], $session_id, $this->trackid, $this->phpsessid, time(), time()))){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
		}
		unset($userdatas["points_for_referer"]);
		$_SESSION["accounts"] = array_merge($userdatas, array("session_id"=>$session_id, "trackid"=>$this->trackid, "phpsessid"=>$this->phpsessid));
		$home = ($this->values("member_type") == 1) ? "admin.home" : "user.home";
		if(!isset($_SESSION["first_login"])){
	   	 $mainObject->setHistory($db, array("name"=>"Session", "content"=>"You are logged on our site.", "at"=>time()), $this, $this);
	   	 $errors->setFlash($home, ["message"=>$errors->get($db, "SUCCESS_LOGIN")["message"] ." - <span style='color:#FF9900;'>". $_SESSION["accounts"]["username"]. "</span>", "type"=>$errors->get($db, "SUCCESS_LOGIN")["type"]]);
		}
		$mainObject->setHistory($db, array("name"=>"First session", "content"=>"<span class='bold'> Your are logged on our site for the first time. Please read our <a href=\"?src=main.terms\"> Terms </a> .</span>", "at"=>time()), $this, $this);
		unset($_SESSION["first_login"]);
		$errors->setFlash($home, $errors->get($db, "SUCCESS_FIRST_LOGIN"));
	}
	
	private function access($db, $mainObject, $tablesObj, $vipObj, $errors, $adminOnly=false){
		$laws = ($adminOnly) ? 1 : 4;
		if((int)$this->values("member_type")>$laws OR $this->values("account_is_validate")!=1 OR $this->values("account_validation_key")!=NULL){ return false; }
		/*
		$query=$db->prepare("SELECT * FROM {$mainObject->usertbl()}
		INNER JOIN {$this->table} ON {$mainObject->usertbl()}.id={$this->table}.user_id WHERE {$mainObject->usertbl()}.id=?", array($this->values("id")));
		if($query->rowCount() <= 0){ return false; }
		if(!$datas = $query->fetch(PDO::FETCH_ASSOC)){ return false; }
		if($datas["email"]!=$this->values("email")){ return false; }
		if($datas["member_type"]!=$this->values("member_type") && $datas["member_type"] <= $laws){ $_SESSION["accounts"]["member_type"]=$datas["member_type"]; }
		if(!$this->values("session_id") OR $this->values("session_id")!=$datas["session_id"] OR (count(explode(md5("$##"), $this->trackid)) > 1) OR $datas["trackid"]!=$this->trackid OR $datas["phpsessid"]!=$this->phpsessid OR ($last_at = time() - $datas["last_at"]) > $this->MAXIMUM_OFFLINE_TTS){ return false; }
		if(!$db->prepare("UPDATE {$this->table} SET last_at=?, tts=?, location=? WHERE user_id=?", array(time(), $last_at, $this->location, $this->values("id")))){ return false; }
		*/
		# CLEAN EXPIRED VIP
		if(!$vipObj->upgradeClean($db, $mainObject)){ return false; }
		# CLEAN EXPIRED MESSAGES
		$messenger = new messenger($tablesObj);
		if(!$messenger->cleanExpired_messages($db)){ return false; }
		# CLEAN EXPIRED ACCOUNT
		$this->cleanExpiredAccounts($db, $tablesObj);
		# CLEAN EXPIRED BANISHMENT
		if(!$mainObject->banishment_expireCleaner($db) ){ return false; }
		# VERIFIE SI LE MEMBRE CONNECTE SEST FAIT BANNIR
		if($mainObject->isBanned($db, $this->values("id"))){ return false; }
		# ACTIVITES CRON JOB AUTOMATIQUE SIMULATOR
		if(!$tablesObj->cronJobExec($db, $mainObject)){ return false; }
		
		#if(!$db->prepare("UPDATE {$tablesObj->sites_tbln()} SET last_views_at=? WHERE  last_views_at=?", [time(), 0])){ return false; }
		if(!$db->prepare("UPDATE {$tablesObj->sites_tbln()} SET views_for_hour_full=? WHERE  last_views_at<=? && views_for_hour_full=views_for_hour", [0, time()-3600])){ return false; }
		
		return true;
	}
	
	public function autorized($db, $mainObject, $tablesObj, $vipObj, $errors, $adminOnly=false){
		if(!$this->access($db, $mainObject, $tablesObj, $vipObj, $errors, $adminOnly)){
	   	 $errors->_rdr("main.logout&timeout=1"); exit;
		}
	}
}