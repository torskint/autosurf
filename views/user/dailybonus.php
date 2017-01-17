
<div class="container-fluid well">

<?php if(!$points){ ?>
<div class="dailybonus">
<form method="post">
                        <div class="form-group">
                        <input type="hidden" name="daily_bonus" value="<?=time(); ?>" />
                        </div>
                        
                        <button id="btn-dailybonus" type="submit" class="btn btn-img col-sm-12k">
                            <img src="views/templates/assets/img/product.jpg"/>
                            <span><a href="http://www.facebook.com/rayonx1" target="_blank"> click here</a></span>
                        </button> <br/>
                      </form> <br/>
                    </div>
                    <?php } else { ?>
                    <!-- -->  <div  class="form jumbotron hiddeun">
                    <div class="centered" id="bonus-dis">
                    
                    <?php 
                    try{
                        
                        $errors->_throw(["type"=>2, "message"=>"You won <h1 class='text-success text-center'>".number_format($points)."</h1> crédits today. Try again in <strong id='reboursToDate' class='text-danger'>". $bonus->reboursToDate() . "</strong>"]);       
                        
                    } catch(Exception $e){
                        echo $e->getMessage();
                    }
                    ?>
                    
                    </div>
                    </div> <!-- -->
           <?php } ?>
    
      <?php if($points){ ?>
            <div  class="panel panel-default">
            <h2 class="page-header">Statistiques <?=$main->all_total($all); ?> </h2>
                            <div class="panel-heading"> <span class="pull-left badge"><?=$pagenb; ?></span> <span class="pull-right badge"><?=$nbrpg; ?> page(s)</span> </div>
                            <?php if(count($datas) >= 1){ ?>
		                      <table class="table table-condensed table-responsive">
		                          <thead>
		                          <tr>
		                              <th>NO.</th>
		                              <th> NAME</th>
                                    <th> BONUS</th>
                                    <th class="hidden-xs">DATE</th>
		                          </tr>
		                          </thead>
		                          <tbody>
                                    <?php $total = 0; foreach($datas as $k=>$infos){ ?>
                                            <tr <?php if($session->values("id") == $infos["user_id"]){ echo "class='my-place'"; } ?>>
                                                <td><?=$k+1; ?></td>
                                                <td> <?=$user->idToName($db, $infos["user_id"]); ?></td>
                                                <td><?=number_format($infos["points"]); ?></td>
                                                <td class="hidden-xs"><?=$main->dateFormat($infos["bonus_at"]); ?></td>
                                            </tr>
                                    <?php $total += $infos["points"]; } ?>
                                    <tr class="total-stats">
                                        <td>&nbsp;</td>
                                       <td>TOTAL / PAGE</td>
                                       <td><?=number_format($total); ?></td>
                                       <td class="hidden-xs">&nbsp;</td>
                                   </tr>
		                          </tbody>
		                      </table>
                    <?php } ?>
               <?=$pagin->getPagin(); ?>
        </div>
    <?php } ?>
</div>

