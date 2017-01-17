<div class="container-fluid well">
<h2 class="container page-header"> banishment Manager</h2>
<br/>
<div class="container">

<form method="post">
<?php foreach($datas as $id){ ?>
<div class="well jumbotron col-sm-push-1 col-sm-5 col-lg-push-1 col-lg-4 border-theme" style="margin-right:20px;">
<h2 class="page-header container"> Banishment > <span class="bold"> <?=$n=$user->idToName($db, $id); ?> </span> </h2>
<div class="form-group">
<label>Choose the reason : </label>
<select name="banishment[<?=$id; ?>][]" class="form-control">
<option>Causes</option>
<?php foreach($banishment_causes as $k=>$index){ ?>
	<option value="<?=$k; ?>"><?=$index; ?></option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label>Choose the duration : </label>
<select name="banishment[<?=$id; ?>][]" class="form-control">
<option>Duration</option>
<?php foreach($banishment_durations as $k=>$duration){ ?>
	<option value="<?=$k; ?>"><?=$duration; ?> days</option>
<?php } ?>
</select>
</div>
<br/>
</div>
&nbsp;
<?php } ?>
&nbsp;
&nbsp;
<div class="col-sm-12"> 
<input type="submit" name="complete_banishment" class="btn btn-success" value="submit banishment">
<div class="form-group">
<br/>
<input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action
</div>
</div>
</form>
</div>
&nbsp;
&nbsp;
</div>
