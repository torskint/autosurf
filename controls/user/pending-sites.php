<?php

try{
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    $allPendingSites = $sites->allPendingSites($db);
    $pendingSites = $pagin->setPagin($allPendingSites, "user.pending-sites");
    
    $countPendingSites = count($allPendingSites);
    $nbrpg = (round(count($allPendingSites)/10) < count($allPendingSites)/10) ? round(count($allPendingSites)/10)+1 : round(count($allPendingSites)/10);       
    if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
    
    if(!empty($main->postAll("pdelete")) && $main->postAll("pending_action")==="DELETE" ){
        $sites->pendingSiteDelete($db, $messenger, $main, $session, $errors);
    } else if(!empty($main->postAll("pactivate")) && $main->postAll("pending_action")==="ACTIVATE"){
        $sites->pendingSiteActivate($db, $messenger, $main, $session, $errors);
    } else {}
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}
