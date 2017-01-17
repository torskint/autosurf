<div class="container-fluid well" style="background:#DDD;">

<?php if(!isset($_SESSION["accounts"]) && $DIRECTORY=="main" && $page=="home"){ ?>
<div class="col-sm-3 well">
<form class="col-sm-12" method="post">	   	   
	   	 <h2>Login</h2><br/>
	   		 <div class="form-group">
	   	   	  <label for="username"> Email adress</label>
	   	   	  <input type="email" name="email" value="<?=$main->post('email'); ?>" class="form-control"/>
	   		 </div>
	   		 <div class="form-group">
	   	   	  <label>Password</label>
	   	   	  <input type="password" name="password"  value="<?=$main->post('password'); ?>" class="form-control"/>
	   		 </div>
	   		 <input type="submit" class="btn btn-success" value="submit"/>
	   		 <br/><br/>
	   	 </form>
</div>
<?php } ?>

<div id="charts" class="<?php if($page == "home" && $DIRECTORY == "main" && !isset($_SESSION["accounts"])){ echo "col-sm-8"; } else { echo "col-sm-12"; } ?>">

<noscript> <br/>
	<div class="well">
		<h2 class="text-danger bold"> JAVASCRIPT DESACTIVATED</h2>
		<blockquote>
	   	 Pour une meilleure qualité de navigation sur notre site web, nous vous recommandons de bien vouloir activé la fonction javascript sur votre navigateur.
	   	 <br/> Merci de tenir compte de nos remarques.
	   	 <br/> <br/>
	   	 <small>admin</small>
		</blockquote>
	</div>
</noscript>

</div>
</div>
