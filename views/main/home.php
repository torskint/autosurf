
<div class="container-fluid jumbotron well">
<div class="container">
<h2 class="page-header">Offers</h2>
<div class="vip1 col-xs-12 col-sm-3">
<div class="vip-header"> <p> free</p> </div>
<div class="vip-body">
<ul class="vip-offers">
<?=$admin->text($db, "FREE_OFFERS"); ?>
</ul>
</div>
<!--
<a href="?src=<?php if(isset($_SESSION['accounts'])){ echo 'user.upgrade'; } else { echo 'main.register'; } ?>" class="btn btn-default  col-sm-push-1 col-sm-10"> Pay 0,000</a>
-->
</div>

<div class="vip2 col-sm-push-1 col-xs-12 col-sm-3 ">
<div class="vip-header"> <p>silver</p> </div>
<div class="vip-body" >
<ul class="vip-offers">
<?=$admin->text($db, "SILVER_OFFERS"); ?>
</ul>
</div>
<!--
<a href="?src=<?php if(isset($_SESSION['accounts'])){ echo 'user.upgrade'; } else { echo 'main.register'; } ?>" class="btn btn-default col-sm-push-1 col-sm-10">Pay 8,000</a>
-->
</div>

<div class="vip3 col-sm-push-2 col-xs-12 col-sm-3 ">
<div class="vip-header"> <p>gold</p> </div>
<div class="vip-body">
<ul class="vip-offers">
<?=$admin->text($db, "GOLD_OFFERS"); ?>
</ul>
</div>
<!--
<a href="?src=<?php if(isset($_SESSION['accounts'])){ echo 'user.upgrade'; } else { echo 'main.register'; } ?>" class="btn btn-default col-sm-push-1 col-sm-10">Pay 15,000</a>
-->
</div>
</div>
</div>
