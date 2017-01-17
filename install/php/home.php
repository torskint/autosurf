<?php

if(isset($_GET["git"])){ header("location: https://github.com/torskint"); exit; }

if(isset($_SESSION["ETAPE_A"])){
	
	if(isset($_POST["db_init"])){
		$_SESSION["key"] = bin2hex(openssl_random_pseudo_bytes(16));
		$errors = []; $i = 0; $tbl = array(); $nd = [];
		### FILTER VARS
		if(!preg_match("/^[a-z0-9-_\.]+$/i", $_POST["dbname"])){ $errors["dbname"] = "Invalid database name (a-z 0-9 - _ .)"; }
		if(!preg_match("/^[a-z0-9-_\.]+$/i", $_POST["dbhost"])){ $errors["dbhost"] = "Invalid database host (a-z 0-9 - _ .)"; }
		if(!preg_match("/^[a-z0-9-_\.]+$/i", $_POST["dbuser"])){ $errors["dbuser"] = "Invalid user name (a-z 0-9 - _ .)"; }
		if(!preg_match("/^[a-z0-9-_\.]{0,}$/i", $_POST["dbpwd"])){ $errors["dbpwd"] = "Invalid database password"; }
		if(count($errors) > 0){ return false; }
		### CREATE DATABASE CONNECTION CLASS FILE
		file_put_contents("../models/db.php", sprintf(file_get_contents("config/db.config"), $_POST["dbname"], $_POST["dbhost"], $_POST["dbuser"], $_POST["dbpwd"]));
		### PUSH DATABASE CONNECTION VARS IN SESSION
		$D = $_SESSION["db"] = array
		(
			"host" => $_POST["dbhost"],
			"name" => $_POST["dbname"],
			"user" => $_POST["dbuser"],
			"pwd" => $_POST["dbpwd"]
		);
		try {
			$connec = new PDO("mysql:host={$D['host']}; charset=utf8", $D['user'], $D['pwd']);
		} catch(Exception $e){
			$_SESSION["flash"][] = ["class"=>0, "message"=>"Not connected to database. <br/>".$e->getMessage()]; header("location:./?install=home"); exit(); 
		}
	   	 
		### SCAN DIR TO CHECK ALL .SQL FILES
		foreach(scandir($chm="config/") as $file){ if(is_file($chm.$file) && substr(strtolower($chm.$file), -4)==".sql"){ $tbl[] = $chm.$file; } }
		foreach($tbl as $filex){
			$query = str_replace("propub", $_POST["dbname"], file_get_contents($filex));
			if(!$connec->exec($query)){ $nd[] = $filex; }
		}
		unset($_SESSION["flash"]);
		unset($_SESSION["ETAPE_A"]);
		$_SESSION["ETAPE_B"] = true;
		### $_SESSION["flash"][] = ["class"=>1, "message"=>"Success!. Done."];
		### if(count($nd) > 0){ unset($_SESSION["flash"]); $_SESSION["flash"][] = ["class"=>0, "message"=>"Error!. Not done."]; }
		header("location:./?install=home");
		exit();
	}
	
} else if(isset($_SESSION["ETAPE_B"])){
	
	## ETAPE 3
	if(isset($_POST["adm_init"])){
		$errors = [];
		if(!preg_match("/^[a-z0-9-_\.]+$/i", $_POST["admname"])){ $errors["admname"] = "Invalid user name (a-z 0-9 - _ .)"; }
		if(!filter_var($_POST["admemail"], FILTER_VALIDATE_EMAIL)){ $errors["admemail"] = "Invalid email adress."; }
		if(!preg_match("/^[0-9]+$/i", $_POST["admpoints"])){ $errors["admpoints"] = "Invalid points value."; }
		if(!preg_match("/^[a-z0-9-_\.]{6,}$/i", $_POST["admpwd"])){ $errors["admpwd"] = "Invalid account password"; }
		if(count($errors) > 0){ return false; }
		### DATABASE CONNECTION VARS
		$D = $_SESSION['db'];
		$connec = new PDO("mysql:host={$D['host']};dbname={$D['name']};charset=utf8", $D['user'], $D['pwd']);
		### PASSWORD HASH FUNCTION
		function hashp($string){ return password_hash($string, PASSWORD_BCRYPT, array("cost"=> 6, "salt"=> bin2hex(openssl_random_pseudo_bytes(32)))); }
		### OPERATION INSERT ADMIN IN DB
		### $_SESSION["flash"][] = ["class"=>1, "message"=>"Success!. Installation completed."];
		if(!$connec->exec("INSERT INTO accounts(username, email, country, password, points, member_type, account_is_validate, account_validation_key, uniqid, refererid, signup_at) 
		VALUES('".$_POST["admname"]."', '".$_POST["admemail"]."', 26, '".hashp($_POST["admpwd"])."', '".$_POST["admpoints"]."', 1, 1, NULL, 'TorS-k9', NULL, ".time().")")){ unset($_SESSION["flash"]); $_SESSION["flash"][] = ["class"=>0, "message"=>"Error!. Not done."]; header("location:./?install=home"); exit(); }
		### 26 ---- Benin (country)
		unset($_SESSION["ETAPE_B"]);
		$_SESSION["ETAPE_C"] = true;
		header("location:./?install=home");
		exit();
	}
	
} else if(isset($_SESSION["ETAPE_C"])){
	
	if(isset($_GET["fn"]) && $_GET["fn"]==md5("__act=1&key={$_SESSION["key"]}")){
		function unlink_dir($path){
			if(is_dir($path)){ array_map(function($value){ unlink_dir($value); (is_dir($value)) ? rmdir($value) : unlink($value);
				}, glob($path . '*' , GLOB_MARK)); array_map('unlink' , glob($path. "*"));
			}
		}
		### CREATE .HTACCESS FILE
		### foreach($_SESSION["HTACCESS"] as $dir){ file_put_contents($dir.".htaccess", 
		### "deny from all\n"
		### ); }
		
		### REDIRECTION TO HOMEPAGE
		header("location:../?src=main.home");
		### CLEAR INSTALLATION DIRECTORY
		unlink_dir(dirname(__DIR__));
		session_destroy();
		exit;
	}
	
} else {
	
	### REQUIREMENTS
	function getperms($file){
		$length = strlen(decoct(fileperms($file)))-3;
		return substr(decoct(fileperms($file)), $length);
	}
	
	$require = array
	(
 	   "PHP_VERSION" => array(1, phpversion()),
		"MAIL_FUNCTION" => array(1, "Work"),
		$F="CONTROLS/" => array(0, getperms("../".strtolower($F))),
		$F="MODELS/" => array(0, getperms("../".strtolower($F))),
		$F="VIEWS/" => array(0, getperms("../".strtolower($F)))
	);
	
	if(version_compare(phpversion(), "5.0", "<")){ $require["PHP_VERSION"][0] = 0; } ## FOR ME 5.5.15
	if(!mail(bin2hex(openssl_random_pseudo_bytes(8))."@yopmail.com" , "mail test", print_r($_SERVER, true))){ $require["MAIL_FUNCTION"] = array(0, "Not work"); }
	
	/*
	* For generate .htaccess files
	*/
	$_SESSION["HTACCESS"] = $directorys = array
	(
 	   "../views/",
		"../models/",
		"../controls/"
	);
	
	foreach($directorys as $dir){
		if(getperms($dir) >= 755){ $require[str_replace("../", "", strtoupper($dir))][0] = 1; }
	}
	
	if(isset($_GET["cbx"])){
		if((int)$_GET["cbx"]==1){ $_SESSION["ETAPE_A"] = true; }
		else { $_SESSION["flash"][] = ["class"=>0, "message"=>"You must accept our terms."]; }
		header("location:./?install=home");
		exit();
	}
	
}
?>
<?php

if(isset($_GET["reinit"], $_GET["__key"], $_SESSION["key"]) && $_GET["__key"]==md5($_SESSION["key"])){
	
	if($D = $_SESSION['db']){ if($connec = new PDO("mysql:host={$D['host']};dbname={$D['name']};charset=utf8", $D['user'], $D['pwd'])){ $connec->exec("DROP DATABASE {$D['name']}"); } }
	session_destroy();
	header("location:./?install=home");
	exit();
	
} else if(isset($_GET["reinit"])){
	session_destroy();
	header("location:./?install=home");
	exit();
}
?>