<?php

class main extends banishment {

	public function register($db, $mailObj, $errors){
		unset($_SESSION["key"], $_SESSION["logindatas"], $_SESSION["confirm"]);
		$refererid=!empty($_SESSION["refererid"]) ? intval($_SESSION["refererid"]) : NULL;
		if(empty($this->POST)){ return false; }
		if(!$this->valid("username", "email", "country", "email_confirm", "password", "password_confirm")){ $errors->_throw($errors->get($db,"ERROR_INVALID_FIELD") , 0); }
		if(!$this->confirmed("email")){ $errors->_throw($errors->get($db,"ERROR_EMAIL_NOT_CONFIRMED")); }
		if(!$this->confirmed("password")){ $errors->_throw($errors->get($db,"ERROR_PASSWORD_NOT_CONFIRMED")); }
		if($this->indb($db, $this->post("email"), "id", true)){ $errors->_throw($errors->get($db, "ERROR_EMAIL_UNAVAILABLE")); }
		if($this->username_indb($db, $this->post("username"), true)){ $errors->_throw($errors->get($db, "ERROR_USERNAME_UNAVAILABLE")); }
		$country = $this->country($this->post("country"));
		if(!$db->prepare("INSERT INTO {$this->table}(id, username, email, country, password, points, member_type, account_is_validate, account_validation_key, uniqid, refererid, signup_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(NULL, strtolower($this->post("username")), strtolower($this->post("email")), $country, $this->hash($this->post("password")), 0, 999, NULL, $x=$this->key($this->CONFIRMATION_KEY_LENGHT), $u=$this->uniqid($db, 7), $refererid, time()))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		$mailObj->send(array("email"=>$this->post("email"), "username"=>$this->post("username") , "confirmation_link"=>"#ROOT#/?src=main.confirm&key=".base64_encode($x."2".$u)), "registred_mail");
		$this->setHistory($db, array("name"=>"Registration", "content"=>"You are registred successfuly on our site.", "at"=>time()), $this, (int)$db->lastInsertId());
		$errors->setFlash("main.home",$errors->get($db,"SUCCESS_SIGNUP"));
	}

	public function login($db, $errors){
		unset($_SESSION["logindatas"]);
		if(empty($this->POST)){ return false; }
		if(!$this->valid("email", "password")){ $errors->setFlash("main.home", $errors->get($db,"ERROR_INVALID_FIELD")); }
		if(!$datas=$this->indb($db, $this->post("email"), "*", false)){ $errors->setFlash("main.home", $errors->get($db,"ERROR_BAD_IDS")); }
		if(!$this->pwdverify($this->post("password"), $datas["password"])){ $errors->setFlash("main.home", $errors->get($db,"ERROR_BAD_IDS")); }
		if($this->isBanned($db, $datas["id"])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCOUNT_BANNED")); }
		if(in_array($datas["member_type"], $this->ACCESS_INTERVAL) && $datas["account_is_validate"]==1 && is_null($datas["account_validation_key"])) { return $datas; }
		if(!isset($_SESSION["confirm"])){ $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_NOT_VALIDATE")); }
		$_SESSION["logindatas"]=$this->POST;
		$errors->_rdr("main.confirm");
		exit();
	}

	public function confirm($db, $mailObj, $mpObj, $errors){
		if(empty($_SESSION["confirm"])){
			if(!isset($this->GET["key"])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND")); }
			$k=base64_decode($this->GET["key"]);
			$k=explode("2", $k);
			if(!$this->ckey($k[0], $this->CONFIRMATION_KEY_LENGHT) OR !$this->ckey($k[1], 7)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND")); }
			$_SESSION["confirm"] = ["key"=>$k[0], "uniqid"=>$k[1]]; 
			$errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_CONFIRMATION_PENDING"));
		} else if(!empty($_SESSION["confirm"])){
			if(!isset($_SESSION["logindatas"])){ $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_CONFIRMATION_PENDING")); } 
			if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE email=? && account_validation_key=? && uniqid=? && member_type=? && account_is_validate IS NULL", [$_SESSION["logindatas"]["email"], $_SESSION["confirm"]["key"], $_SESSION["confirm"]["uniqid"], 999])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
			if($query->rowCount() > 0){
				if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
				if(!$db->prepare("UPDATE {$this->table} SET account_validation_key=?, account_is_validate=?, member_type=?, points=? WHERE id=? && uniqid=?", array(NULL, 1, 4, $this->NEW_USER_REWARD, $datas["id"], $_SESSION["confirm"]["uniqid"]))){ $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_NOT_VALIDATE")); }
				/*MAIL TO USER FOR BONUS && VALIDATION */
				$mailObj->send(array("date"=>$datas["signup_at"], "email"=>$datas["email"], "username"=>$datas["username"] , "bonus"=>$this->NEW_USER_REWARD), "accountConfirmed_mail");
				$this->setHistory($db, array("name"=>"Account validate", "content"=>"You are validate your user account.", "at"=>time()), $this, $datas["id"]);
				if(!is_null($datas["refererid"])){
					if(!$query=$db->prepare("SELECT email, username, member_type FROM {$this->table} WHERE id=?", [$datas["id"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
					if(!$d=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
					if(!$db->prepare("UPDATE {$this->table} SET points=points+? WHERE id=?", [$points=$this->newUser_RefererReward($d["member_type"]), $datas["refererid"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
					$this->referralsRewardForReferer($db, $datas["id"], $points, $errors);
					//MP TO REFERER FOR BONUS
					$mpObj->send_auto($db, array("receiver_id"=>$datas["refererid"] , "bonus"=>$points), "mp_newUserRefererReward");
				}
				//MP TO ADMIN FOR NEW USER (not done)
				unset($_SESSION["logindatas"], $_SESSION["confirm"]);
				$_SESSION["first_login"] = "YES";
				if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE id=? && email=? && member_type=? && account_is_validate=? && account_validation_key IS NULL", [$datas["id"], $datas["email"], 4, 1])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
				if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
				return $datas;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function pwdResetStart($db, $mailObj, $errors){
		unset($_SESSION["logindatas"], $_SESSION["confirm"]);
		if(!$db->prepare("DELETE FROM {$this->pwdresetTbl} WHERE reset_at < ?", [time() - (3600*24)])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(isset($this->GET["key"])){
			unset($_SESSION["pwdreset"]);
			$k=unserialize(base64_decode($this->GET["key"]));
			$k=explode("/", $k);
			if(!$this->ckey($k[0], $this->CONFIRMATION_KEY_LENGHT*2) OR !$this->ckey($k[2], 7)){ $errors->setFlash("main.reset-pwd", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND")); }
			if(!$query=$db->prepare("SELECT user_id FROM {$this->pwdresetTbl} WHERE reset_key=? && md5(name)=?", [$k[0], $k[1]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
			if($query->rowCount() > 0){
				if(!$datas = $query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
				if(!$query=$db->prepare("SELECT * FROM {$this->table} WHERE id=?", [$datas["user_id"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
				if($query->rowCount() > 0){
					if(!$datas =$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
					$_SESSION["pwdreset"] = $datas;
					$errors->setFlash("main.reset-pwd", $errors->get($db, "ERROR_PASSWORD_RESET_PENDING"));
				}
				$errors->setFlash("main.reset-pwd", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND"));
			}
			return false;
		} else {
			if(empty($this->POST)){ return false; }
			if(!$this->valid("email")){ $errors->_throw($errors->get($db,"ERROR_INVALID_EMAIL_FIELD")); }
			if(!$datas=$this->indb($db, $this->post("email"), "id, uniqid, username", false)){ $errors->_throw($errors->get($db,"ERROR_ACCOUNT_NOT_FOUND")); }
			if(!$query=$db->prepare("SELECT id FROM {$this->pwdresetTbl} WHERE user_id=?", [$datas["id"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
			if($query->rowCount() > 0){ $db->prepare("DELETE FROM {$this->pwdresetTbl} WHERE user_id=? ", [$datas["id"]]); }
			if(!$query=$db->prepare("INSERT INTO {$this->pwdresetTbl}(user_id, name, reset_key, reset_at) VALUES(?, ?, ?, ?)", [$datas["id"], $datas["username"] , $x=$this->key($this->CONFIRMATION_KEY_LENGHT*2) , time()])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); } 
			$this->setHistory($db, array("name"=>"Request password", "content"=>"You are request your account password.", "at"=>time()), $this, $datas["id"]);
			//MAIL TO USER
			$mailObj->send(array("email"=>$this->post("email"), "username"=>$datas["username"], "pwdreset_link"=>"#ROOT#/?src=main.reset-pwd&key=".base64_encode(serialize($x."/".md5($datas["username"])."/".$datas["uniqid"]))), "pwdreset_mail"); 
			return $datas;
		}
	}

	public function pwdresetComplete($db, $sessionVar,  $errors){
		if(empty($this->postAll("change_password_form"))){ return false; }
		if(!$this->valid("password", "password_confirm")){ $errors->_throw($errors->get($db, "ERROR_INVALID_FIELD")); }
		if($this->post("password")!=$this->post("password_confirm")){ $errors->_throw($errors->get($db, "ERROR_PASSWORD_NOT_CONFIRMED")); }
		if(!$query=$db->prepare("SELECT password FROM {$this->table} WHERE id=?", [$sessionVar["id"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if(!$db->prepare("UPDATE {$this->table} SET password=? WHERE id=? && email=?", array($this->hash($this->post("password")), $sessionVar["id"], $sessionVar["email"]))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		if(!$db->prepare("DELETE FROM {$this->pwdresetTbl} WHERE user_id=?", array($sessionVar["id"]))){ $errors->_throw($errors->get($db,"ERROR_SQL_ERROR")); }
		unset($_SESSION["pwdreset"]);
		$errors->setFlash("main.home", $errors->get($db, "SUCCESS_DONE"));
	}
}