<?php

class paypal {
	
	private $vendor_email = "sell229@admin.com";
	private $env = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	private $credits_offersTbl;
	private $receiver_id = "MZYX4QCXEA5C6";
	private $currency_code = "EUR";
	
	
	public function __construct($tablesObj, $email=false, $env=false){
		if($email){ $this->vendor_email = $email; }
		if($env){ $this->env = str_replace(".sandbox", "", $this->env); }
		
		$this->credits_offersTbl = $tablesObj->paypal_credits_offers_tbln();
	}
	
	public function paypal_Id(){
		return $this->receiver_id;
	}
	
	public function target_url(){
		return $this->env;
	}
	
	public function currency_code(){
		return $this->currency_code;
	}
	
	public function getCredits_offers($db){
		if(!$query=$db->prepare("SELECT * FROM {$this->credits_offersTbl}", [])){ return []; }
		if($query->rowCount() <= 0){ return []; }
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
}


