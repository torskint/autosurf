<div class="container-fluid well">
<h5 class="container small jumboton text-justify">
In addition to made that you can upgrade now in sharing your credits on our website, you can also become VIP directly via PayPal. 
This feature was built for you have more opening. 
In addition to this, you will finally be able to make your purchases credit directly if you have a PayPal account or credit card. 
In order to ensure more security during each transaction, these operations will only on the official website even PayPal.
</h5>
<br/>
<div class="container">
<div class="well jumbotron col-sm-12">
<h2 class="page-header container"> Buy credits : </h2>
<h4 class="container">
Your purchases are treated 5 minutes after the operation.
</h4>
<br/>
<form action="<?=$paypal->target_url(); ?>" method="post" target="_new">
<div class="col-sm-7 form-group">
<input type="hidden" name="on0" value="crédits">
<select name="os0" class="form-control">
<option>Choose offer</option>
<?php foreach($Credits_datas as $purchase){ ?>
	<option value="<?=$purchase['id']; ?>"><?=$purchase['name']; ?> - <?="€".$purchase['price']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-sm-4 form-group">
<input type="hidden" name="cmd" value="_s-xclick">
<!--input type="hidden" name="hosted_button_id" value="RQMH43GS4K6V4"-->
<input type="hidden" name="currency_code" value="<?=$paypal->currency_code(); ?>"/>
<input type="hidden" name="receiver_id" value="<?=$paypal->paypal_Id(); ?>"/>
<input type="hidden" name="custom" value="id=1&po=7">
<input type="submit" class="btn btn-success" value="purchase">
</div>
</form>
<br/>
</div>

</div>
&nbsp;

<div class="container">
<div class="well jumbotron col-sm-12">
<h2 class="page-header container"> Become VIP+ : </h2>
<h4 class="container">
Your purchases are treated 5 minutes after the operation.
</h4>
<br/>
<form action="<?=$paypal->target_url(); ?>" method="post" target="_new">
<div class="col-sm-7 form-group">
<input type="hidden" name="on0" value="crédits">
<select name="os0" class="form-control">
<option>Choose offer</option>
<?php foreach($Vip_datas as $purchase){ ?>
	<option value="<?=$purchase['id']; ?>"><?=$purchase['name']; ?> - <?="€".$purchase['price']; ?></option>
<?php } ?>
</select>
</div>
<div class="col-sm-4 form-group">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="currency_code" value="<?=$paypal->currency_code(); ?>"/>
<input type="hidden" name="receiver_id" value="<?=$paypal->paypal_Id(); ?>"/>
<input type="hidden" name="custom" value="id=1&po=7">
<input type="submit" class="btn btn-success" value="purchase">
</div>
</form>
<br/>
</div>

</div>
&nbsp;

</div>
