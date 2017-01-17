<?php

try{
     
        if(empty($_SESSION["confirm"])){
            if(!isset($mainObject->GET["key"])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND")); }
            $k=base64_decode($mainObject->GET["key"]);
            $k=explode("2", $k);
             if(!$mainObject->ckey($k[0], $mainObject->CONFIRMATION_KEY_LENGHT) OR !$mainObject->ckey($k[1], 7)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_ACCOUNT_CONFIRMATION_KEY_NOT_FOUND")); }
            
            if(!$query=$db->prepare("SELECT id FROM {$mainObject->table()} WHERE uniqid=? && member_type=? && account_is_validate IS NULL", [$k[1], 999])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
            if($query->rowCount() <= 0){ $errors->setFlash("main.home", $errors->get($db, "ERROR_CONFIRMATION_KEY_UNAVAILABLE")); }
             $_SESSION["confirm"] = ["key"=>$k[0], "uniqid"=>$k[1]]; 
            $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_CONFIRMATION_PENDING"));
            
        } else {
            if(!isset($_SESSION["logindatas"])){ $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_CONFIRMATION_PENDING")); } 
            if(!$query=$db->prepare("SELECT * FROM {$mainObject->table} WHERE email=? && account_validation_key=? && uniqid=? && member_type=? && account_is_validate IS NULL", [$_SESSION["logindatas"]["email"], $_SESSION["confirm"]["key"], $_SESSION["confirm"]["uniqid"], 999])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
            
            if($query->rowCount() > 0){
                if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
                if(!$db->prepare("UPDATE {$mainObject->table} SET account_validation_key=?, account_is_validate=?, member_type=?, points=? WHERE id=? && uniqid=?", array(NULL, 1, 4, $mainObject->NEW_USER_REWARD, $datas["id"], $_SESSION["confirm"]["uniqid"]))){ $errors->setFlash("main.home", $errors->get($db,"ERROR_ACCOUNT_NOT_VALIDATE")); }
                /*MAIL TO USER FOR BONUS && VALIDATION */
                $mailObj->send(array("date"=>$datas["signup_at"], "email"=>$datas["email"], "username"=>$datas["username"] , "bonus"=>$mainObject->NEW_USER_REWARD), "accountConfirmed_mail");
                $mainObject->setHistory($db, array("name"=>"Account validate", "content"=>"You are validate your user account.", "at"=>time()), $mainObject, $datas["id"]);
                if(!is_null($datas["refererid"])){
                    if(!$query=$db->prepare("SELECT email, username, member_type FROM {$mainObject->table} WHERE id=?", [$datas["id"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
                    if(!$d=$query->fetch(PDO::FETCH_ASSOC)){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
                    if(!$db->prepare("UPDATE {$mainObject->table} SET points=points+? WHERE id=?", [$points=$mainObject->newUser_RefererReward($d["member_type"]), $datas["refererid"]])){ $errors->_throw($errors->get($db, "ERROR_SQL_ERROR")); }
                    $mainObject->referralsRewardForReferer($db, $datas["id"], $points, $errors);
                    //MP TO REFERER FOR BONUS
                    $mpObj->send_auto($db, array("receiver_id"=>$datas["refererid"] , "bonus"=>$points), "mp_newUserRefererReward");
                }
                //MP TO ADMIN FOR NEW USER (not done)
                unset($_SESSION["logindatas"], $_SESSION["confirm"]);
                $_SESSION["first_login"] = "YES";
                if(!$query=$db->prepare("SELECT * FROM {$mainObject->table} WHERE id=? && email=? && member_type=? && account_is_validate=? && account_validation_key IS NULL", [$datas["id"], $datas["email"], 4, 1])){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
                if(!$datas=$query->fetch(PDO::FETCH_ASSOC)){ $errors->setFlash("main.home", $errors->get($db, "ERROR_SQL_ERROR")); }
                $session->refresher($db, $datas, $main, $errors);
            } else {
                $errors->setFlash("main.home", $errors->get($db, "ERROR_CONFIRMATION_KEY_UNAVAILABLE"));
                
            }
        }
    if(!$errors->getFlash()){}
    
    
} catch(Exception $e){
    $errors->errors
        (
            $e->getMessage(), 
            $e->getCode()
        );
}
