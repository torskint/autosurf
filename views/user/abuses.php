<?php if(!empty($_SESSION["abuses"])){ ?>
<div class="container-fluid well">
<div id="signup" class="container jumbotron">
	   	   	  <form method="post">
	   	   		  <h2>Report abuses</h2> <br/>
	   	   	   	<br/>
	   	   		  <div class="col-sm-5 col-sm-push-1">
	   	   		  
	   	   		  <div class="form-group">
	   	   	   		 <label>Select abuse</label>
	   	   	   	   <select name="abuse_id" class="form-control">
	   	   	   	   	<?php foreach($abuseslist as $abuse){ ?>
	   	   	   	   		<option value="<?=$abuse["id"]; ?>"><?=$abuse["name"]; ?></option>
	   	   	   	   	<?php } ?>
	   	   	   	   </select>
	   	   	   	   <span><input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action </span>
	   	   	   	 </div>
	   	   		  
	   	   		  </div>
	   	   		  <br/>
	   	   		  <div class="col-sm-12">
	   	   		  <input type="submit" name="report_abuses" class="btn btn-success col-sm-3" value="Report abuse"/>
	   	   		  <br/><br/> <br/><span> Your blacklist ? <a href="?src=user.blacklist">Here</a></span>
	   	   		  </div>
	   	   	  </form>
	   		 </div>
		</div>
<?php } ?>




