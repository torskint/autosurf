<div class="container-fluid well">
	<div class="container">
		<div class="col-sm-5 text-justify">
	   	 <?=$main->textFormater($admin->text($db, "SITE_FOOTER_BLOG_L")); ?>
		</div>
		<div class="col-sm-5 col-sm-push-1 text-justify">
	   	 <?=$main->textFormater($admin->text($db, "SITE_FOOTER_BLOG_C")); ?>
		</div>
	</div>
	&nbsp;
	<blockquote class="pull-right hidden"> <strong> Written by </strong>
	<br/> <small>Tor skint</small>
	<small>For sale -> 200€</small>
	 </blockquote>
</div>
<!-- pub -->
</div>

<div class="container-fluid pre-footer">
<div class="container footer">

<address class="col-sm-6 col-sm-push-3 text-center">
&copy; <?=date("Y")?> - All rights reserved. - <a href="?src=main.contact-us"> Contact us</a> <br/>
- Imagined and directed by <a href="https://www.facebook.com/rayonx1" target="_blank" alt="www.facebook.com/us"><?=$admin->text($db, "SITE_AUTHOR"); ?> </a>
</address>

</div>
</div>

</body>
</body>
</html>