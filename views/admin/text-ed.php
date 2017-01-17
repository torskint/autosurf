<div class="container">
<div class="form">

<form method="post">
<h2 class="page-header">Update text <?php if(!$access){ echo " <span class='pull-right glyphicon glyphicon-lock'></span> "; } ?> </h2> <br/>
<div class="form-group">
<label>name</label>
<input type="text" class="form-control" name="name" value="<?=$_SESSION["TEXT"]["name"]; ?>" disabled/>
</div>
<div class="form-group">
<label>Message</label>
<textarea class="form-control" name="contents" rows="5" <?php if(!$access){ echo "disabled"; } ?> ><?=isset($contents) ? $contents : null; ?></textarea>
</div>
<label for="action">
<input type="checkbox" name="action" value="CONFIRM_PROCESS" <?php if(!$access){ echo "disabled"; } ?>> confirm action 
</label>
<br/> <br/>
<input type="submit" name="Update" value="Submit form" class="btn btn-success col-sm-2" <?php if(!$access){ echo "disabled"; } ?> />
<br/><br/>
</form>

</div>
</div>