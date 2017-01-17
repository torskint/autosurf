<?php

class abuses {
	
	private $abusesTypeListTbl;
	private $denied_domainsTbl;
	private $blacklistTbl;
	private $separator="-";
	private $USER_PERCENTAGE=10; //10% des membres
	
	public function __construct($tablesObj){
		$this->blacklistTbl = $tablesObj->blacklist_tbln();
		$this->denied_domainsTbl = $tablesObj->denied_domains_tbln();
		$this->abusesTypeListTbl = $tablesObj->admin_abuses_type_list_tbln();
	}
	
	public function blacklistTbl(){
		return $this->blacklistTbl;
	}
	
	public function denied_domainstbl(){
		return $this->denied_domainsTbl;
	}
	
	private function blacklistQuotas($Obj){
		if($Obj->values("member_type") == 3){ return 50; }
		if($Obj->values("member_type") == 2){ return 100; }
		if($Obj->values("member_type") == 1){ return 1000; }
		else { return 5; }
	}
	
	private function idToUrl($db, $id){
		$query=$db->prepare("SELECT url FROM sites WHERE id=?", [(int)$id]) or die();
		return $query->fetch(PDO::FETCH_ASSOC)["url"];
	}
	
	public function deniedDomains($db){
		if(!$sites = $db->prepare("SELECT * FROM {$this->denied_domainsTbl}")){ return false; }
		return $sites;
	}
	
	public function userWhoAreblacklistedYou($db, $sessionObj, $sitesObj, $errors){ $k=[]; $i=1;
		if(!$query=$db->prepare("SELECT id FROM {$sitesObj->sitestbl()} WHERE user_id=? && status=?", [$sessionObj->values("id"), 1])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return 0; }
		if(!$site = $query->fetchAll(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$query=$db->prepare("SELECT content, user_id FROM {$this->blacklistTbl} WHERE user_id<>?", [$sessionObj->values("id")])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return 0; }
		if(!$blacklist=$query->fetchAll(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(empty($blacklist)){ return 0; }
		foreach($blacklist as $content){
	   	 $contents=!empty($content["content"]) ? unserialize($content["content"]) : array();
	   	 if(empty($contents)){ return 0; }
	   	 foreach($contents AS $key=>$c){
	   		 foreach($site as $id){
	   	   	  if($id["id"] == $c["site_id"]){ $k[]= $content["user_id"]; }
	   		 } $i++;
	   	 }
		}
		return count(array_unique($k));
	}
	
	public function blacklistCounter($db, $sessionObj){
		if(!$query1=$db->prepare("SELECT content FROM {$this->blacklistTbl} WHERE user_id=?", [$sessionObj->values("id")])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query1->rowCount() <= 0){ return 0; }
		$fetch_contents=$query1->fetch(PDO::FETCH_ASSOC)["content"];
		$contents=!empty($fetch_contents) ? unserialize($fetch_contents) : array();
		return count($contents);
	}
	
	public function abusesTypeList($db){
		$query=$db->prepare("SELECT id, name FROM {$this->abusesTypeListTbl}");
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function setAbuses($db, $mainObject, $userObj, $sessionObj, $sitesObj, $errors){
		$x=0; $k=array();
		if(empty($_SESSION["abuses"])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_EMPTY_ABUSES_DATAS")); }
		if(empty($mainObject->postAll("report_abuses")) OR $mainObject->postAll("action")!="CONFIRM_PROCESS" OR  empty($mainObject->postAll("abuse_id")) OR !is_numeric($mainObject->postAll("abuse_id"))){ return false; }
		$abuseID = (int)$mainObject->postAll('abuse_id');
		$siteID = (int)$_SESSION["abuses"];
		//si l'utilisateur a deja signaler ledit site, alors on le redirige vers le surf bar.
		if(!$query1=$db->prepare("SELECT content FROM {$this->blacklistTbl} WHERE user_id=?", [$sessionObj->values("id")])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); } 
		$fetch_contents=$query1->fetch(PDO::FETCH_ASSOC)["content"];
		$contents=!empty($fetch_contents) ? unserialize($fetch_contents) : array();
		if(!empty($contents)){ $i=1;
	   	 foreach($contents AS $key=>$c){
	   		 if($siteID == $c["site_id"]){ $k["tor_skint_".$i]=$i; }
	   		 $i++;
	   	 }
		}
		if(count($k) > 0){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SITE_ALREADY_REPORTED"));}
		//si le nombre de personnes ayant signaler ce site est supérieur ou égal a 10% des membres du site on supprime ledit site.
		//Et on envoi un mail au propriétaire du site supprimé et a l'administrateur du site.
		//puis on place ce site dans la liste des domaines indésirables. 
		if(!$query=$db->prepare("SELECT id, user_id, name, counter, url FROM {$sitesObj->sitestbl()} WHERE id=?", [$siteID])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() == 0){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SITE_NOT_EXIST")); }
		$site=$query->fetch(PDO::FETCH_ASSOC);
		if($userObj->count($db)>1000 && ($site["counter"]+1)>=round($userObj->count($db)/$this->USER_PERCENTAGE)){
	   	 if(!$query = $db->prepare("SELECT email FROM {$mainObject->usertbl()} WHERE id=?", array($site["user_id"]))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if(!$email = $query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 #$messengerObj->send($user["email"].........)
	   	 #$messengerObj->send($admin["email"].........)
	   	 if(!$db->prepare("INSERT INTO {$this->denied_domainsTbl}(site_id, name, url, percentage, add_at) VALUES(?, ?, ?, ?, ?)", array($siteID, $site["name"], $site["url"], ($site["counter"]+1)/$userObj->count($db), time()))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if(!$db->prepare("DELETE FROM {$sitesObj->sitestbl()} WHERE id=? && user_id=?", array($siteID, $site["user_id"]))){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 $errors->setFlash("user.surfbar", $errors->get($db, "SUCCESS_SITE_BANNED"));
		}
		$quotas = $this->blacklistQuotas($sessionObj);
		if(count($contents) >= $quotas){ $errors->_throw($errors->get($db, "ERROR_BLACKLIST_FULL")); }
		if(!empty($contents)){
	   	 $datas = $contents;
	   	 $datas[] = array("site_id"=>$siteID, "abuse_id"=>$abuseID);
		} else {
	   	 $datas[0] = array("site_id"=>$siteID, "abuse_id"=>$abuseID);
		}
		$CONTENTS_ABUSES=serialize($datas);
		if($query1->rowCount() > 0 && count($k)==0){
	   	if(!$db->prepare("UPDATE {$this->blacklistTbl} SET content=? WHERE user_id=?", [$CONTENTS_ABUSES , $sessionObj->values("id")])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 $x=1;
		} else {
	   	 if(!$db->prepare("INSERT INTO {$this->blacklistTbl}(content, user_id, add_at) VALUES(?, ?, ?)", [$CONTENTS_ABUSES, $sessionObj->values("id"), time()])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 $x=1;
		}
		if($x==1 ){
	   	 if(!$db->prepare("UPDATE {$sitesObj->sitestbl()} SET counter=counter+? WHERE id=?", [1, $siteID])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		}
		unset($_SESSION["abuses"]);
		$mainObject->setHistory($db, array("name"=>"Site blacklisted", "content"=>$this->idToUrl($db, $abuseID), "at"=>time()), $this->blacklistTbl, $sessionObj);
		$errors->setFlash("user.surfbar", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function getAbuses($db, $sessionObj, $sitesObj, $errors){ $datas=[];
		if(!$db->prepare("DELETE FROM {$this->blacklistTbl} WHERE content=?", ["a:0:{}"])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$query1=$db->prepare("SELECT content FROM {$this->blacklistTbl} WHERE user_id=?", [$sessionObj->values("id")])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query1->rowCount() <= 0){ return []; }
		$fetch_contents=$query1->fetch(PDO::FETCH_ASSOC)["content"];
		$contents=!empty($fetch_contents) ? unserialize($fetch_contents) : [];
		if(empty($contents)){ return []; } $i=1;
		foreach($contents AS $key=>$c){
	   	 if(!$query=$db->prepare("SELECT id, name, url, total_views_receive, user_id, add_at FROM {$sitesObj->sitestbl()} WHERE id=?", [$c["site_id"]])){ $errors->setFlash("user.home", $errors->get($db, "ERROR_SQL_ERROR")); }
	   	 if($query->rowCount() <= 0){ unset($contents[$key]); if(!$db->prepare("UPDATE {$this->blacklistTbl} SET content=? WHERE user_id=?", [serialize($contents), $sessionObj->values("id")])){ $errors->setFlash("user.surfbar", $errors->get($db, "ERROR_SQL_ERROR")); } }
	   	 else { $datas[] = $query->fetch(PDO::FETCH_ASSOC); }
	   	 $i++;
		}
		if(count($datas) > 0){ return $datas; }
		return [];
	}
	
	public function cancelAbuses($db, $mainObject, $sessionObj, $sitesObj, $errors){
		if(empty($mainObject->postAll("blacklist_cancel")) OR empty($mainObject->postAll("site_id")) OR !is_numeric((int)$mainObject->postAll("site_id"))){ $errors->setFlash("user.blacklist", $errors->get($db, "ERROR_EMPTY_ABUSES_DATAS")); }
		if(!$query1=$db->prepare("SELECT content FROM {$this->blacklistTbl} WHERE user_id=?", [(int)$sessionObj->values("id")])){ $errors->setFlash("user.blacklist", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query1->rowCount() <= 0){ return false; }
		$fetch_contents=$query1->fetch(PDO::FETCH_ASSOC)["content"];
		$contents=!empty($fetch_contents) ? unserialize($fetch_contents) : [];
		if(empty($contents)){ $errors->setFlash("user.blacklist", $errors->get($db, "ERROR_EMPTY_ABUSES_DATAS")); } $i=1;
		foreach($contents AS $key=>$c){
	   	 if((int)$mainObject->postAll("site_id") == (int)$c["site_id"]){ unset($contents[$key]); }
	   	 $i++;
		}
		if(!$db->prepare("UPDATE {$this->blacklistTbl} SET content=? WHERE user_id=?", [serialize($contents) , $sessionObj->values("id")])){ $errors->setFlash("user.blacklist", $errors->get($db, "ERROR_SQL_ERROR")); }
		$mainObject->setHistory($db, array("name"=>"Site unblocked", "content"=>"<span class='danger fa fa-times-circle'></span> &nbsp; ".$this->idToUrl($db, $mainObject->postAll("site_id"))."", "at"=>time()), $this->blacklistTbl, $sessionObj);
		$errors->setFlash("user.blacklist", $errors->get($db, "SUCCESS_DONE"));
	}
}