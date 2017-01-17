<?php

class historic extends filter {
	
	public function setHistory($db, array $content, $thees, $Obj){
		$id = !is_numeric($Obj) ? $Obj->values("id") : $Obj;
		$table = is_object($thees) ? $thees->table : $thees;
		$content = array(
			"name"=>htmlentities($content["name"], ENT_QUOTES, "UTF-8"), 
			"content"=>htmlentities($content["content"], ENT_QUOTES, "UTF-8"),
			"at"=>$content["at"]
		);
		$query=$db->prepare("SELECT id, content FROM histo_{$table} WHERE user_id=?", array($id));
		if($query->rowCount() > 0){ $d = $query->fetch(PDO::FETCH_ASSOC); }
		if(!isset($d)){ $db->prepare("INSERT INTO histo_{$table} VALUES(?, ?, ?, ?)", array(NULL, $id, serialize(array(0=>$content)), time())); }
		else {
			if(empty($d["content"])){ return false; }
			$contents = unserialize($d["content"]);
			$contents[] = $content;
			$db->prepare("UPDATE histo_{$table} SET content=? WHERE user_id=?", array(serialize($contents), $id));
		}
	}
	
	public function getHistory($db, $thees, $sessionObj){
		$table = is_object($thees) ? $thees->table : $thees;
		$query=$db->prepare("SELECT content FROM histo_{$table} WHERE user_id=? ORDER BY add_at DESC", array($sessionObj->values("id")));
		$d = $query->fetch(PDO::FETCH_ASSOC);
		$contents = unserialize($d["content"]);
		$contents = is_array($contents) ? $contents : [];
		if($sessionObj->values("member_type") <= 3){ krsort($contents); }
		return $contents;
	}
}

