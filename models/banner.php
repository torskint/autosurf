<?php

class banner {
	
	private $table="banners";
	
	private function fromDb($db){
		if(!$query=$db->prepare("SELECT * FROM {$this->table}", [])){}
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function myBanners($db, $sessionObj){
		$banners = [];
		foreach($bn=$this->fromDb($db) as $k=>$v){
	   	 if($bn["user_id"] == $sessionObj->values("id")){ $banners[] = $v; }
		}
		return $banners;
	}
	
	public function otherBanners($db, $sessionObj){
		$banners = [];
		foreach($bn=$this->fromDb($db) as $k=>$v){
	   	 if($bn["user_id"] != $sessionObj->values("id")){ $banners[] = $v; }
		}
		return $banners;
	}
	
	public function display($datas){
		return 
		"<a href='{$datas["url"]}'>
		<img src='{$datas["imgurl"]}' width='{$datas["width"]}' height='{$datas["height"]}' alt='{$datas["description"]}'/>
		</a>";
	}
}