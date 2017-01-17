<?php if(count($message_content) <= 0){ ?>
	                 <div class="panel panel-default">
                          <?php if(count($datas) >= 1){ ?>
                            <h2 class="page-header"> Messages </h2>
                        <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
		                      <table class="table table-striped table-condensed">
		                          <thead>
		                          <tr>
		                              <th>ID</th>
		                              <th style="text-align:left;">TITLE</th>
                                    <th class="hidden-xs">BY</th>
                                    <th>DATE</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                      <?php foreach($datas as $k=>$infos){ ?>
                                            <tr>
                                                <td><?=$k+1; ?></td>
                                                <td style="text-align:left;" <?php if($infos["is_read"] == 0){ echo "class='bold'"; } ?> ><a href="?src=user.message&_bz=<?=base64_encode("mid={$infos['id']}&sum={$infos['checksum']}"); ?>" <?php if($infos["is_read"]!=0){ echo "style='color:#999;'"; } ?>> <?=html_entity_decode($infos["subject"], ENT_QUOTES, "UTF-8"); ?></a></td>
                                                <td class="hidden-xs">system</td>
                                                <td><?=$main->dateFormat($infos["add_at"]); ?></td>
                                            </tr>
                                    <?php } ?>
		                          </tbody>
		                      </table>
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                <?=$pagin->getPagin(); ?>
	           </div>
        <?php } else { ?>
        <div class="panel panel-default">
                <h2 class="page-header"> MESSAGES > READ </h2>
                        <div class="panel-heading"> <span class="pull-left badge"> <?=number_format(strlen($message_content["content"])); ?> characters </div>
		                      <table class="table table-condensed">
		                          <tbody>
                                    <tr><td style="text-align:left;text-shadow:1px 1px 0px #999;" class="bold"><?=html_entity_decode($message_content["subject"], ENT_QUOTES, "UTF-8"); ?> <br>
                                    <hr class="bg-th" style="width:100%;height:1px;"/>
                                    <small style="font-weight:normal;font-style:italic;margin-left:50%;"><?=$main->dateFormat($message_content["add_at"]); ?> by <?=$user->idToName($db, $message_content["sender_id"]); ?> </small> <br>
                                    <small style="font-weight:normal;font-style:italic;margin-left:50%;">View, <?=$main->dateFormat($read_at = ($message_content["read_at"]==0) ? time() : $message_content["read_at"]); ?> </small> <br>
                                    <small style="font-weight:normal;font-style:italic;margin-left:50%;">Expire at, <?=$main->dateFormat($read_at+$message_content["timeout"]); ?> </small>
                                    </td></tr>
                                    <tr><td style="text-align:left; padding:3%;"> <?=html_entity_decode($message_content["content"], ENT_QUOTES, "UTF-8"); ?></td></tr>
		                          </tbody>
		                      </table>
                    <hr class="bg-th" style="width:100%; height:1px;"/>
                    <a href="?src=user.message&_bz=<?=base64_encode("cid={$message_content['id']}&sum={$message_content['checksum']}"); ?>" type="submit" class="btn btn-danger">Delete this message</a>
                    </div>
        <?php } ?>