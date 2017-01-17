<div class="container-fluid well">
<div id="signup" class="container">

<form method="post">
<h2 class="page-header">Send message</h2>
<div class="col-sm-10 col-sm-push-1">
<div class="form-group">
<label>Message receiver</label>
<select name="receiver[][id]" class="form-control" multiple>
<option value="*">All users</option>
<?php foreach($users as $infos){ ?>
<option value="<?=$infos["id"]; ?>"><?=$infos["username"]; ?></option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label>Message validity</label>
<select name="validity" class="form-control">
<option>Choose validity</option>
<?php foreach($validitys as $k=>$v){ ?>
<option value="<?=$k; ?>"><?=$v; ?> seconds</option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label>Message subject</label>
<input type="text" class="form-control" name="subject_mp" value="<?=$main->post('subject_mp'); ?>"/>
</div>
<div class="form-group">
<label>Message body</label>
<textarea class="form-control" name="message_mp" rows="10"><?=$main->post("message_mp"); ?></textarea>
</div>
<label for="action">
<input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action 
</label>
<br/> <br/>
</div>

<div class="col-sm-12">
<input type="submit" name="mp" value="Send mp" class="btn btn-success col-sm-3"/>
</div>
<br/><br/>
</form>

</div>
&nbsp;
</div>