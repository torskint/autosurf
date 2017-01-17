<?php

try {
	$ddm = $abuses->deniedDomains($db);
	$allddm = $ddm->fetchAll(PDO::FETCH_ASSOC);
	$per_page = 20;
	$denied_domainslist = $pagin->setPagin($allddm, "main.denied-domains", $per_page);
	
	$nbrpg = (round(count($allddm)/$per_page) < count($allddm)/$per_page) ? round(count($allddm)/$per_page )+1 : round(count($allddm)/$per_page);
	if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0; }
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}

