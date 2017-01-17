
		   		  <div class="panel panel-default">
	   	   	   	 <?php if(count($pendingSites) >= 1){ ?>
	   	   	   	   <h2 class="page-header"> Awaiting</h2>
	   	   		  <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
			   	   		<table class="table table-condensed">
			   	   	   	 <thead>
			   	   	   	 <tr>
	   	   	   	   		<?php if($session->values("member_type") == 1){ ?>
	   	   	   	   	   	 <th>TH</th>
	   	   	   	   		<?php } ?>
			   	   	   		 <th>ID</th>
			   	   	   		 <th>NAME</th>
	   	   	   	   		<th class="hidden-xs">OWNER</th>
	   	   	   	   		<th class="hidden-xs">STATUS</th>
	   	   	   	   		<th>DATE</th>
	   	   	   	   		<?php if($session->values("member_type") == 1){ ?>
	   	   	   	   	   	 <th>ACT.</th>
	   	   	   	   	   	 <th>DEL.</th>
	   	   	   	   		<?php } ?>
			   	   	   	 </tr>
			   	   	   	 </thead>
			   	   	   	 <tbody>
	   	   	   	   		  <?php foreach($pendingSites as $k=>$infos){ ?>
	   	   	   	   	   		 <tr <?php if($session->values("id") == $infos["user_id"]){ echo "class='my-place'"; } ?>>
	   	   	   	   	   		 <form method="post">
	   	   	   	   	   	   	  <?php if($session->values("member_type") == 1){ ?>
	   	   	   	   	   	   		  <td>
	   	   	   	   	   	   	   	   	<input type="hidden" name="ownerUserID" value="<?=$infos["user_id"]; ?>"/>
	   	   	   	   	   	   	   	   	<input type="checkbox" name="action" value="CONFIRM_PROCESS">
	   	   	   	   	   	   		  </td>
	   	   	   	   	   	   	  <?php } ?>
	   	   	   	   	   	   	  <td><?=$k+1; ?></td>
	   	   	   	   	   	   	  <td><a class="disable" href="<?=$infos['url']; ?>" target="_new"><?=$infos["name"]; ?></a></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$user->idToName($db, $infos["user_id"]); ?></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=$main->status_icon(2); ?> wait...</td>
	   	   	   	   	   	   	  <td><?=$main->dateFormat($infos["add_at"]); ?></td>
	   	   	   	   	   	   	  <?php if($session->values("member_type") == 1){ ?>
	   	   	   	   	   	   		  <td>
	   	   	   	   	   	   	   	   	<input type="hidden" name="psite_id" value="<?=$infos["id"]; ?>">
	   	   	   	   	   	   	   	   	<input type="hidden" name="pactivate" value="PROCESS">
	   	   	   	   	   	   	   	   	<button type="submit" name="pending_action" class="btn btn-success" value="ACTIVATE">
	   	   	   	   	   	   	   	   		<span class="glyphicon glyphicon-ok"></span>
	   	   	   	   	   	   	   	   	</button>
	   	   	   	   	   	   		  </td>
	   	   	   	   	   	   		  <td>
	   	   	   	   	   	   	   	   	<input type="hidden" name="psite_id" value="<?=$infos["id"]; ?>">
	   	   	   	   	   	   	   	   	<input type="hidden" name="pdelete" value="PROCESS">
	   	   	   	   	   	   	   	   	<button type="submit" name="pending_action" class="btn btn-danger" value="DELETE">
	   	   	   	   	   	   	   	   	   <span class="glyphicon glyphicon-trash"></span>
	   	   	   	   	   	   	   		  </button>
	   	   	   	   	   	   		  </td>
	   	   	   	   	   	   	  <?php } ?>
	   	   	   	   	   	   	  </form>
	   	   	   	   	   		 </tr>
	   	   	   	   		<?php } ?>
			   	   	   	 </tbody>
			   	   		</table>
	   	   	  <?php } else { ?>
	   	   		  <span class="not-found"></span>
	   	   	  <?php } ?>
	   		 <?=$pagin->getPagin(); ?>
		   	</div><!-- /col-md-12 -->