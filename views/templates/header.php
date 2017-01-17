<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="description" content="<?=$admin->text($db, 'SITE_DESCRIPTION'); ?>">
		<meta name="keyword" content="<?=$admin->text($db, 'SITE_KEYWORDS'); ?>">
		<meta name="author" content="<?=$admin->text($db, 'SITE_AUTHOR'); ?>">
		<title> <?=$admin->text($db, 'SITE_TITLE'); ?> | <?=str_replace("-", " ", $page); ?> </title>
		
		<!--link href="views/assets/css/normalize.css" rel="stylesheet"/-->
		<link href="views/templates/assets/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="views/templates/assets/css/bootstrap-theme.min.css" rel="stylesheet"/-->
		<link href="views/templates/assets/css/style.min.css" rel="stylesheet"/>
		<?php require("views/templates/assets/css/style.php"); ?>
		
		<script src="views/templates/assets/js/jquery.min.js"></script>
		<script src="views/templates/assets/js/bootstrap.min.js"></script>
		<script src="views/templates/assets/js/highcharts.js"></script>
		<script src="views/templates/assets/js/exporting.js"></script>
		
		<?php
			if(isset($_GET["src"]) && $_GET["src"]=="admin.home"){
				require("views/admin/plugins/users-stats.php");
				require("views/admin/plugins/messages-stats.php");
			}
		?>
		
		<?php
			if(isset($_GET["src"]) && $_GET["src"]=="user.surfbar"){
				echo $surfbar->head();
				echo $surfbar->play();
				echo $surfbar->pause();
				echo $surfbar->foot($site);
			}
		?>
		
		<script src="views/templates/assets/js/script.js"></script>
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
					<?=$errors->display(); ?>
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span> 
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="?src=main.home"> &nbsp; <?=$admin->text($db, 'SITE_BRAND'); ?> </a>
					</div>
					
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
						
						<?php if(!isset($_SESSION["accounts"])){ ?>
						<li class="dropdown"><a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#">Members menu<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
								<li><a  href="?src=main.home"> Sign in </a></li>
								<li><a  href="?src=main.register"> Sign up </a></li>
								<li><a  href="?src=main.reset-pwd"> Request password </a></li>
							</ul>
						</li>
						<?php } else { ?>
						
						<?php
							if($session->values("member_type") == 1){
						?>
						
						<li class="dropdown"><a id="dLabel" role="button" class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="?src=main.home">Advance<span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-left" role="menu" aria-labelledby="dLabel">
								<li><a  href="?src=admin.home"> Dashboard</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.newsletter"> Send newsletter</a></li>
								<li><a  href="?src=admin.newsletter-display"> View newsletter</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.message"> Send message</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.text"> Text Settings</a></li>
								<li><a  href="?src=admin.text-add"> Add text</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.errors"> Errors Settings</a></li>
								<li><a  href="?src=admin.errors-add"> Add errors</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.secure"> Secure Settings</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.users"> User Summary</a></li>
								<li><a  href="?src=admin.users-add"> Add user</a></li>
								<li class="divider"></li>
								<li><a  href="?src=admin.banishment"> Banishment</a></li>
								<li><a  href="?src=admin.abuses">  Abuses</a></li>
							</ul>
						</li>
						
						<?php
							}
						?>
						
						<li class="dropdown"><a id="dLabel" role="button" class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="?src=main.home">Account <span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-left" role="menu" aria-labelledby="dLabel">
								<li><a  href="?src=user.home"> Your profile</a></li>
								<li><a  href="?src=user.email-ed"> Edit email</a></li>
								<li><a  href="?src=user.pwd-ed"> Edit password</a></li>
								<li><a  href="?src=user.historic"> Historics</a></li>
								<li class="divider"></li>
								<li><a class="text-danger"  href="?src=main.logout"> Log out</a></li>
								<li><a  href="?src=user.delete"> Delete account</a></li>
							</ul>
						</li>
						<li class="dropdown"><a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href=?src=main.home">Sites <span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-left " role="menu" aria-labelledby="dLabel">
								<li><a  href="?src=user.sites"> Sites list</a></li>
								<li><a  href="?src=user.sites-add"> Add site</a></li>
								<li><a  href="?src=user.sites-pt"> Credite site</a></li>
								<?php if($session->values("member_type") <=3){ ?>
								<li><a  href="?src=user.user-pt"> Credite user</a></li>
								<?php } ?>
								<li><a  href="?src=user.sites-de"> Delete sites</a></li>
								<li><a href="?src=user.surfbar"> Surf bar</a></li>
								<li class="divider"></li>
								<li><a  href="?src=user.referrals"> Referrals</a></li>
								<li><a  href="?src=user.getReferrals"> get referrals</a></li>
								<li><a  href="?src=user.blacklist"> Blacklist</a></li>
								<li><a  href="?src=user.message"> Inbox <?=(($nb=$messenger->newNotification($db, $session)) > 0) ? "<span class='badge pull-right'>$nb</span>" : '';  ?> </a></li>
							</ul>
						</li>
						<li class="dropdown"><a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="?src=main.money">Advertise <span class="caret"></span></a>
							<ul class="dropdown-menu dropdown-menu-left " role="menu" aria-labelledby="dLabel">
								<li><a  href="?src=user.dailybonus"> Day bonus</a></li>
								<?php if(isset($wxewruuvkkjjk_wanam56com)){ ?>
								<li><a  href="?src=user.purchase"> Purchase / paypal</a></li>
								<?php } ?>
								<li><a  href="?src=user.upgrade"> Become VIP</a></li>
								<li><a  href="?src=user.pending-sites"> Pending sites</a></li> 
							</ul>
						</li>
						
					<?php } ?>
					<li class="dropdown"><a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="#">Help & support <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
	   	   	   	<li><a  href="?src=main.terms"> Terms & conditions</a></li>
	   	   	   	<li><a  href="?src=main.denied-domains"> Denied domains</a></li>
	   	   	   	<li><a  href="?src=main.topstats"> Top statistics</a></li>
							<li><a  href="?src=main.contact-us"> Contact us</a></li>
	   		 		</ul>
					</li>
					
				</ul>
			</div>
		</div>
	</nav>