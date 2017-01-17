<?php

class filter {
	
	protected $POST=array();
	protected $GET=array();
	protected $table;
	protected $countryList=[];
	private $hashCost = 6;
	protected $banishmentTbl;
	protected $pwdresetTbl;
	protected $countryTbl;
	protected $NEW_USER_REWARD=100;
	protected $ACCESS_INTERVAL=array();
	public $CONFIRMATION_KEY_LENGHT=50;
	#private $db_instance = null;
	
	public function __construct($tablesObj){
		$this->POST=$_POST;
		$this->GET=$_GET;
		$this->ACCESS_INTERVAL=array(1, 2, 3, 4);
		
		$this->table = $tablesObj->user_tbln();
		$this->banishmentTbl = $tablesObj->banishment_tbln();
		$this->pwdresetTbl = $tablesObj->pwdreset_tbln();
		$this->countryTbl = $tablesObj->admin_country_tbln();
	}
	
	public function countryList($db){
		if(!$query=$db->prepare("SELECT * FROM {$this->countryTbl}")){return []; }
		if($query->rowCount() <= 0){ return []; }
		return $this->countryList = $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function country($pays){
		global $db;
		return (is_numeric($pays) && $pays>= 1 && array_key_exists($pays, $this->countryList($db))) ? intval($this->countryList($db)[($pays-1)]["id"]) : false;
	}
	
	public function dateFormat($time){
		if(strlen($this->timestampFormat($time)) >= strlen(time())){ $time = $this->timestampFormat($time); } else { return "---"; }
		if($time >= strtotime("00:00:00") && $time < strtotime("00:00:00 +1 day")){ return "Today, ".date("H:i", $time); }
		if($time >=strtotime("00:00:00 -1 day") && $time < strtotime("00:00:00")){ return "Last day,  ".date("H:i", $time); }
		else { return date("M d, Y", $time); }
	}
	
	public function timestampFormat($timestamp){
		return $timestamp;
	}
	
	public function all_total($datas){
		$allTotal=0;
		foreach($datas as $p){ $allTotal += $p["points"]; }
		return "<span class='bold pull-right'>T : ".number_format($allTotal)." </span>";
	}
	
	protected function newUser_RefererReward($type){
		### Points au parrain lorsque un nouveau membre s'inscrit 
		### et valide son compte.
		if($type == 4){ return 10; }
		if($type == 3){ return 20; }
		if($type == 2){ return 50; }
		if($type == 1){ return 100; }
		else { return 0; }
	}
	
	public function referralsRewardForReferer($db, $parrainid, $points, $errors){
		if(!$db->prepare("UPDATE {$this->table} SET points_for_referer=points_for_referer+? WHERE id=?", [$points, $parrainid])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
	}
	
	public function access_interval(){
		return $this->ACCESS_INTERVAL;
	}
	
	public function post($post){
		return (array_key_exists($post, $this->POST) && !empty($this->POST[$post])) ? $this->POST[$post] : false;
	}
	
	public function postAll($btn=null){
		return array_key_exists($btn, $this->POST) ? $this->POST[$btn] : $this->POST;
	}
	
	public function get($get){
		return !empty($this->GET[$get]) ? trim($this->GET[$get]) : null;
	}
	
	//LISTE OF TABLE.
	public function usertbl(){
		return $this->table;
	}
	
	private function message($m){
		return (is_string($m) && (strlen($m) >= 10 && strlen($m) <= 5000)) ? htmlentities(htmlspecialchars($m), ENT_QUOTES, "UTF-8") : false;
	}
	
	private function script($code){
		return !empty($code) ? $code : false;
	}
	
	private function id($id){
		return is_numeric($id) ? (int)$id : false;
	}
	
	private function intg($id){
		return is_numeric($id) ? (int)$id : false;
	}
	
	private function subject($s){
		return (is_string($s) && (strlen($s) >= 10 && strlen($s) <= 200)) ? htmlentities(htmlspecialchars($s), ENT_QUOTES, "UTF-8") : false;
	}
	
	public function name($name){
		return preg_match("/^[a-z0-9_ ]+$/i", $name);
	}
	
	public function daily($time){
		return $time;
	}
	
	public function email($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
	}
	
	public function url($db, $sitesObj, $abuseObj, $errors){ $j[]=0;
		$lowerUrl = strtolower($this->post("url"));
		if(!preg_match("#^((http)+(s|)(:\/\/))?(www\.|www2\.|www3\.|)?([a-z0-9-\.]+)+(\.[a-z]{2,9})+(\/||(\/[a-z0-9]+)(\.(html|htm))?$#i", $lowerUrl)){ $errors->_throw($errors->get($db, "ERROR_INVALID_URL_FIELD")); }
		$query=$db->prepare("SELECT id FROM {$sitesObj->sitestbl()} WHERE url=?", [$lowerUrl]);
		if($query->rowCount() >= 1){ return false; }
		$parse = parse_url($lowerUrl);
		$url = str_replace("www.", "", $parse["host"]);
		$url = str_replace("www2.", "", $url);
		$url = str_replace("www3.", "", $url);
		$url_explode = explode(".", $url);
		$url = array_slice($url_explode, -2)[0].".". array_slice($url_explode, -2)[1];
		
		if(!$query=$db->prepare("SELECT url FROM {$abuseObj->denied_domainstbl()}")){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return true; }
		if(!$datas2=$query->fetchAll(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		foreach($datas2 as $k=>$deniedurl){
	   	 foreach(["http:\/\/", "https:\/\/", ""] as $http){
	   		 foreach(["www\.", "www2\.", "www3\.", ""] as $www){
	   	   	  if(preg_match("/^($http$www)$/i", $deniedurl["url"])){
	   	   		  $url_denied = str_replace("$http$www", "", $deniedurl["url"]);
	   	   	  }
	   		 }
	   	 }
	   	 $durl2 = isset($url_denied) ? $url_denied : $deniedurl["url"];
	   	 if((md5($url)==md5($durl2)) OR (count(explode($durl2, $url)) > 1)){ $errors->_throw($errors->get($db, "ERROR_SITE_DENIED")); }
		}
		
		return true;
	}
	
	protected function username($name){
		return (preg_match("/^[a-z0-9]{6,}$/i", $name) && !preg_match("/^(admin|user|webmast)$/i", $name)) ? $name : false;
	}
	
	protected function password($pwd){
		return preg_match("/^[a-zA-Z0-9]{4,}$/", $pwd) ? $pwd : false;
	}
	
	protected function ckey($ckey, $length){
		return preg_match("/^[a-zA-Z0-9-]{{$length}}$/", $ckey) ? true : false;
	}
	
	public function confirmation_key(){
		return $this->key($this->CONFIRMATION_KEY_LENGHT);
	}
	
	public function pwdverify($pwd, $hash){
		return password_verify($pwd, $hash);
	}
	
	protected function confirmed($confirm){
		return ($this->post($confirm) == $this->post($confirm."_confirm")) ? true : false;
	}
	
	public function hash($string){
		return password_hash($string, PASSWORD_BCRYPT, array("cost"=> $this->hashCost, "salt"=> bin2hex(openssl_random_pseudo_bytes(32))));
	}
	
	public function status_icon($status){
		if($status == 1){
	   	 return "<span class='text-success glyphicon glyphicon-ok-circle'></span>";
		} else if($status == 2){
	   	 return "<span class='text-warning glyphicon glyphicon-ban-circle'></span>";
		} else if($status == 0){
	   	 return "<span class='text-danger glyphicon glyphicon-ban-circle'></span>";
		}
	}
	
	public function key($length){
		return substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ098765431-", 150)), 0, $length);
	}
	
	public function uniqid($db, $length){
		$query=$db->prepare("SELECT id FROM {$this->table} WHERE uniqid=?", array($this->key($length)));
		do {
	   	 $uniqid = $this->key($length);
		} while($query->rowCount() > 0);
		return $uniqid;
	}
	
	public function indb($db, $email, $field, $return=false){
		$query=$db->prepare("SELECT $field FROM {$this->table} WHERE email=?", array($email));
		if($query->rowCount() > 0){ return (!$return) ? $query->fetch(PDO::FETCH_ASSOC) : true; }
		return false;
	}
	
	protected function action(){
		if(isset($this->POST["action"]) && $this->POST["action"] == "CONFIRM_PROCESS"){ return true; }
		return false;
	}
	
	public function username_indb($db, $name, $return=false){
		$query=$db->prepare("SELECT id FROM {$this->table} WHERE username=?", array($name));
		if($query->rowCount() > 0){ return (!$return) ? $query->fetch(PDO::FETCH_ASSOC) : true; }
		return false;
	}
	
	public function valid(){ $j=array();
		foreach(func_get_args() as $param){
	   	 $func=explode("_", $param)[0];
	   	 if(!$this->post($param) OR !$this->$func($this->post($param))){
	   		 $j[]=$param;
	   	 }
		}
		return (count($j) > 0) ? false : true;
	}
	
	public function textFormater($text){
		$text = ucfirst(strtolower($text));
		#preg_match("/\*(.*)\*/", $text, $m);
		#debug($m[0]); die;
		#$text = str_replace($m[0], "<b>{$m[0]}</b>", $text);
		return nl2br(preg_replace_callback('/[.!?].*?\w/' , create_function('$matches' , 'return strtoupper($matches[0]);'), $text));
	}
}