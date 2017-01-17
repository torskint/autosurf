<div  class="panel panel-default">
            <h2 class="page-header">banishment Manager <a href="?src=admin.users"><span id="truncate-historic" class="text-success glyphicon glyphicon-plus pull-right"></span> </a> </h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-striped table-condensed">
		                          <thead>
		                          <tr>
                                    <th>No.</th>
		                              <th>NAME</th>
                                    <th class="hidden-xs" >CAUSE</th>
                                    <th>DATE</th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <form method="post">
                                    <?php foreach($datas as $k=>$infos){ ?>
                                        <tr>
                                            <td><?=$k+1; ?></td>
                                            <td><?=$user->idToName($db, $infos["user_id"]); ?></td>
                                            <td class="hidden-xs"><?=wordwrap(substr(htmlspecialchars($infos["cause"]), 0, 100), 50, "<br>"); ?></td>
                                            <td><?=$main->dateFormat($infos["add_at"]); ?></td>
                                            <td>
                                                  <input type="checkbox" name="user_ids[]" value="<?=$infos["user_id"]; ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><button type="submit" name="submit" class="btn btn-success" value="submit form"> unblock </button></td>
                                        <td>&nbsp;</td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td> <input type="checkbox" name="action" value="CONFIRM_PROCESS"> </td>
                                    </tr>
                                    </form>
		                          </tbody>
		                      </table>
                    
	                  	  <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
            <?=$pagin->getPagin(); ?>
</div>