<div class="container-fluid well">
	   	 <div id="signup" class="container jumbotron">
	   	   	  <form method="post">
	   	   		  <h2>Contact us</h2> <br/>
	   	   	   	<br/>
	   	   		  <div class="col-sm-8 col-sm-push-1">
	   	   		  
	   	   		  <div class="form-group">
	   	   	  <label for="email"> Email adress</label>
	   	   	  <input type="email" name="email_contactus" value="<?php if(isset($_SESSION['accounts']) && count($_SESSION['accounts']) > 0 ){ echo $_SESSION['accounts']['email']; } else { echo $main->post('email_contactus'); } ?>" class="form-control" <?php if(isset($_SESSION['accounts']) && count($_SESSION['accounts']) > 0 ){ echo "disabled"; } ?> required/>
	   	   	  <?php if(isset($_SESSION['accounts']) && count($_SESSION['accounts']) > 0 ){ ?>
	   	   	  <input type="hidden" name="uniqid" value="<?=$session->values('uniqid'); ?>" class="form-control"/>
	   	   	  <?php } ?>
	   		 </div>
	   		 <div class="form-group">
	   	   	  <label for="contactus-subject"> Subject (a-z <espace> - _)</label>
	   	   	  <input type="text" name="subject_contactus" value="<?=$main->post('subject_contactus'); ?>" class="form-control"/>
	   		 </div>
	   		 <div class="form-group">
	   	   	  <label>Message (max 600 characters)</label>
	   	   	  <textarea name="message_contactus" class="form-control" rows="10"><?=$main->post('message_contactus'); ?></textarea>
	   		 </div>
	   		 <br/>
	   	   		  
	   	   		  </div>
	   	   		  <br/>
	   	   		  <div class="col-sm-12">
	   	   		  <input type="submit" name="contactus" class="btn btn-success col-sm-3 col-sm-pus-1" value="Contact us"/>
	   	   		  <br/><br/>
	   	   		  </div>
	   	   	  </form>
	   		 </div>
		</div>
