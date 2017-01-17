
	   	   		  
		   	   		  <div class="panel panel-default">
	   	   	   	   <?php if(count($myReferralsList) >= 1){ ?>
	   	   	   	   	<h2 class="page-header">Referrals <?/*=$main->all_total($allReferrals);*/ ?> </h2>
	   	   	   	   <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span></div>
			   	   		<table class="table table-condensed">
			   	   	   	 <thead>
			   	   	   	 <tr>
			   	   	   		 <th>ID</th>
	   	   	   	   		<th>NAME</th>
			   	   	   		 <th class="hidden-xs">ACCOUNT</th>
	   	   	   	   		<th class="hidden-xs">COUNTRY</th>
	   	   	   	   		<th class="hidden-xs">BALANCE</th>
	   	   	   	   		<th>GAIN</th>
	   	   	   	   		<th class="hidden-xs">STATUS</th>
	   	   	   	   		<th class="hidden-xs">DATE</th>
	   	   	   	   		<th>RELEASE</th>
	   	   	   	   		<th>TH</th>
			   	   	   	 </tr>
			   	   	   	 </thead>
			   	   	   	 <tbody>
	   	   	   	   		<?php $total = 0; foreach($myReferralsList as $k=>$infos){ ?>
	   	   	   	   	   		 <tr>
	   	   	   	   	   	   	  <td><?=$k+1; ?></td>
	   	   	   	   	   	   	  <td><?=$infos["username"]; ?></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$user->accountType((int)$infos["member_type"]); ?></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$user->idToCountry($db, (int)$infos["country"]); ?></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=number_format((int)$infos["points"]); ?></td>
	   	   	   	   	   	   	  <td><?=number_format((int)$infos["points_for_referer"]); ?></td>
	   	   	   	   	   	   	  <?php if((int)$infos["member_type"] <= 4){ ?>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$main->status_icon(1); ?> active</td>
	   	   	   	   	   	   	  <?php } else { ?>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$main->status_icon(0); ?> wait...</td>
	   	   	   	   	   	   	  <?php } ?>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$main->dateFormat($infos["signup_at"]); ?></td>
	   	   	   	   	   	   	  <td>
	   	   	   	   	   	   		  <form method="post">
	   	   	   	   	   	   		  <input type="hidden" name="ref_infos" value="<?="name={$infos["username"]}&uid={$infos["uniqid"]}"; ?>">
	   	   	   	   	   	   		  <input type="submit" name="lost_ref" class="btn btn-danger" value="lose for <?=number_format($reward->lostReferralsPrice($db, $main, $infos["id"], $infos["points_for_referer"])); ?>"/>
	   	   	   	   	   	   		  <td>
	   	   	   	   	   	   	   	   <input type="checkbox" name="action" value="CONFIRM_PROCESS"/>
	   	   	   	   	   	   		  </td>
	   	   	   	   	   	   		  </form>
	   	   	   	   	   	   	  </td>
	   	   	   	   	   		 </tr>
	   	   	   	   		<?php $total += $infos["points_for_referer"]; } ?>
	   	   	   	   	   		 <tr class="total-stats">
	   	   	   	   	   	   	  <td>&nbsp;</td>
	   	   	   	   	   	   	  <td>TOTAL / PAGE</td>
	   	   	   	   	   	   	  <td class="hidden-xs">&nbsp;</td>
	   	   	   	   	   	   	  <td class="hidden-xs">&nbsp;</td>
	   	   	   	   	   	   	  <td class="hidden-xs">&nbsp;</td>
	   	   	   	   	   	   	  <td><?=number_format($total); ?></td>
	   	   	   	   	   	   	  <td>&nbsp;</td>
	   	   	   	   	   	   	  <td class="hidden-xs">&nbsp;</td>
	   	   	   	   	   	   	  <td class="hidden-xs">&nbsp;</td>
	   	   	   	   	   	   	  <td>&nbsp;</td>
	   	   	   	   	   	   	  <td>&nbsp;</td>
	   	   	   	   	   		 </tr>
			   	   	   	 </tbody>
			   	   		</table>
	   	   	  <?php } else { ?>
	   	   		  <span class="not-found"></span>
	   	   	  <?php } ?>
	   		 <?=$pagin->getPagin(); ?>
				</div>