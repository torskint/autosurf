
	   	 <div  class="panel panel-default">
	   	 
 	   	   	   	  
	   	   	   	   <?php if(count($denied_domainslist) >= 1){ ?>
	   	   	   	   	<h2 class="page-header"> SITES Prohibited</h2>
	   	   	   	   <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
			   	   		<table class="table table-condensed">
			   	   	   	 <thead>
			   	   	   	 <tr>
			   	   	   		 <th>ID</th>
			   	   	   		 <th class="hidden-xs">NAME</th>
	   	   	   	   		<th>URL</th>
	   	   	   	   		<th>DATE</th>
			   	   	   	 </tr>
			   	   	   	 </thead>
			   	   	   	 <tbody>
	   	   	   	   		<?php $i=1; foreach($denied_domainslist as $infos){ ?>
	   	   	   	   	   		 <tr>
	   	   	   	   	   	   	  <td><?=$i; ?></td>
	   	   	   	   	   	   	  <td class="hidden-xs"><?=substr($infos["name"], 0, 30); if(strlen($infos["name"]) > 30){ echo " ..."; } ?></td>
	   	   	   	   	   	   	  <td><?=substr($infos["url"], 0, 30); if(strlen($infos["url"]) > 30){ echo " ..."; } ?></td>
	   	   	   	   	   	   	  <td><?=$main->dateFormat($infos["add_at"]); ?></td>
	   	   	   	   	   		 </tr>
	   	   	   	   		<?php $i++; } ?>
			   	   	   	 </tbody>
			   	   		</table>
	   	   	  <?php } else { ?>
	   	   		  <span class="not-found">Not found ...</span>
	   	   	  <?php } ?>
	   	   	  <?=$pagin->getPagin(); ?>
		   		</div>
