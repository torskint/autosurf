<?php if(isset($_SESSION["flash"]))
{ 
	$c = ($_SESSION["flash"][0]["class"] == 1) ? "success" : "danger";
	echo "<div style='background:#ccc;' class='container well text-".$c."'> <h5>". $_SESSION["flash"][0]["message"] . "</h5></div>";
	unset($_SESSION["flash"]);
}
?>

<div class="container-fluid well">
<div id="signup" class="container">

<?php if(isset($_SESSION["ETAPE_B"])){ ?>
		  	  <form method="post" action="?install=home">
		  		  <h2>3/ - create admin account : </h2> <br/>
		  	   	<br/>
		  		  <div class="col-sm-6 col-sm-push-2">
		  		  <div class="form-group">
		  	   		 <label> User name</label>
		  	   		 <input type="text" class="form-control" name="admname" placeholder="Enter user name" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["admname"])){ echo $errors["admname"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Admin email</label>
		  	   		 <input type="text" class="form-control" name="admemail" placeholder="Enter email adress" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["admemail"])){ echo $errors["admemail"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Points</label>
		  	   		 <input type="number" class="form-control" name="admpoints" placeholder="Enter points balance" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["admpoints"])){ echo $errors["admpoints"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Admin password</label>
		  	   		 <input type="password" class="form-control" name="admpwd" placeholder="Enter a pwd (6 char. min)" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["admpwd"])){ echo $errors["admpwd"]; } ?> </span>
		  		  </div> <br/>
		  		  </div>
		  		  <br/>
		  		  <div class="col-sm-12">
		  		  <br/>
		  		  <input type="submit" name="adm_init" class="btn btn-sm btn-success" value="Create admin"/>
		  		  <br/><br/> <br/>
		  		  </div>
		  	  </form>

<?php } else if(isset($_SESSION["ETAPE_C"])){ ?>
	   		 
	   		 <h2>4/ - Installation completed : </h2> <br/>
		  		  <div class="jumbotron alert-danger">
		  		  For security reasons, we recommend that you well want to delete the installation folder. 
		  		  This operation not cause any damage to the operation of your site. You will then redirected to the homepage of this site. <br/> <br/> <br/> <br/>
		  		  <a type="button" href="./?install=home&fn=<?=md5('__act=1&key='.$_SESSION["key"]); ?>" class="btn btn-sm btn-danger pull-right"> Clean installation directory</a>
		  		  </div>
		  		  <div class="jumbotron alert-success"> A big thank you to all those who are supported me loan or by far in achieving this script. <br/>
		  		  Mainly : <span style="color:#FF9900;" class="bold"> curtis; jobed; igor; </span></span>. </div> <br/> <br/>
		  		  
<?php } else if(isset($_SESSION["ETAPE_A"])){ ?>
		  		  
		  	  <form method="post" action="?install=home">
		  		  <h2>2/ - connect to database : </h2> <br/>
		  	   	<br/>
		  		  <div class="col-sm-6 col-sm-push-2">
		  		  <div class="form-group">
		  	   		 <label> Database name</label>
		  	   		 <input type="text" class="form-control" name="dbname" placeholder="Enter database name" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["dbname"])){ echo $errors["dbname"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Database host</label>
		  	   		 <input type="text" class="form-control" name="dbhost" placeholder="Enter database host" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["dbhost"])){ echo $errors["dbhost"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Database user</label>
		  	   		 <input type="text" class="form-control" name="dbuser" placeholder="Enter database user" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["dbuser"])){ echo $errors["dbuser"]; } ?> </span>
		  		  </div> <br/>
		  		  <div class="form-group">
		  	   		 <label> Database password</label>
		  	   		 <input type="password" class="form-control" name="dbpwd" placeholder="Enter database password" value="">
		  	   		 <span class="text-danger"> <?php if(isset($errors["dbpwd"])){ echo $errors["dbpwd"]; } ?> </span>
		  		  </div> <br/>
		  		  </div>
		  		  <br/>
		  		  <div class="col-sm-12">
		  		  <br/>
		  		  <input type="submit" name="db_init" class="btn btn-sm btn-success" value="Connect database"/>
		  		  <br/><br/> <br/>
		  		  </div>
		  	  </form>

<?php } else { ?>
		  		  
		  		  <div class="col-sm-12">
		  		  <h2 class="page-header">1/ - requirements : </h2>
		  		  <div class="well bg-th" style="color:#ccc;">
		  		  Welcome to our automatic script installer <br/>
		  		  If you have any questions or suggestions please contact us < tor.skint@gmail.com >.
		  		  </div>
		  	  <br/>
		  		  <div  class="panel panel-default">
		  		  
				  		<table class="table table-condensed">
				  		<thead>
				  		<tr>
				  	   		 <th>NAME</th>
		  		  		<th> STATUS</th>
		  		  		<th> CURRENT</th>
				  		</tr>
				  		</thead>
				  		<tbody>
				  	   		 <?php $stat=0; foreach($require as $k=>$v){ if($v[0] != 1){ $isseterrors = true; }?>
		  	   		  	 <tr>
		  	   		  		 <td class="bold"><?=str_replace("_", " ", $k); ?></td>
		  	   		  		 <td><span class='<?=((int)$v[0]==1) ? "glyphicon glyphicon-ok color-th" : "glyphicon glyphicon-ban-circle text-danger" ; ?>'></span></td>
		  	   		  		 <td class="bold"><?=$v[1]; ?></td>
		  	   		  	 </tr>
		  	   		 <?php $stat += (int)$v[0]; } ?>
		  		  	<tr>
		  		  		<td class="bold">JAVASCRIPT</td>
		  		  		<td>
		  	   		  	 <span id="js-ok" class='hidden glyphicon glyphicon-ok color-th'></span>
		  	   		  	 <noscript><span class='glyphicon glyphicon-ban-circle text-danger'></span></noscript>
		  		  		</td>
		  		  		<td class="bold">
		  	   		  	 <span id="js-ok" class='hidden'> Activated </span>
		  	   		  	 <noscript> Desactivated </noscript>
		  		  		</td>
		  		  	</tr>
				  		</tbody>
				  		</table>
		  		  </div>
		  		  
		  		  <div>
		  		  <?php if($stat < 5){ ?>
		  		  <div class="jumbotron alert-danger">
		  		  Oops !!!, there was an error. <br/> This script can't be properly installed. <br/> Please correct the errors and try again.
		  		  </div>
		  		  <?php } ?>
		  		  <br/>
		  		  <form method="GET">
		  		  <input type="checkbox" name="cbx" value="1"/> Accept conditions
		  		  <button type="submit" class="btn btn-sm <?=($stat < 5) ? "btn-danger" : "btn-success";  ?> pull-right"> <?=($stat < 5) ? "Continue still " : "Next";  ?></button>
		  		  </form>
		  		  <br/><br/> <br/>
		  		  </div>
		  	  </div>
		  	  
		  <?php } ?>
		</div>
</div>