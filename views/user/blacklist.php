
            <div  class="panel panel-default">
            
                            <?php if(count($blacklist) >= 1){ ?>
                          <h2 class="page-header">Black list</h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
		                      <table class="table table-striped table-condensed table-responsive">
		                          <thead>
		                          <tr>
		                              <th>ID</th>
		                              <th> NAME</th>
                                    <th> VIEWS</th>
                                    <th>DATE</th>
                                    <th>ACT.</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php foreach($blacklist as $k=>$infos){ ?>
                                            <tr>
                                                <td><?=$k+1; ?></td>
                                                <td><a class="disabled" href="<?=$infos["url"]; ?>" target="_blank"><?=$infos["name"]; ?></a></td>
                                                <td><?=$infos["total_views_receive"]; ?></td>
                                                <td><?=$main->dateFormat($infos["add_at"]); ?></td>
                                                <td>
                                                    <form method="post" action="?src=user.blacklist-de">
                                                        <input type="hidden" name="site_id" value="<?=$infos["id"]; ?>">
                                                        <button type="submit" name="blacklist_cancel" class="btn btn-success" value="Activate">
                                                            <span class="glyphicon glyphicon-ok"></span>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php } ?>
		                          </tbody>
		                      </table>
                    <!--/div-->
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
            <?=$pagin->getPagin(); ?>
</div>