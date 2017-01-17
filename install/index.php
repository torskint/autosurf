<?php
session_start();

$color = "#FFFFFF";

$Qs=(isset($_GET["install"]) && preg_match("/^([a-z]{3,20})+(|(\/[0-9]+))$/i", $_GET["install"])) ?  $_GET["install"] : "home";
$page = !empty($Qs) ? $Qs : "home";

?>
<?php

if(!is_dir("../models/database/") OR !is_writable("../models/database")){ }
if(is_file($db = "../models/database/db.php")){ require($db); }

if(is_file($models = "php/{$page}.php")){ require($models); }
else { header("location:?install=home"); }
require("html/templates/header.php");

if(is_file($views = "html/{$page}.php")){ require($views); }
else { header("location:?install=home"); }

require("html/templates/footer.php");
