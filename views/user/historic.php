      <div  class="panel panel-default">
            <h2 class="page-header"><span class="hidden-xs">Historics / </span>
            <?php if($session->values("member_type") <= 3){ ?>
            <?=str_replace("_", " ", $datas[$option]); ?>
            <? } else { echo "All"; } ?>
            
            
            <?php if($session->values("member_type") <= 3){ ?>
                    <form class="pull-right" method="post">
                       <input type="hidden" name="HISTORIC_ID" value="<?=$option; ?>" />
                        <button type="submit" name="TRUNCATE_HISTORIC" class="btn btn-link" value="Truncate historic"/>
                        <span id="truncate-historic" class="text-danger glyphicon glyphicon-trash pull-right"></span>
                        </button>
                    </form>
             <?php } ?>
            </h2>
            
                 <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                     <?php if(count($historic) >= 1){ ?>
		                      <table class="table table-striped table-condensed">
		                          <thead>
		                          <tr>
		                              <th>ID</th>
		                              <th>NAME</th>
                                    <th class="hidden-xs"> DETAILS MESSAGE</th>
                                    <th>DATE</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php $j=(($pagenb-1)*10)+1; foreach($historic as $k=>$infos){ ?>
                                            <tr>
                                                <td><?=$j; ?></td>
                                                <td class="color-th"><?=$infos["name"]; ?></td>
                                                <td class="hidden-xs"> <?=html_entity_decode($infos["content"]); ?></td>
                                                <td><?=$main->dateFormat($infos["at"]); ?></td>
                                            </tr>
                                    <?php $j++; } ?>
		                          </tbody>
		                      </table>
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                    <?=$pagin->getPagin(); ?>
                </div>
        
        <?php if(!$displayAll){ ?>
                    <div class="tfoot">
                    <form class="view-historic form-inline" role="form" method="post">
                          <div class="form-group">
                            <select name="historic_id" class="form-control">
                                <?php foreach($datas as $key=>$histo){ ?>
                                    <option value="<?=$key; ?>"><?=str_replace("_", " ", $histo); ?></option>
                                <?php } ?>
                            </select>
                        <input type="submit" class="btn btn-default" value="views historic"/>
                        </div>
                      </form>
	           </div>
        <?php } ?>