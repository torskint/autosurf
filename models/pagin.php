<?php

class pagin {
	
	private $PAGE_NUMBER=1;
	private $PER_PAGE=10;
	private $TOTAL_PAGE=1;
	private $LOCATION=null;
	
	public function __construct($params){
		if(is_numeric($params[0]) && $params[0] > 0){ $this->PAGE_NUMBER = (int)$params[0]; } 
	}
	
	public function setPagin($datas, $url, $per_page=false){
		$this->LOCATION = $url;
			if($per_page){ $this->PER_PAGE = $per_page; }
		if(count($datas) > $this->PER_PAGE){
	   	 $count = count($datas);
	   	 if($this->PAGE_NUMBER > $count){ header("location:?src={$this->LOCATION}/1");  exit(); }
	   	 $this->TOTAL_PAGE = ($count/$this->PER_PAGE > round($count/$this->PER_PAGE)) ? round($count/$this->PER_PAGE)+1 : round($count/$this->PER_PAGE);   
	   	 return array_slice($datas, ($this->PAGE_NUMBER-1)*$this->PER_PAGE, $this->PER_PAGE, true);
		}
		return $datas;
	}
	
	public function getPagin(){
		if($this->TOTAL_PAGE >1){
	   	 
 	   	$prev_class = null;
	   	 $next_class = null;
	   	 
 	   	if($this->PAGE_NUMBER == 1){ $prev_class = " hidden"; }
	   	 else if($this->PAGE_NUMBER == $this->TOTAL_PAGE){ $next_class = " hidden"; } 
	   	 
 	   	### $this->LOCATION = "../../".str_replace(".", "/", $this->LOCATION);
	   	 
 	   	return "<ul class=\"pager\">"
	   	 ."<li class=\"previous{$prev_class}\"><a href=\"?src={$this->LOCATION}/".($this->PAGE_NUMBER-1)."\">&larr; Previous</a></li>"
	   	 ."<li class=\"next{$next_class}\"><a href=\"?src={$this->LOCATION}/".($this->PAGE_NUMBER+1)."\">Next &rarr;</a></li>"
	   	 ."</ul>";
		}
	}
	
	public function search($db, $mainObject, $errors){
		if(count($mainObject->postAll()) <= 0){ return false; }
		if(!$mainObject->post("email_search")){ return false; }
		if(!$query = $db->prepare("SELECT * FROM {$mainObject->usertbl()} WHERE email LIKE ?", array("%". $mainObject->post("name")."%"))){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
		if($query->rowCount() <= 0){ return false; }
		if(!$datas = $query->fetchAll(PDO::FETCH_ASSOC)){ return false; }
		return $datas;
	}
	
	public function f2p($num) {
	if(!is_scalar($num)) return false;
	return (float)$num * 100 . '%' ;
	}
	
}

