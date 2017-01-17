<?php

class messenger extends _texts {
	
	private $table = false;
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->messenger_tbln();
		$this->tables_instance = $tablesObj;
	}
	
	public function send($db, $mainObject, $sessionObj, array $datas, $errors){
		$message = htmlentities($datas["message_mp"], ENT_QUOTES, "UTF-8");
		$subject = htmlentities($datas["subject_mp"], ENT_QUOTES, "UTF-8");
		if(!$db->prepare("INSERT INTO {$this->table}(sender_id, user_id, subject, content, is_read, timeout, add_at) VALUES(?, ?, ?, ?, ?, ?, ?)", [(int)$sessionObj->values("id"), (int)$datas["receiver_id"], $subject, $message, 0, (int)$datas["message_timeout"], time()])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	}
	
	public function send_auto($db, $args, $callback){
		$query=$db->prepare("SELECT * FROM {$this->tables_instance->user_tbln()} WHERE id=?", [(int)$args["receiver_id"]]);
		if($query->rowCount() <= 0){ return false; }
		$datas = $this->$callback(array_merge($args, $query->fetch(PDO::FETCH_ASSOC)));
		if(!$db->prepare("INSERT INTO {$this->table}(sender_id, user_id, subject, content, is_read, timeout, add_at) VALUES(?, ?, ?, ?, ?, ?, ?)", [NULL, (int)$args["receiver_id"], $datas["subject"], $datas["message"], 0, 3600*24, time()])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	}
	
	public function messages_list($db, $sessionObj){
		$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=? ORDER BY add_at DESC", [(int)$sessionObj->values("id")]);
		return ($query->rowCount()) ? $query->fetchAll(PDO::FETCH_ASSOC) : [];
	}
	
	public function messages_view($db, $sessionObj, $message_id, $message_sum, $errors){
		$query=$db->prepare("SELECT * FROM {$this->table} WHERE user_id=? && id=? && md5(checksum)=? ORDER BY id DESC", [(int)$sessionObj->values("id"), $message_id, $message_sum]);
		return ($query->rowCount()) ? $query->fetch(PDO::FETCH_ASSOC) : $errors->_rdr("user.message"); exit;
	}
	
	public function updateChecksum($db, $sessionObj){
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=?", [(int)$sessionObj->values("id")])){ return false; }
		if($query->rowCount() <= 0){ return false; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $infos){
			$db->prepare("UPDATE {$this->table} SET checksum=? WHERE id=?", [bin2hex(openssl_random_pseudo_bytes(16)), (int)$infos["id"]]);
		}
	}
	
	public function messages_is_read($db, $sessionObj, $message_id, $message_sum){
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=? && id=? && md5(checksum)=? && is_read=?", [(int)$sessionObj->values("id"), $message_id, $message_sum, 0])){ return false; }
		if($query->rowCount() <= 0){ return false; }
		$db->prepare("UPDATE {$this->table} SET read_at=?, is_read=? WHERE user_id=? && id=? && md5(checksum)=?", [time(), 1, (int)$sessionObj->values("id"), $message_id, $message_sum]);
	}
	
	public function messages_cleaner($db, $sessionObj, $message_id, $message_sum, $errors){
		if(!$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=? && id=? && md5(checksum)=?", [(int)$sessionObj->values("id"), $message_id, $message_sum])){ return false; }
		if($query->rowCount() <= 0){ $errors->_rdr("user.message"); exit; }
		if(!$db->prepare("DELETE FROM {$this->table} WHERE user_id=? && id=? && md5(checksum)=?", [(int)$sessionObj->values("id"), $message_id, $message_sum])){ $errors->setFlash("user.message", $errors->get($db, "ERROR_SQL_ERROR")); }
		$errors->setFlash("user.message", $errors->get($db, "SUCCESS_DONE"));
	}
	
	public function newNotification($db, $sessionObj){
		$query=$db->prepare("SELECT id FROM {$this->table} WHERE user_id=? && is_read=?", [(int)$sessionObj->values("id"), 0]);
		return (($nb = $query->rowCount()) <= 0) ? 0 : $nb;
	}
	
	public function cleanExpired_messages($db){
		if(!$db->prepare("DELETE FROM {$this->table} WHERE read_at+timeout<=? && is_read=?", [time(), 1])){ return false; }
		if(!$query=$db->prepare("SELECT id FROM {$this->table}")){ return false; }
		if($query->rowCount() <= 0){ $db->tblTruncate($this->table); }
		return true;
	}
}