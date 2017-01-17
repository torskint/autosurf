<?php

class surfbar extends surf {
	
	public $table;
	private $limite_minimum=1;
	private $active_url_status=1;
	private $views_for_hour_full_status = 1;
	private $surfbar_point_get=0.5; 
	private $surfbar_point_lost=1;
	private $surfbar_status=true;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->surfbar_tbln();
	}
	
	public function surfbarTbl(){
		return $this->table;
	}
	
	public function surfbar($db, $abuseObj, $sitesObj, $sessionObj, $errors){
		if(!$query1=$db->prepare("SELECT content FROM {$abuseObj->blacklistTbl()} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		$fetch_contents1=$query1->fetch(PDO::FETCH_ASSOC)["content"];
		$contents1=!empty($fetch_contents1) ? unserialize($fetch_contents1) : array();
		$queryString="";
		$datas1=array();
		if(!empty($contents1)){
	   	 foreach($contents1 AS $key1=>$c1){ 
	   		 $queryString .= "&& id!=? ";
	   		 $datas1[] = $c1["site_id"];
	   	 }
		}
		$datas2=array();
		if(!$query2=$db->prepare("SELECT content FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		$fetch_contents2=$query2->fetch(PDO::FETCH_ASSOC)["content"];
		$contents2=!empty($fetch_contents2) ? unserialize($fetch_contents2) : array();
		if(!empty($contents2) && !is_null($contents2) && count($contents2) > 0){
	   	 foreach($contents2 AS $key2=>$c2){ 
	   		 $queryString .= "&& id!=? ";
	   		 $datas2[] = $c2["site_id"];
	   	 }
		}
		$string = " " . trim($queryString);
		$query=$db->prepare("SELECT * FROM {$sitesObj->sitestbl()} WHERE status=? && actual_surfbar_points>=? && views_for_hour_full < views_for_hour && user_id!=?{$string}", array_merge(array($this->active_url_status, $this->limite_minimum, $sessionObj->values("id")), $datas1, $datas2));
		if($query->rowCount() > 0){
	   	 $j = rand(0, $query->rowCount()-1);
	   	 return $site=$query->fetchAll(PDO::FETCH_ASSOC)[$j];
		} else {
	   	 $errors->setFlash("user.home", $errors->get($db, "ERROR_EMPTY_SURFBAR"));
		}
	}
	
	public  function surfbar_status(){
		return $this->surfbar_status;
	}
	
	public function rewarder($db, $sitedatas, $mainObject, $sitesObj, $sessionObj, $rewardObj, $errors){
		$datas = array(); $init = false;
		$this->surfbar_point_get=$rewardObj->getSurfbarPoints($db, $sessionObj, $errors);
		$this->surfbar_point_lost=$rewardObj->lostSurfbarPoints($db, $sessionObj, $errors);
		if(!$db->prepare("UPDATE {$sitesObj->sitestbl()} SET total_views_receive=total_views_receive+?, total_views_points_attribuate=total_views_points_attribuate+?, actual_surfbar_points=actual_surfbar_points-?, views_for_hour_full=views_for_hour_full+?, last_views_at=? WHERE id=?", array(1, $this->surfbar_point_lost, $this->surfbar_point_lost, 1, time(), $sitedatas["id"]))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		
		if(!$query=$db->prepare("SELECT websites_visited FROM {$mainObject->usertbl()} WHERE id=?", [$sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$i = $query->fetch()){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($i["websites_visited"] < 100){
	   	 $init = true;
	   	 if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET websites_visited=websites_visited+? WHERE id=?", array(1, $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		} else {
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+?, websites_visited=websites_visited+? WHERE id=?", array($this->surfbar_point_get, 1, $sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		}
		if(!$query=$db->prepare("SELECT content FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() > 0){
	   	 $datas = $query->fetch(PDO::FETCH_ASSOC)["content"];
	   	 $datas = is_array(unserialize($datas)) ? unserialize($datas) : array();
	   	 $datas[] = array("site_id"=>(int)$sitedatas["id"]);
	   	 $content = serialize($datas);
	   	 if(!$db->prepare("UPDATE {$this->table} SET content=?, last_at=? WHERE user_id=?", array($content, time(), $sessionObj->values("id")))){ return false; }
	   	 $mainObject->setHistory($db, array("name"=>"surf bar", "content"=>"Your are surf in " . $sitesObj->idToUrl($db, $sitedatas["id"], $errors) . " ", "at"=>time()), $this->table, $sessionObj);
		} else {
	   	 $datas[] = array("site_id"=>(int)$sitedatas["id"]);
	   	 $content = serialize($datas);
	   	 if(!$db->prepare("INSERT INTO {$this->table}(content, user_id, last_at, surf_at) VALUES(?, ?, ?, ?)", array($content , $sessionObj->values("id"), time(), time()))){ return false; }
	   	 $mainObject->setHistory($db, array("name"=>"Surf bar", "content"=>"You are surf in <a href='{$sitesObj->idToUrl($db, $sitedatas['id'], $errors)}'>". $sitesObj->idToUrl($db, $sitedatas["id"], $errors) ."</a>", "at"=>time()), $this->table, $sessionObj);
		}
		if($init){ $errors->setFlash("user.surfbar", ["type"=>3, "message"=>"You must still surf <span class='badge'>".abs($i['websites_visited'] - 99)."</span> websites to activate your account."]); }
		return true;
	}
	
	public function dayViews($db, $sessionObj){
		if(!$query=$db->prepare("SELECT content FROM {$this->table} WHERE user_id=?", array($sessionObj->values("id")))){ return 0; }
		$contents=$query->fetch(PDO::FETCH_ASSOC)["content"];
		return !empty($contents) ? count(unserialize($contents)) : 0;
	}
	
	public function bonusSurfbar($db, $mainObject, $rewardObj, $errors){
		if(!$query=$db->prepare("SELECT id, content, user_id FROM {$this->table}")){ return false; }
		if($query->rowCount() <= 0){ return true; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $datas){
	   	 if(($content=count(unserialize($datas["content"]))) >= 100){
	   	 $db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", array($j=$rewardObj->dailySurfbarBonus($db, $content), $datas["user_id"]));
	   	 $mainObject->setHistory($db, ["name"=>"Bonus surfbar", "content"=>"You are received ".number_format($j)." points .", "at"=>time()], $this, $datas["user_id"]);
	   	 }
		}
		return true;
	}
	
	public function countNowSurfbarSurfers($db, $sitesObj){
		$query=$db->prepare("SELECT id FROM {$this->table} WHERE last_at >= ?", array(time() - 60));
		return $query->rowCount();
	}
	
	public function promoteUrl($db){
		$query=$db->prepare("SELECT * FROM promotion");
		if($query->rowCount() <= 0){ return []; }
		return $query->fetch(PDO::FETCH_ASSOC)["url"];
	}
}