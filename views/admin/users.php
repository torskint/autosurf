<div  class="panel panel-default">
            <h2 class="page-header"> <?=$types[$option]; ?></h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-condensed">
		                          <thead>
		                          <tr>
                                    <th class="hidden-xs">No.</th>
		                              <th>NAME</th>
		                              <th class="hidden-xs">EMAIL</th>
                                    <th class="hidden-xs">COUNTRY</th>
                                    <th class="hidden-xs">BALANCE</th>
                                    <th>VISITS <span class="glyphicon glyphicon-arrow-up"></span></th>
                                    <th>VISITS <span class="glyphicon glyphicon-arrow-down"></span> </th>
                                    <th class="hidden-xs">REFERER</th>
                                    <th class="hidden-xs">DATE</th>
                                    <th class="hidden-xs">STATUS.</th>
                                    <th>TH</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <form method="post" action="?src=admin.account-de">
                                    <?php foreach($datas as $k=>$infos){ ?>
                                        <tr <?php if($session->values("id") == $infos["id"]){ echo "class='my-place'"; } ?> >
                                            <td class="hidden-xs" ><?=$k+1; ?></td>
                                            <td><?=$infos["username"]; ?></td>
                                            <td class="hidden-xs"><?=$infos["email"]; ?></td>
                                            <td class="hidden-xs"><?=$user->idToCountry($db, $infos["country"]); ?></td>
                                            <td class="hidden-xs"><?=$infos["points"]; ?></td>
                                            <td><?=$infos["websites_visited"]; ?></td>
                                            <td><?="A REVOIR"; ?></td>
                                            <td class="hidden-xs"><?=$user->idToName($db, $infos["refererid"]); ?></td>
                                            <td class="hidden-xs"><?=$main->dateFormat($infos["signup_at"]); ?></td>
                                            <td class="hidden-xs"><?php 
                                                if($main->isBanned($db, $infos["id"])){ echo "<span class='text-danger glyphicon glyphicon-ban-circle'>BAN</span>"; }
                                                else { echo "<span class='text-success glyphicon glyphicon-ok-circle'>OK</span>"; }
                                            ?></td>
                                            <td> <?php if($infos["id"] != $session->values("id")){ ?>
                                                  <input type="checkbox" name="user_ids[]" value="<?=$infos["id"]; ?>">
                                                <input type="hidden" name="from" value="<?=$page; ?>">
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td><button type="submit" name="submit_delete_account" class="btn btn-danger" value="submit delete"> Delete</span></button></td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td><button type="submit" name="submit_account_banishment" class="btn btn-warning" value="submit banishment"> Banished</span></button></td>
                                        <td><?php if($option == 999){ ?> <button type="submit" name="submit_account_validation" class="btn btn-success" value="submit validation"> Validate</span></button><?php } else { echo "&nbsp;"; } ?></td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td class="hidden-xs">&nbsp;</td>
                                        <td><input type="checkbox" name="action" value="CONFIRM_PROCESS"> </td>
                                    </tr>
                                    </form>
		                          </tbody>
		                      </table>
	                  	  <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
            <?=$pagin->getPagin(); ?>
        </div>

            <div class="tfoot ">
                    <form class="view-historic form-inline" role="form" method="post">
                          <div class="form-group">
                            <select name="type" class="form-control">
                                <?php foreach($types as $k=>$type){ ?>
                                    <option value="<?=$k; ?>"><?=$type; ?></option>
                                <?php } ?>
                            </select>
                        <input type="submit" class="btn btn-default" value="Submit form"/>
                        </div>
                      </form>
	           </div>


