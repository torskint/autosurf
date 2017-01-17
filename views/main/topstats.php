
	   	 <div  class="panel panel-default">
	   	 
 	   	   	   	  <?php if(count($TOP_SITES) >= 1){ ?>
	   	   	   	   	<h2 class="page-header">Top sites</h2>
	   	   	   	   <div class="panel-heading"> <span class="pull-right badge">1 page(s)</span> </div>
			   	   		<table class="table table-condensed">
			   	   	   	 <thead>
			   	   	   	 <tr>
			   	   	   		 <th>No.</th>
			   	   	   		 <th class="hidden-xs" >OWNER</th>
	   	   	   	   		<th>SITE NAME</th>
			   	   	   		 <th>VIEWS</th>
			   	   	   		 <th class="hidden-xs" >URL</th>
	   	   	   	   		<th>DATE</th>
			   	   	   	 </tr>
			   	   	   	 </thead>
			   	   	   	 <tbody>
			   	   	   		 <?php foreach($TOP_SITES as $k=>$infos){?>
	   	   	   	   	   	 <tr <?php if($session->values("id") == $infos["user_id"]){ echo "class='my-place'"; } ?>>
	   	   	   	   	   		 <td><?=$k+1; ?></td>
	   	   	   	   	   		 <td class="hidden-xs" ><?=$user->idToName($db, $infos["user_id"]); ?></td>
	   	   	   	   	   		 <td><?=$infos["name"]; ?></td>
	   	   	   	   	   		 <td class="bold text-danger"><?=number_format($infos["total_views_receive"]); ?></td>
	   	   	   	   	   		 <td class="hidden-xs" ><a href="<?=$infos["url"]; ?>" target="_blank"><?=$infos['url']; ?></a></td>
	   	   	   	   	   		 <td><?=$main->dateFormat($infos["add_at"]); ?></td>
	   	   	   	   	   	 </tr>
	   	   	   	   	  <?php } ?>
			   	   	   	 </tbody>
			   	   		</table>
	   	   	  
<?php } else { ?>
<span class="not-found"></span>
 <?php } ?>
</div>

	   	   	  <div  class="panel panel-default">
	   	   	   	   <?php if(count($TOP_USERS) >= 1){ ?>
	   	   	   	   <h2 class="page-header">Top users</h2>
	   	   	   	   <div class="panel-heading"> <span class="pull-right badge">1 page(s)</span> </div>
			   	   		<table class="table table-condensed">
			   	   	   	 <thead>
			   	   	   	 <tr>
			   	   	   		 <th>No.</th>
			   	   	   		 <th>NAME</th>
	   	   	   	   		<th class="hidden-xs" >ACCOUNT</th>
			   	   	   		 <th>POINTS</th>
			   	   	   		 <th class="hidden-xs" >REFERER</th>
	   	   	   	   		<th>DATE</th>
			   	   	   	 </tr>
			   	   	   	 </thead>
			   	   	   	 <tbody>
			   	   	   		 <?php foreach($TOP_USERS as $k=>$infos){ ?>
	   	   	   	   	   	 <tr <?php if($session->values("id") == $infos["id"]){ echo "class='my-place'"; } ?>>
	   	   	   	   	   		 <td><?=$k+1; ?></td>
	   	   	   	   	   		 <td><?=$infos["username"]; ?></td>
	   	   	   	   	   		 <td class="hidden-xs"><?=$user->accountType($infos["member_type"]); ?></td>
	   	   	   	   	   		 <td class="bold text-danger"><?=number_format($infos["points"]); ?></td>
	   	   	   	   	   		 <td class="hidden-xs" ><?=$user->idToName($db, $infos["refererid"]); ?></td>
	   	   	   	   	   		 <td><?=$main->dateFormat($infos["signup_at"]); ?></td>
	   	   	   	   	   	 </tr>
	   	   	   	   	  <?php } ?>
			   	   	   	 </tbody>
			   	   		</table>
	   	   	  <!--/div-->
		   	   		  <?php } else { ?>
	   	   		  <span class="not-found"></span>
	   	   	  <?php } ?>
</div>