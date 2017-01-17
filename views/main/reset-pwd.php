
<div class="container-fluid well">
<div id="signup" class="container jumbotron">

<?php if(!empty($_SESSION["pwdreset"])){ ?>
	   	 <form method="post" action="" class="col-sm-6">
	   	 <h2>Password change</h2>
	   	 <br/>
	   	 <br/>
	   		 <div class="form-group">
	   	   	  <label for="password"> New password: </label>
	   	   	  <input type="password" name="password" value="<?=$main->post('password'); ?>" class="form-control" placeholder="New password"/>
	   		 </div>
	   		 <div class="form-group">
	   	   	  <label for="password_confirm"> Confirm new password: </label>
	   	   	  <input type="password" name="password_confirm" value="<?=$main->post('password_confirm'); ?>" class="form-control" placeholder="New password confirmation"/>
	   		 </div>
	   		 <!--div class="form-group">
	   	   	  <label for="captcha"> Captcha</label>
	   	   	  <p>A 47 F L8</p>
	   	   	  <input type="text" name="captcha" value="<?=$main->post('captcha'); ?>" class="form-control" placeholder="captcha"/>
	   		 </div-->
	   		 <input type="hidden" name="change_password_form" value="9"/>
	   		 <input type="submit" class="btn btn-success col-sm-4" value="submit"/>
	   		 <br><br/> <br/><span>  Or sign in ? <a href="?src=main.home"> here </a></span>
	   	 </form>
<?php } else { ?>
	   	 <form method="post" class="col-sm-6">
	   	 <h2>Password request</h2>
	   	 <br/>
	   	 <br/>
	   		 <div class="form-group">
	   	   	  <label for="email"> Email adress</label>
	   	   	  <input type="email" name="email" value="<?=$main->post('email'); ?>" class="form-control" placeholder="Email adress"/>
	   		 </div>
	   		 <!--div class="form-group">
	   	   	  <label for="captcha"> Captcha</label>
	   	   	  <p>A 47 F L8</p>
	   	   	  <input type="text" name="captcha" value="<?=$main->post('captcha'); ?>" class="form-control" placeholder="captcha"/>
	   		 </div-->
	   		 <input type="submit" class="btn btn-success col-sm-4" value="submit"/>
	   		 <br><br/> <br/><span>  Or sign in ? <a href="?src=main.home"> here </a></span>
	   	 </form>
<?php } ?>

</div>
</div>