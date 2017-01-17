		<div class="container-fluid well">
	   	 <div id="signup" class="container jumbotron">
	   	 <?php if(count($datas) >= 1){ ?>
	   	   	  <form method="post">
	   	   		  <h2>credite user</h2> <br/>
	   	   	   	<br/>
	   	   		  <div class="col-sm-5 col-sm-push-1">
	   	   		  
	   	   		  <div class="form-group">
	   	   	   		 <label>Select target user account</label>
	   	   	   	   <select name="target" class="form-control">
	   	   	   	   <option>Choose user</option>
	   	   	   	   	<?php foreach($datas as $infos){ ?>
	   	   	   	   	   <option value="<?=md5($infos["email"]); ?>"><?=$infos["username"]." - [".number_format($infos["points"])." credits]"; ?></option>
	   	   	   	   	<?php } ?>
	   	   	   	   </select>
	   	   	   	 </div>
	   	   	   	 <div class="form-group">
	   	   	   		 <label>Points value</label>
	   	   	   		 <input type="number" class="form-control" name="points" pattern="/^[0-9-]+$/" value="<?=$main->post("points"); ?>">
	   	   	   	 </div> <br/>
	   	   		  
	   	   		  </div>
	   	   		  <br/>
	   	   		  <div class="col-sm-12">
	   	   		  <input type="submit" name="credite_user" class="btn btn-sm btn-success" value="Credite an account"/>
	   	   		  <br/><br/> <br/>
	   	   		  </div>
	   	   	  </form>
	   	   	  
	   	   	  <?php } else { ?>
	   	   		  <span class="not-found"></span>
	   	   	  <?php } ?>
	   	   	  
	   		 </div>
		</div>