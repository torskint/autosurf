<?php

class bonus {
	
	#private $dailybannertbl="dailybannerlist";
	private $table;
	private $PAGE_TIMEOUT=120;
	
	public function __construct($tablesObj){
		$f = __CLASS__."_tbln";
		$this->table = $tablesObj->$f();
	}
	/*
	public function dailybonustbl(){
		return $this->dailybonustbl;
	}*/
	
	public function reboursToInt(){
		$endHour = mktime(00, 00, 00, date("m"), date("d"), date("Y"));
		$nowHour = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		return   ($endHour - $nowHour) - 3600;
	}
	
	public function reboursToDate(){
		return  date("H:i:s", $this->reboursToInt());
	}
	
	public function dailybonus_points($sessionObj){
		$dl = (int)$sessionObj->values("member_type");
		if($dl == 3){ return rand(10, 30)*10; } 
		if($dl == 2){ return rand(30, 50)*10; } 
		if($dl == 1){ return rand(5000, 10000)*10; }
		return rand(1, 10)*10;
	}
	
	public function dailybonus($db, $mainObject, $sessionObj, $errors){
		if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=?", array((int)$sessionObj->values("id")))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		$datas=$query->fetch(PDO::FETCH_ASSOC);
		if($query->rowCount() > 0){ return $datas["points"]; }
		if(empty($mainObject->postAll())){ return false; }
		if(time() - $mainObject->post("daily_bonus") > $this->PAGE_TIMEOUT){ $errors->setFlash("user.dailybonus", $errors->get($db, "ERROR_PAGE_TIMEOUT")); }
		$bounce = (int)$this->dailybonus_points($sessionObj);
		if(!$db->prepare("INSERT INTO {$this->table}(user_id, points, bonus_at) VALUES(?, ?, ?)", array((int)$sessionObj->values("id"), $bounce, time()))){ $errors->setFlash("user.dailybonus", $errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$db->prepare("UPDATE {$mainObject->usertbl()} SET points=points+? WHERE id=?", array($bounce, $sessionObj->values("id")))){ $errors->setFlash("user.dailybonus", $errors->get($db, "ERROR_SQL_ERROR")); }
		$mainObject->setHistory($db, array("name"=>"Bonus", "content"=>"You have won ".number_format($bounce)." credits.", "at"=>time()), $this->table, $sessionObj);
		$errors->_rdr("user.dailybonus");
	}
	
	public function dailybonus_stats($db){
		if(!$query=$db->prepare("SELECT * FROM {$this->table} ORDER BY  points DESC")){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR"), 0 ); }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function dailybannerbonus(){
		/*$query=$db->prepare("SELECT * FROM {$this->dailybannertbl}");
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() > 0){ return $datas["points"]; }
		*/
	}
}