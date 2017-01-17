        <div class="container-fluid well">
            <div id="signup" class="container jumbotron">
            <?php if(count($datas) >= 1){ ?>
                    <form method="post">
                        <h2>delete site</h2> <br/>
                         <br/>
                        <div class="col-sm-5 col-sm-push-1">
                        
                        <div class="form-group">
                              <label>Select target site</label>
                            <select name="site" class="form-control">
                                <option>Choose site</option>
                                <?php foreach($datas as $url){ ?>
                                    <option value="<?=$url["id"]; ?>"><?=$url["name"]; ?></option>
                                <?php } ?>
                            </select>
                            <span class="label-block"><input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action </span>
                          </div> <br/>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="delete" class="btn btn-success col-sm-3 col-sm-pus-1" value="Delete site"/>
                        <br/><br/> <br/>
                        </div>
                    </form>
                    
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                    
                </div>
        </div>
        
