<?php

class banishment extends historic {
	
	public function banishmentTbl(){
		return $this->banishmentTbl;
	}
	
	public function isBanned($db, $id){
		if(!$query=$db->prepare("SELECT id FROM {$this->banishmentTbl} WHERE user_id=?", [(int)$id])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() >= 1){ return true; }
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE id=? && member_type=?", [(int)$id, 998])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() >= 1){ return true; }
		return false;
	}
	
	public function banishment_expireCleaner($db){ $bn = [];
		$query=$db->prepare("SELECT id, user_id, old_member_type FROM {$this->banishmentTbl} WHERE add_at+timeout<=?", [time()]);
		if(($nb = $query->rowCount()) <= 0){ return true; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) AS $infos){
	   	 $db->prepare("UPDATE {$this->table} SET member_type=? WHERE id=?", [(int)$infos["old_member_type"], (int)$infos["user_id"]]);
	   	 $db->prepare("DELETE FROM {$this->banishmentTbl} WHERE id=?", [(int)$infos["id"]]);
	   	 $bn[] = $infos["id"];
		}
		if(count($bn) == $nb){ return true; }
		return false;
	}
}