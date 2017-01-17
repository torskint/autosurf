<div  class="panel panel-default">
            <h2 class="page-header">Text Manager <a href="?src=admin.text-add"><span id="truncate-historic" class="text-success glyphicon glyphicon-plus pull-right"></span> </a> </h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-condensed">
		                          <thead>
		                          <tr>
                                    <th>No.</th>
		                              <th>NAME</th>
                                    <th class="hidden-xs" >DETAILS MESSAGE</th>
                                    <th class="hidden-xs" >DATE</th>
                                    <th>EDIT.</th>
                                    <th>DEL.</th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php foreach($datas as $k=>$infos){ ?>
                                        <tr>
                                            <td><?=$k+1; ?></td>
                                            <td><?=$infos["name"]; ?></td>
                                            <td class="hidden-xs"><?=wordwrap(substr(htmlspecialchars($infos["message"]), 0, 100), 50, "<br>"); ?></td>
                                            <td class="hidden-xs" ><?=$main->dateFormat($infos["add_at"]); ?></td>
                                            <td>
                                                    <form method="post" action="?src=admin.text-ed">
                                                        <input type="hidden" name="name" value="<?=$infos["name"]; ?>">
                                                        <input type="hidden" name="id" value="<?=$infos["id"]; ?>">
                                                        <button type="submit" name="TEXT" class="btn btn-success" value="edit" <?php if($infos['access'] == 0){ echo "disabled"; } ?>>Edit</button>
                                                    </form>
                                             </td>
                                            <form method="post" action="?src=admin.de-manager&from=<?=$_GET["src"]; ?>">
                                                  <td>
                                                        <input type="hidden" name="id" value="<?=$infos["id"]; ?>">
                                                        <input type="hidden" name="tbl" value="<?=$admin->textTbl(); ?>">
                                                        <button type="submit" name="DE-MANAGER" class="btn btn-danger" value="delete" <?php if($infos['access'] == 0){ echo "disabled"; } ?>>Delete</button>
                                                  </td>
                                                    <td><input type="checkbox" name="action" value="CONFIRM_PROCESS" <?php if($infos['access'] == 0){ echo "disabled"; } ?>> </td>
                                           </form>
                                        </tr>
                                    <?php } ?>
		                          </tbody>
		                      </table>
                    
	                  	  <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
            <?=$pagin->getPagin(); ?>
</div>