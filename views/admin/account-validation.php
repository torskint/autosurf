<div class="container-fluid well">
<h2 class="container page-header"> Accounts validation</h2><br/>
<div class="container">

<form method="post">
<?php foreach($datas as $id){ ?>
<div class="well jumbotron col-sm-push-1 col-sm-5 col-lg-push-1 col-lg-4 border-theme" style="margin-right:20px;">
<h2 class="page-header container"> Validation > <span class="bold"> <?=$n=$user->idToName($db, $id); ?> </span> </h2>
<div class="form-group">
<label>Choose user grade : </label>
<select name="validation[<?=$id; ?>][]" class="form-control">
<option>Grades</option>
<?php foreach($member_grades as $k=>$index){ ?>
	<option value="<?=$k; ?>"><?=$index; ?></option>
<?php } ?>
</select>
</div>
<div class="form-group">
<label>User balance : </label>
<input type="number" name="validation_points[<?=$id; ?>][]" class="form-control" value="">
</div>
<br/>
</div>
&nbsp;
<?php } ?>
&nbsp;
&nbsp;
<div class="col-sm-12"> 
<input type="submit" name="complete_validation" class="btn btn-success" value="submit validation">
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
