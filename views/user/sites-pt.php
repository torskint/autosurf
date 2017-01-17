        <div class="container-fluid well">
            <div id="signup" class="container jumbotron">
            <?php if(count($datas) >= 1){ ?>
                    <form method="post">
                        <h2>credite site</h2> <br/>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        
                        <div class="form-group">
                              <label>Select target site</label>
                            <select name="site_id" class="form-control">
                                <option>Choose site</option>
                                <?php foreach($datas as $url){ ?>
                                    <?php if($url["status"] == 1){ ?>
                                        <option value="<?=$url["id"]; ?>"> <?=$url["name"]; ?> - (<?=number_format($url["actual_surfbar_points"]); ?> points) </option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                          </div>
                          <div class="form-group">
                              <label>Points value</label>
                              <input type="number" class="form-control" name="site_points" pattern="/^[0-9-]+$/" value="<?=$main->post("site_points"); ?>">
                          </div> <br/>
                        <div class="form-group">
                              <label>Visits by hour</label>
                            <select name="site_visits_by_hour" class="form-control">
                            <?php foreach([5, 10, 20, 50, 100, 150, 200, 300, 400, 500] as $k){ ?>
                            <option value="<?=$k; ?>"> <?=$k; ?> visits</option>
                            <?php } ?>
                            </select>
                          </div> <br/>
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="credite_site" class="btn btn-success col-sm-3 col-sm-pus-1" value="credite site"/>
                        <br/><br/> <br/>
                        </div>
                    </form>
                    
                    <?php } else { ?>
                        <span class="not-found"></span>
                    <?php } ?>
                    
                </div>
        </div>
