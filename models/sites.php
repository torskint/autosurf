<?php

class sites {
	
	public $table;
	private $blacklist=2;
	private $status=1;
	private $not_validate=0;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->sites_tbln();
	}
	
	public function sitestbl(){
		return $this->table;
	}
	
	public function mysites($db, $sessionObj){
		if(!$sites = $db->prepare("SELECT `id` FROM `sites` WHERE `user_id`=? && `status`=?", array($sessionObj->values("id"), 1))){ return false; }
		return $sites;
	}
	
	public function allPendingSites($db){
		if(!$sites = $db->prepare("SELECT * FROM {$this->table} WHERE status=?", array(0))){ return false; }
		if($sites->rowCount() <= 0){ return []; }
		return $sites->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public  function siteInfos($url, $key){
		$i = get_meta_tags($url);
		if(!$i[$key]){return false; }
		return $i[$key];
	}
	
	public function getTitle($url){
		//file get contents error 
		$i=preg_match("/<title>(.+)<\/title>/", @file_get_contents($url), $matches);
		return isset($matches[1]) ? strip_tags($matches[1]) : "Not found";
	}
	
	public function delete($db, $mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll("delete"))){ return false; }
		if(!is_numeric($mainObject->postAll("site")) OR $mainObject->postAll("site")<=0){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); } 
		if(!$db->prepare("DELETE FROM {$this->table} WHERE user_id=? && id=?", array($sessionObj->values("id"), $mainObject->postAll("site")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$mainObject->setHistory($db, array("name"=>"Site deleted", "content"=>"Your are deleted an site." , "at"=>time()), $this->table, $sessionObj);
		$errors->setFlash("user.sites-de",  $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function pendingSiteDelete($db, $mpObj, $mainObject, $sessionObj, $errors){
		if(!is_numeric($mainObject->postAll("psite_id")) OR $mainObject->postAll("pending_action")!="DELETE" OR !is_numeric($mainObject->postAll("ownerUserID"))){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->valid("action")){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_CONFIRM_ACTION")); }
		//mp to site owner
		$sitename = $this->idToSite($db, $mainObject->postAll("psite_id") , $errors);
		$mpObj->send_auto($db, array("receiver_id"=>(int)$mainObject->postAll("ownerUserID"), "site"=>$sitename), "mp_pendingSiteDeleted");
		if(!$db->prepare("DELETE FROM {$this->table} WHERE user_id=? && id=?", array($mainObject->postAll("ownerUserID"), $mainObject->postAll("psite_id")))){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_SQL_ERROR")); }
		$mainObject->setHistory($db, array("name"=>"Pending Site deleted", "content"=>"You have deleted the site No. {". $mainObject->postAll("psite_id") . "}" , "at"=>time()), $this->table, $sessionObj);
		$errors->setFlash("user.pending-sites", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function pendingSiteActivate($db, $mpObj, $mainObject, $sessionObj, $errors){
		if(!is_numeric($mainObject->postAll("psite_id")) OR $mainObject->postAll("pending_action")!="ACTIVATE" OR !is_numeric($mainObject->postAll("ownerUserID"))){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->valid("action")){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_CONFIRM_ACTION")); } 
		if(!$db->prepare("UPDATE {$this->table} SET status=? WHERE user_id=? && id=?", array(1, (int)$mainObject->postAll("ownerUserID"), (int)$mainObject->postAll("psite_id")))){ $errors->setFlash("user.pending-sites", $errors->get($db, "ERROR_SQL_ERROR")); }
		$mainObject->setHistory($db, array("name"=>"Pending Site enabled", "content"=>"You have enabled the site. No. {". $mainObject->postAll("psite_id") . "}" , "at"=>time()), $this->table, $sessionObj);
		//mp to site owner
		$sitename = $this->idToSite($db, $mainObject->postAll("psite_id") , $errors);
		$mpObj->send_auto($db, array("receiver_id"=>(int)$mainObject->postAll("ownerUserID"), "site"=>$sitename), "mp_pendingSiteActivated");
		$errors->setFlash("user.pending-sites", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function getEditDatasFromSiteslist($db, $mainObject, $sessionObj, $errors){
		if(empty($_POST["site_id"])){ return false; }
		$id= (int)$_POST["site_id"];
		if(!is_numeric($id) || $id <= 0){ $errors->setFlash("user.sites", $errors->get($db, "ERROR_SITE_NOT_EXIST")); }
		if(!$query=$db->prepare("SELECT id, name, url FROM {$this->table} WHERE user_id=? && id=?", array($sessionObj->values("id"), $id))){ $errors->setFlash("user.sites", $errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("user.sites", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ $errors->setFlash("user.sites", $errors->get($db, "ERROR_SITE_NOT_EXIST")); }
		return $datas;
	}
	
	public function edit($db, $mainObject, $sessionObj, $abuseObj, $errors){
		if(empty($mainObject->postAll("edit_site"))){ return false; }
		if(!$mainObject->valid("name")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->url($db, $this, $abuseObj, $errors)){ $errors->_throw($errors->get($db, "ERROR_SITE_UNAVAILABLE")); }
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		if(!$db->prepare("UPDATE {$this->table} SET name=?, status=?, url=?, add_at=? WHERE user_id=? && id=?", array($mainObject->post("name"), 0, $mainObject->post("url"), time(), $sessionObj->values("id"), $_SESSION["site_infos"]["id"]))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		unset($_SESSION["site_id"]);
		$errors->setFlash("user.sites", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function add($db, $mainObject, $sessionObj, $rewardObj, $abuseObj, $errors){
		if(empty($mainObject->postAll("add_site"))){ return false; }
		if(!$mainObject->valid("name")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if(!$mainObject->url($db, $this, $abuseObj, $errors)){ $errors->_throw($errors->get($db, "ERROR_SITE_UNAVAILABLE")); }
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() >= $rewardObj->reward($db, "sites_authorized", $errors, $sessionObj->values("member_type"))){ $errors->_throw($errors->get($db, "ERROR_SITE_BRANDWITCH_EXCEEDED")); }
		if(!$mainObject->valid("action")){ $errors->_throw($errors->get($db, "ERROR_CONFIRM_ACTION")); }
		#if($sessionObj->values("member_type") < 1){ $status=1; } else { $status=0; }
		$status = 0;
		if(!$db->prepare("INSERT INTO {$this->table} SET name=?, status=?, url=?, last_views_at=?, add_at=?, user_id=?", array($mainObject->post("name"), $status, $mainObject->post("url"), NULL, time(), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$errors->setFlash("user.sites", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function display($db, $sessionObj){
		$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")));
		if($query->rowCount() > 0){
	   	 return $query->fetchAll(PDO::FETCH_ASSOC);
		}
	   return [];
	}
	
	public function transfertPointsToAnotherUser($db, $main, $session, $abuses, $errors){
		
	}
	
	public function allowPointsToUrls($db, $mainObject, $sessionObj, $abuses, $errors){
		if(empty($mainObject->postAll("credite_site"))){ return false; }
		if(!is_numeric($mainObject->postAll("site_id")) OR !is_numeric($mainObject->postAll("site_points"))){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		$p = (int)$mainObject->postAll("site_points");
		$vh = (preg_match("/^[0-9]{1,3}$/", $mainObject->postAll("site_visits_by_hour") && $mainObject->postAll("site_visits_by_hour") > 4 && $mainObject->postAll("site_visits_by_hour") <501)) ? $mainObject->postAll("site_visits_by_hour") : 20;
		$points = ($sessionObj->values("member_type")<=2) ? $p : abs($p);
		//  PARRAINAGE BONUS
		if($sessionObj->values("member_type") <= 3){ $referer_part=0; } else { $referer_part=$points*0.05; }
		if($points > 0){
	   	 if(!$query=$db->prepare("SELECT points FROM {$mainObject->usertbl()} WHERE id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($query->fetch(PDO::FETCH_ASSOC)["points"] < $points){ $errors->_throw($errors->get($db, "ERROR_TRANSACTION_NOT_AUTHORIZED")); }
		}
		if($points < 0){
	   	 if(!$query=$db->prepare("SELECT actual_surfbar_points FROM {$this->table} WHERE id=? && user_id=?", array($mainObject->postAll("site_id"), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($query->fetch(PDO::FETCH_ASSOC)["points"] > $points){ $errors->_throw($errors->get($db, "ERROR_TRANSACTION_NOT_AUTHORIZED")); }
		}
		if(!$db->prepare("UPDATE {$this->table} SET actual_surfbar_points=actual_surfbar_points+?, views_for_hour_full=?, views_for_hour=?, last_views_at=? WHERE user_id=? && id=?", array($points-$referer_part, $vh, $vh, time() , $sessionObj->values("id"), $mainObject->postAll("site_id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", array(-($points), $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($points >= 10000){
	   	 if(!$query=$db->prepare("SELECT refererid FROM {$mainObject->usertbl()} WHERE id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 $refererid = $query->fetch(PDO::FETCH_ASSOC)["refererid"];
	   	 if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", array($points=$referer_part , $refererid))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	   	 $mainObject->referralsRewardForReferer($db, $refererid, $points, $errors);
		}
		$mainObject->setHistory($db, array("name"=>"Url credited", "content"=>"Your have credited an url.", "at"=>time()), $this, $sessionObj);
		$errors->setFlash("user.sites-pt", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function totalVisitsOnMySites($db, $sessionObj, $errors){
		if(!$query =$db->prepare("SELECT total_views_receive FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetchAll(PDO::FETCH_ASSOC)){ return 0; }
		$totalVisits = 0;
		foreach($datas as $k => $v){
	   	 $totalVisits = $totalVisits + $v["total_views_receive"];
		}
		return $totalVisits;
	}
	
	public function idToUrl($db, $site_id , $errors){
		if(!$query=$db->prepare("SELECT url FROM {$this->table} WHERE id=?", array($site_id))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return 'not found'; }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		return $datas["url"];
	}
	
	public function idToSite($db, $site_id , $errors){
		if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE id=?", array($site_id))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetch(PDO::FETCH_ASSOC);
	}
}