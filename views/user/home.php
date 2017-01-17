<div class="body jumbotron">
<div class="container-fluid">

<div class="well container text-center">
<div class="form-group col-sm-8 col-sm-push-2">
<h4 class="bold underline color-th">REFERER. LINK : </h4>
<input type="text" class="form-control" value="<?=$admin->text($db, 'SITE_ROOT')."?r=".$userdatas['username']; ?>"/>
</div>
</div>

<div class="col-sm-5 col-sm-push-1">
   <div class="user-profile">
	<div class="user-profile-photo img-circle"><div class="icon-user glyphicon glyphicon-user"></div></div>
   </div>
<div class="text-corps">
<div> <span>Plan</span>
	<?php 
	    $lw = (int)$userdatas['member_type'];
	    if($lw == 4){ echo "<span class='text-default'> ".$reward->status($lw)." </span>"; }
	    else if($lw == 3){ echo "<span class='text-primary'>vip ".$reward->status($lw)." </span>"; }
	    else if($lw == 2){ echo "<span class='text-success'>vip ".$reward->status($lw)." </span>"; }
	    else if($lw == 1){ echo "<strong class='text-danger'> ".$reward->status($lw)." </strong>"; }
	?> 
</div>
<div> <span>Name</span><strong> <?=$userdatas["username"]; ?> </strong> </div>
<div> <span>Email</span><?=$userdatas["email"]; ?> </div>
<div> <span>Country</span><?=$user->idToCountry($db, $userdatas["country"]); ?> </div>
<div> <span>Balance</span> <?=number_format($userdatas["points"]); if($balance_plus){ echo " ~ <strong class='badge text-primary'>+". number_format(round($balance_plus, 2)) . "</strong>";  } ?> </div>
<div> <span>Join the </span> <?=$main->dateFormat($userdatas["signup_at"]); ?> </div>
<div> <span>Referer</span> <?=($userdatas["refererid"]) ? $user->idToName($db, $userdatas["refererid"]) : "--- --- ---"; ?> </div>
<div> <span>Expiry date </span> <span id="expire"><?=$vip->vipExpiration($db, $session); ?></span></div>
<div> <span>Action </span> <a href="?src=user.email-ed">Edit account</a></div>
</div>
</div>

<div class="col-sm-push-1 col-sm-5">
<div class="blank-user-profile-photo hidden-xs"></div>
<div class="text-corps">
<div> <span class="mg"> Total url(s)</span><?=$urls; $reward->newStats_display($urls_plus); ?> </div>
<div> <span class="mg"> Referral(s)</span><?=$referrals; $reward->newStats_display($referrals_plus); ?> </div>
<div> <span class="mg"> Ref(s) reward </span><?=number_format($total_points_for_referer); if($referer_reward_plus){ echo " ~ <strong class='badge text-primary'> +" . $referer_reward_plus . "</strong>"; } ?> </div>
<div> <span class="mg">Total views</span> <?=$userdatas["websites_visited"]; if($total_websites_visited_plus){ echo " ~ <strong class='badge text-primary'> +". $total_websites_visited_plus ."</strong>"; } ?> </div>
<div> <span class="mg">Visit(s) received</span> <?=$totalVisitsOnMySites; if($total_views_receive_plus){ echo " ~ <strong class='badge text-primary'> +". $total_views_receive_plus ."</strong>"; } ?> </div>
<div> <span class="mg">Today views</span> <?=$dailyviews; if($dailyviews_plus){ echo " ~ <strong class='badge text-primary'> +". $dailyviews_plus ."</strong>"; } ?> </div>
<div> <span class="mg">your blacklist</span> <?=$urlsYouAreBlacklisted; $reward->newStats_display($blacklist_plus);  ?> </div>
<div> <span class="mg">you in blacklist</span> <?=$usersWhoAreblacklistedYou; $reward->newStats_display($blacklister_plus); ?> </div>
</div>
</div>
</div>

</div>
