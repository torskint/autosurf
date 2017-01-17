<div class="container-fluid well">
<div id="signup" class="container">

<form method="post">
<h2 class="page-header">Send newsletter</h2>
<div class="col-sm-10 col-sm-push-1">
<div class="form-group">
<label for="subject_newsletter">subject</label>
<input type="text" class="form-control" name="subject_newsletter" value="<?=$main->post('message_newsletter'); ?>"/>
</div>
<div class="form-group">
<label for="message_newsletter">Message</label>
<textarea class="form-control" name="message_newsletter" rows="15"><?=$main->post("message_newsletter"); ?></textarea>
</div>
<label for="action">
<input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action 
</label>
<br/> <br/>
</div>

<div class="col-sm-12">
<input type="submit" name="newsletter" value="Send newsletter" class="btn btn-success col-sm-3"/>
</div>
<br/><br/>
</form>

</div>
&nbsp;
</div>