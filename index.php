<?php
session_start();

	### SETTINGS
	$colors = "#419641";
	$color = "#FFFFFF";

	
	require("models/vars.php");
	
	### ------------------------------------------------------------------------
	### REFERER 
	if(isset($_GET["r"])){ $errors->_rdr("main.referer&ruid={$_GET["r"]}"); exit; }
	
	### ------------------------------------------------------------------------
	
	if(is_file($models = "controls/{$DIRECTORY}/{$page}.php")){ require($models); } else { $errors->_rdr("main.404"); }
	require("views/templates/header.php");
	require("views/templates/addons-header.php");
	require("models/charts.php");
	if(is_file($views = "views/{$DIRECTORY}/{$page}.php")){ require($views); } else { $errors->_rdr("main.404"); }
	require("views/templates/footer.php");
	
?>