
        <div class="container-fluid well">
            <div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Add site</h2> <br/>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        
                        <div class="form-group">
                              <label>site name</label>
                              <input type="text" class="form-control" name="name" value="<?=$main->post("name"); ?>" placeholder="site name">
                          </div>
                        <div class="form-group">
                              <label>site url: (authorized)</label>
                            <ul class="col-sm-10 col-sm-push-1">
                            <li>http(s)://www.url.com</li>
                            <li>http(s)://url.com</li>
                            <li>http(s)://www.url.com/folder/page.html</li>
                            </ul>
                              <input type="url" class="form-control" name="url" placeholder="http://m.my-website.com" value="<?=$main->post("url"); ?>">
                        <span class="label-block"><input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action </span>
                        </div> <br/>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <br/>
                        <input type="submit" name="add_site" class="btn btn-sm btn-success" value="Add another site"/>
                        <br/><br/> <br/>
                        </div>
                    </form>
                </div>
        </div>
