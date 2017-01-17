	                  	  <div class="panel panel-default">
                            <?php if(count($datas) >= 1){ ?>
                                <h2 class="page-header">Free Referrals </h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span></div>
		                      <table class="table table-condensed">
		                          <thead>
		                          <tr>
		                              <th>ID</th>
                                    <th>NAME</th>
                                    <th class="hidden-xs">COUNTRY</th>
                                    <th class="hidden-xs">BALANCE</th>
                                    <th>ACCOUNT</th>
                                    <th class="hidden-xs">STATUS</th>
                                    <th class="hidden-xs">DATE</th>
                                    <th>PAY</th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php $total = 0; foreach($datas as $k=>$infos){ ?>
                                            <tr>
                                                <td><?=$k+1; ?></td>
                                                <td><?=$infos["username"]; ?></td>
                                                <td class="hidden-xs"><?=$user->idToCountry($db, $infos["country"]); ?></td>
                                                <td class="hidden-xs"><?=number_format($infos["points"]); ?></td>
                                                <td><?=$user->accountType($infos["member_type"]); ?></td>
                                                <?php if($main->isBanned($db, $infos["id"])){ ?>
                                                <td class="hidden-xs" ><?=$main->status_icon(0); ?> BANNED</td>
                                                <?php } else { ?>
                                                <td class="hidden-xs"><?=$main->status_icon(1); ?> OK</td>
                                                <?php } ?>
                                                <td class="hidden-xs"><?=$main->dateFormat($infos["signup_at"]); ?></td>
                                                <td>
                                                    <form method="post">
                                                    <input type="hidden" name="ref_infos" value="<?="name={$infos["username"]}&uid={$infos["uniqid"]}"; ?>">
                                                    <input type="submit" name="get_ref" class="btn btn-success" value="Get for <?=number_format($reward->getReferralsPrice($db, $main,$infos["id"])); ?>"/>
                                                    <td>
                                                        <input type="checkbox" name="action" value="CONFIRM_PROCESS"/>
                                                    </td>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php } ?>
		                          </tbody>
		                      </table>
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                <?=$pagin->getPagin(); ?>
				</div>