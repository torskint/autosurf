<?php

try {
    $session->autorized($db, $main, $tables, $vip, $errors, true);
    unset($_SESSION["TEXT"]);
    
    $query=$db->prepare("SELECT * FROM admin_text_settings", []);
    if($query->rowCount() <= 0){ return []; }
    $all=$query->fetchAll(PDO::FETCH_ASSOC);
    
    $datas = $pagin->setPagin($all, "admin.text");
    
    $nbrpg = (round(count($all)/10) < count($all)/10) ? round(count($all)/10)+1 : round(count($all)/10);                
    if($nbrpg > 0){ $pagenb = $params[1]; } else { $pagenb = 0;  }
    
    if(!$errors->getFlash()){}       
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}

