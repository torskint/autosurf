
            <div  class="panel panel-default">
            <h2 class="page-header">Errors manager <a href="?src=admin.errors-add"><span id="truncate-historic" class="text-success glyphicon glyphicon-plus pull-right"></span> </a> </h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-condensed table-responsive">
		                          <thead>
		                          <tr>
                                    <th>No.</th>
		                              <th>NAME</th>
		                              <th class="hidden-xs">DETAILS MESSAGE</th>
                                    <th class="hidden-xs" >CLASS</th>
                                    <th>EDIT.</th>
                                    <th>DEL.</th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php foreach($datas as $k=>$infos){ ?>
                                        <tr 
                                        <?php
                                        $q = explode("_", $infos["name"])[0];
                                        if($q == "SUCCESS"){ echo "class='bg-success'"; }
                                        else if($q == "ERROR"){ echo "class='bg-danger'"; }
                                        else { echo "class='bg-warning'"; } ?> >
                                            <td><?=$k+1; ?></td>
                                            <td class="bold"><?=$infos["name"]; ?></td>
                                            <td class="hidden-xs"><?=$infos["message"]; ?></td>
                                            <td class="hidden-xs"><?=$infos["class_html"]; ?></td>
                                            <td>
                                                    <form method="post" action="?src=admin.errors-ed">
                                                        <input type="hidden" name="id_errors" value="<?=$infos["id"]; ?>">
                                                        <button type="submit" name="ERRORS" class="btn btn-success" value="edit errors" <?php if($infos["access"] == 0){ echo "disabled"; } ?>>Edit</button>
                                                    </form>
                                                </td>
                                                <form method="post" action="?src=admin.de-manager&from=<?=$_GET['src']; ?>">
                                                    <td>
                                                        <input type="hidden" name="id" value="<?=$infos["id"]; ?>">
                                                        <input type="hidden" name="tbl" value="<?=$admin->errorsTbl(); ?>">
                                                        <button type="submit" name="DE-MANAGER" class="btn btn-danger" value="delete" <?php if($infos["access"] == 0){ echo "disabled"; } ?>>Delete</button>
                                                    </td>
                                                    <td><input type="checkbox" name="action" value="CONFIRM_PROCESS" <?php if($infos["access"] == 0){ echo "disabled"; } ?> > </td>
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