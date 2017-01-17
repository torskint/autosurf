        <div class="container-fluid well">
            <div id="signup" class="container jumbotron">
            <?php if(count($formuleslist) >= 1){ ?>
                    <form method="post">
                        <h2>upgrade account</h2> <br/>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        
                        <div class="form-group">
                              <label>Select formule type</label>
                            <select name="formule_id" class="form-control">
                                <?php foreach($formuleslist as $formule){ ?>
                                    <option value="<?=$formule["id"]; ?>"><?=$formule["name"] . " - ". $formule["price"] . " points > ".$formule["timeout"]/86400 . " days"; ?></option>
                                <?php } ?>
                            </select>
                            <span class="label-block"><input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action </span>
                            <input type="hidden" name="PAYOUT" value="<?=$formule['price']*1.8; ?>"/>
                            <input type="hidden" name="OVERFLY" value="<?=($formule['price']*0.8)-43.001; ?>"/>
                          </div> <br/>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="upgrade" class="btn btn-sm btn-success" value="upgrade account"/>
                        <br/><br/> <br/> <span> about formulas VIP ? <a href="?src=main.upgrade-infos">Informations</a></span>
                        <br/><br/> <br/>
                        </div>
                    </form>
                    
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                    
                </div>
        </div>
