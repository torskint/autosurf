<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors);     
    
    unset($_SESSION["abuses"]);
    
    $site=$surfbar->surfbar($db, $abuses, $sites, $session, $errors);
    
    $nbSurfer = $surfbar->countNowSurfbarSurfers($db, $sites);
    $daySitesViews = $surfbar->dayViews($db, $session);
    $_SESSION["abuses"]=$site["id"];
    
    # $defaultUrls = $surfbar->promoteUrl($db);
    
    if(!$errors->getFlash()){}
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

