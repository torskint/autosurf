<div class="container-fluid well">

<div id="signin" class="form col-sm-push-4 col-sm-4 jumbotron">
	   <form method="post">
	   	 <h2>Confirm account</h2> <br/>
	   	 <div class="login-profile">
	   	 <div class="login-profile-photo img-circle"><span class="icon-user glyphicon glyphicon-user"></span></div>
	   	 </div> <br/>
	   		 <div class="form-group">
	   	   	  <label for="username"> Confirmation key </label>
	   	   	  <input type="text" class="form-control" name="ckey" value="<?=$main->get('ckey'); ?>" placeholder="account confirmation key" autofocus>
	   		 </div> <br/>
	   		 <input class="btn btn-default" type="submit" value="CONFIRM ACCOUNT"/>
	   		 <br/><br/><span> Lost your password ? <a href="?src=main.home">abort operation ? </a></span>
	   	 </form>
		</div>
 </div>
