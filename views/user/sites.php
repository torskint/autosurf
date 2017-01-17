
            <div  class="panel panel-default">
            <h2 class="page-header">Your sites</h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-striped table-condensed">
		                          <thead>
		                          <tr>
		                              <th>ID</th>
		                              <th style="text-align:left;">NAME</th>
                                   <th class="hidden-xs" style="text-align:left;">TITLE</th>
                                    <th><span class="visible-xs glyphicon glyphicon-ok"></span> <span class="hidden-xs">STATUS</span> </th>
                                    <th><span class="visible-xs glyphicon glyphicon-eye-open"></span> <span class="hidden-xs">VIEWS</span></th>
                                    <th><span class="visible-xs glyphicon glyphicon-globe"></span> <span class="hidden-xs">SURF</span></th>
                                    <th><span class="visible-xs glyphicon glyphicon-globe"></span> <span class="hidden-xs">LAST SURF</span></th>
                                    <th class="hidden-xs">TOTAL</th>
                                    <th>DATE</th>
                                    <th>EDIT</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php
                                        $total_total_views_receive=0;
                                        $total_surfbar_points=0;
                                        $total_total_views_points_attribuate=0;
                                    ?>
                                    <?php foreach($datas as $infos){ ?>
                                            <tr>
                                                <td><?=$i+1; ?></td>
                                                <td style="text-align:left;"><a href="<?=$infos["url"]; ?>" target="_blank"><?=$infos["name"]; ?></a></td>
                                                <td class="hidden-xs" style="text-align:left;"><?=$sites->getTitle($infos["url"]); ?></td>
                                                <td><?=$main->status_icon($infos["status"]); ?></td>
                                                <td class="hidden-xs"><?=number_format($infos["total_views_receive"]); ?></td>
                                                <td <?php if($infos["actual_surfbar_points"] > 0){ echo "class='text-success'"; } else { echo "class='text-danger'"; } ?>><?=number_format($infos["actual_surfbar_points"]); ?> </td>
                                                <td><?=$main->dateFormat($infos["last_views_at"]); ?></td>
                                                
                                                <td><?=number_format($infos["total_views_points_attribuate"]); ?></td>
                                                <td><?=$main->dateFormat($infos["add_at"]); ?></td>
                                                <td>
                                                    <form method="post" action="?src=user.sites-ed">
                                                        <input type="hidden" name="site_id" value="<?=$infos["id"]; ?>">
                                                        <button type="submit" name="site_datas" class="glyphicon glyphicon-edit btn btn-success" value="edit"></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                                $total_total_views_receive += $infos["total_views_receive"];
                                                $total_surfbar_points += $infos["actual_surfbar_points"];
                                                $total_total_views_points_attribuate += $infos["total_views_points_attribuate"];
                                            ?>
                                    <?php $i++; } ?>
                                    <tr class="hidden total-stats">
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>TOTAL / PAGE</td>
                                           <td><?=number_format($total_total_views_receive); ?></td>
                                            <td><?=number_format($total_surfbar_points); ?></td>
                                            <td><?=number_format($total_total_views_points_attribuate); ?></td>
                                            <td>&nbsp;</td>
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