     <div  class="panel panel-default">
            <h2 class="page-header"> Toggle status</h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-condensed">
		                          <thead>
		                          <tr>
                                    <th>No.</th>
		                              <th>NAME</th>
                                    <th>STATUS<span class="glyphicon glyphicon-arrow-down"></span> </th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <form method="post">
                                    <?php foreach($datas as $k=>$infos){ ?>
                                        <tr>
                                            <td><?=$k+1; ?></td>
                                            <td><?=$infos["name"]; ?></td>
                                            <td><?php if($infos["access"] == 0){ echo "<span class='text-danger bold'>Locked</span>"; } ?></td>
                                            <td>
                                                  <input type="checkbox" name="secure[]" value="id=<?php if(isset($infos["t_id"])){ echo $infos["t_id"]."&tbl=textTbl"; } else if(isset($infos["e_id"])){ echo $infos["e_id"]."&tbl=errorsTbl"; } ?>&access=<?=$infos['access']; ?>">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><button type="submit" name="submit" class="btn btn-success" value="submit form"> Change status </button></td>
                                        <td>&nbsp;</td>
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