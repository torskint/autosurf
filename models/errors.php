<?php

class errors {
	
	private $errors=array();
	private $logFile="models/database/log/log.csv";
	private $table;
	private $class=["danger", "success", "default", "warning", "primary"];
	
	public function __construct($tablesObj){
		$this->table = $tablesObj->admin_errors_tbln();
	}
	
	private function log($k, $m, $line=null){
		if($k != "ERROR_SQL_ERROR"){ return false; }
		file_put_contents($this->logFile, $k.",".$m.",". $_GET['src'] . "," .$line ."\n", FILE_APPEND);
	}
	
	public function checklog(){
		$log = file($this->logFile);
		if(count($log) == 0) { return false; }
		die("<br><div class='log'>LE FICHIER LOG CONTIENT ". count($log) ." LIGNES. </div>");
	}
	
	public function countLog($name){
		$i = file($this->logFile); $s = [];
		foreach($i as $k=>$c){
	   	 foreach(explode(",", $c) as $k2=>$v){
	   		 if($c[0] == $name){ $s[] = $k2;}
	   	 }
		}
		return count($s);
	}
	
	public function errors($error, $index){
		$this->errors[$this->class[$index]]=$error;
	}
	
	public function _throw(array $errors){
		$class = isset($errors["type"]) ? $errors["type"] : $errors["class"];
		throw new exception($errors["message"], $class);
	}
	
	public function html_class(){
		return $this->class;
	}
	
	public function get($db, $name){
		$query=$db->prepare("SELECT message, class_html as type FROM {$this->table} WHERE name=?", array($name));
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	public function display(){
		if(!empty($this->errors)){
	   	 echo "<div id='alarm' class='alarm alert alert-".array_keys($this->errors)[0]."'>";
	   	 echo "<a class='close' data-dismiss='alert' href='#'> <span aria-hidden='true'>&times;</span> <span class='sr-only'>Close</span> </a>";
	   	 echo array_values($this->errors)[0];
	   	 echo "</div>";
		}
		unset($_SESSION["flash"]);
	}
	
	public function _rdr($param){
		/*if(strpos($param, "main.")!==false){
	   	 $param = str_replace("main.", "", $param);
		} else if(strpos($param, "user.")!==false){
	   	 $param = str_replace("user.", "", $param);
		} else {
	   	 $param = str_replace("admin.", "", $param);
		}*/
		#$param = str_replace(".", "/", $param);
		#header("location:../{$param}");
		header("location:?src=$param");
	}
	
	public function setFlash($param, array $errors){
		$_SESSION["flash"][]=["class"=>$errors["type"], "message"=>$errors["message"]];
		$this->_rdr($param);
		unset($_SESSION["abuses"]);
		exit();
	}
	
	public function getFlash(){
		if(isset($_SESSION["flash"])){
	   	 foreach($_SESSION["flash"] AS $message){
	   		 $this->_throw($message);
	   	 }
		}
		return false;
	}
}