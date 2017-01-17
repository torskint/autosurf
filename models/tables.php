<?php

class tables {
	
	public function __construct($db){}
	
	//TABLES LISTE
	
	public function surfbar_tbln(){
		return "surfbar";
	}
	
	public function admin_points_tbln(){
		return "admin_points_settings";
	}
	
	public function user_tbln(){
		return "accounts";
	}
	
	public function messenger_tbln(){
		return "messenger";
	}
	
	public function sites_tbln(){
		return "sites";
	}
	
	public function sessions_tbln(){
		return "sessions";
	}
	
	public function mail_tbln(){
		return "mail";
	}
	
	public function paypal_credits_offers_tbln(){
		return "paypal_buy_credit_offers";
	}
	
	public function vip_tbln(){
		return "vip_accounts";
	}
	
	public function adminVip_tbln(){
		return "admin_vip_settings";
	}
	
	public function blacklist_tbln(){
		return "blacklist";
	}
	
	public function denied_domains_tbln(){
		return "denied_domainslist";
	}
	
	public function admin_abuses_type_list_tbln(){
		return "admin_abuseslist_settings";
	}
	
	public function admin_errors_tbln(){
		return "admin_errors_settings";
	}
	
	public function admin_text_tbln(){
		return "admin_text_settings";
	}
	
	public function admin_newsletter_tbln(){
		return "newsletter";
	}
	
	public function bonus_tbln(){
		return "bonus";
	}
	
	public function banishment_tbln(){
		return "banishment";
	}
	
	public function pwdreset_tbln(){
		return "password_reset";
	}
	
	public function admin_country_tbln(){
		return "admin_country_settings";
	}
	
	public function cronJobTbl(){
		return "cron_job";
	}
	
	
	// FUNCTION 
	
	public function tbl_fromCronTbls($db, $name){
		$query=$db->prepare("SHOW TABLES LIKE ?", ["{$name}%"]);
		return $query->fetchAll(PDO::FETCH_NUM);
	}
	
	public function cronJobExec($db, $mainObject){
		if(!$query=$db->prepare("SELECT id, name FROM {$this->cronJobTbl()} WHERE add_at + timeout <= ?", [$mainObject->timestampFormat(time())])){ return false; }
		if($query->rowCount() <= 0){ return true; }
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $k=>$infos){
	   	 foreach($this->tbl_fromCronTbls($db, $infos["name"]) as $k=>$tbl){ 
	   	 $db->tblTruncate($tbl[0]);
	   	 }
	   	 if(!$db->prepare("UPDATE {$this->cronJobTbl()} SET add_at = ? WHERE id=?", [$mainObject->timestampFormat(strtotime("00:00:00")) , (int)$infos["id"]])){ return false; }
		}
		return true;
	}
	
	## LORSQU'UN UTILISATEUR EST SUPPRIMÉ, ON SUPPRIME AUSSI 
	## SES ACTIVITÉS DANS LES AUTRES TABLES. DONT VOICI LA LISTE
	public function tblArray(){
		return array(
		$this->surfbar_tbln(),
		"histo_".$this->surfbar_tbln(),
		$this->sites_tbln(),
		"histo_".$this->sites_tbln(),
		$this->sessions_tbln(),
		"histo_".$this->sessions_tbln(),
		$this->vip_tbln(),
		$this->blacklist_tbln(),
		"histo_".$this->blacklist_tbln(),
		$this->bonus_tbln(),
		"histo_".$this->bonus_tbln(),
		"histo_".$this->user_tbln(),
		$this->banishment_tbln(),
		$this->pwdreset_tbln(),
		$this->messenger_tbln()
		);
	}
}