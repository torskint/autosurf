<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title> Installation | <?=str_replace("-", " ", $page); ?> </title>
	<link href="../views/templates/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="../views/templates/assets/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="../views/templates/assets/css/style.min.css" rel="stylesheet">
	<?php $colors = "#419641"; require("../views/templates/assets/css/style.php"); ?>
	
	<script src="../views/templates/assets/js/jquery.min.js"></script>
	<script src="../views/templates/assets/js/bootstrap.min.js"></script>
	<script> $(function(){ $("#js-ok, i").removeClass("hidden"); }); </script>
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
  </head>
<body>
<div class="container-fluid">

	<nav class="navbar navbar-inverse">
	   <div class="container-fluid">
		<div class="navbar-header">
		<a href="?install=home&reinit=true&__key=<?=@md5($_SESSION["key"]); ?>"  class="btn btn-warning pull-right">Reset</a>
		  <a class="navbar-brand" href="?install=home"> &nbsp; Installation </a>
		</div>
	</div>
	 </div>
	</nav>
	<!--Fin du menu haut-->
	