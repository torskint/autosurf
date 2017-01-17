
	                 <div class="panel panel-default">
                          <?php if(count($datas) >= 1){ ?>
                            <h2 class="page-header"> USER'S BLACKLIST </h2>
                        <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
		                      <table class="table table-condensed">
                                <thead>
		                          <tr>
		                              <th>ID</th>
		                              <th>ROWS</th>
                                    <th>OWNER</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                      <?php $i=1; foreach($datas as $site_id=>$stats){ $siteInfos = $sites->idToSite($db, $site_id , $errors); ?>
                                            <tr class="tr-parent">
                                            
                                                <td class="bold" style="border:0;"><?=$i; ?></td>
                                                <td style="border:0;">
                                                
                                                <table class="table table-condensed">
                                                <thead>
		                                          <tr>
		                                              <th>ID</th>
		                                              <th>NAME</th>
                                                    <th class="hidden-xs">OWNER</th>
                                                    <th class="hidden-xs">BLACKLISTED BY</th>
                                                    <th>DATE</th>
                                                    <th>ACT.</th>
		                                          </tr>
		                                          </thead>
                                                <tbody>
                                                <?php foreach($stats as $k=>$v){ ?>
                                                <tr>
                                                <td><?=$k+1; ?></td>
                                                <td><a class="disable" href="<?=$siteInfos['url']; ?>" target="_new"><?=$siteInfos["name"]; ?></a></td>
                                                <td class="hidden-xs"><?=$user->idToName($db, $siteInfos["user_id"]); ?></td>
                                                <td class="hidden-xs"><?=count($stats)."/".$user->count($db); ?> - <span class="badge"><?=substr(count($stats)/$user->count($db)*100, 0, 4); ?> % </span></td>
                                                <td><?=$main->dateFormat($siteInfos["add_at"]); ?></td>
                                                <td>by abuses</td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                                </table>
                                                </td>
                                                
                                                <td class="bold" style="border:0;"> <?=$user->idToName($db, $siteInfos["user_id"]); ?></td>
                                                
                                            </tr>
                                    <?php $i++; } ?>
		                          </tbody>
		                      </table>
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                <?=$pagin->getPagin(); ?>
	           </div>