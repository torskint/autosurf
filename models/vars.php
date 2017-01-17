<?php

### REQUIREMENTS
$query_string=(isset($_GET["src"]) && preg_match("/^(main|user|admin)+(\.[a-z_-]{3,20})+(|(\/[0-9]+))$/i", $_GET["src"])) ?  $_GET["src"] : "main.404";
$query_string = explode(".", $query_string);
$DIRECTORY = !empty($query_string[0]) ? $query_string[0] : "main";
$page_ = !empty($query_string[1]) ? $query_string[1] : "home";

$param = explode("/", $page_);
$page = !empty($param[0]) ? $param[0] : "home";
unset($param[0]);
$params = !empty($param) ? $param : array(1=>1);


### CHANGE DEFAULT TIMEZONE
if(function_exists("putenv")) putenv('LC_ALL=en_EN');
if(function_exists("setlocale")) setlocale(LC_ALL , 'en_EN');
if(function_exists("date_default_timezone_set")) date_default_timezone_set("Africa/Brazzaville");
if(function_exists("ini_set")) ini_set('date.timezone', "Africa/Brazzaville");
if(!file_exists("models/db.php")){ header("location:install"); exit; }


function __autoload($class_name){
	if(is_file($class = "models/".$class_name.".php")){ require($class); }
	else { ($class_name=="db") ? header("location:install") : die("Exception . Class <mark>{$class_name}</mark> not found."); }
}

//INSTANCIATION DES OBJETS.
$db=db::idb();
$tables=new tables($db);
$user=new user($tables);
$main=new main($tables);
$errors=new errors($tables);
$session=new session($tables);
$bonus=new bonus($tables);
$sites=new sites($tables);
$surfbar=new surfbar($tables);
$abuses=new abuses($tables);
$vip=new vip($tables);
$reward=new reward($tables);
$pagin = new pagin([0=>!empty($params[1]) ? (int)$params[1]: 1]);
$admin=new admin($tables);
$mail=new mail($db, $tables, $admin->text($db, "SITE_ROOT"));
$paypal=new paypal($tables);
$messenger=new messenger($tables);

