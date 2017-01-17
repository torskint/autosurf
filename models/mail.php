<?php

class mail extends _texts {
	
	private $table;
	private $headers=null;
	private $db = null;
	private $site_root = null;
	private $no_reply_email="contact-us@noreply.com";
	private $contactus_email = "tor.skint@gmail.com";
	
	public function __construct($db, $tablesObj, $site_root){
		$this->db =$db;
		$this->site_root = $site_root;
		$this->headers="MIME-Version: 1.0"."\r\n";
		$this->headers .= "Content-type: text/html; charset=utf-8";
		$this->table = $tablesObj->mail_tbln();
	}
	
	public function send(array $args=[], $callback){
		$datas = $this->$callback($args);
		$this->headers .= "From: [".strtoupper($args["username"])."] {$datas["subject"]}";
		## file_put_contents("inbox/messages.html", str_replace("#ROOT#", trim($this->site_root, "/"), $datas["body"]) );
		return mail($args["email"], $datas["subject"], str_replace("#ROOT#", trim($this->site_root, "/"), nl2br($datas["body"])), $this->headers);
	}
	
	public function outSend(array $datas){
		return mail($datas["email"], $datas["subject"], wordwrap($datas["message"], 200, "\n\r"), $this->headers);
	}
	
	public function contact_us($mainObject, $sessionObj, $errors){
		if(empty($mainObject->postAll())){ return false; }
		if(!$mainObject->post("subject_contactus") OR !$mainObject->post("message_contactus")){ $errors->_throw($errors->get($this->db, "ERROR_INVALID_FIELD")); }		 
		if(!$mainObject->valid("subject_contactus", "message_contactus")){ $errors->_throw($errors->get($this->db, "ERROR_INVALID_FIELD")); }
		if(isset($_SESSION["accounts"]) && array_key_exists("email", $_SESSION["accounts"])){ $email = $sessionObj->values("email"); }
		else { $email = $mainObject->post("email_contactus"); }
		if(!$mainObject->email($email)){ $errors->_throw($errors->get($this->db, "ERROR_INVALID_EMAIL_FIELD")); }
		$this->outSend(array(
			"email"=>$this->contactus_email,
			"subject"=>$mainObject->post("subject_contactus") ,
			"message"=>$mainObject->post("message_contactus")
		));
		$errors->setFlash("main.contact-us", $errors->get($this->db, "SUCCESS_DONE"));
	}
}