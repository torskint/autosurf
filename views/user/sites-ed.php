<div class="container-fluid well">
<div id="signup" class="container jumbotron">
            <?php if(count($_SESSION["site_infos"]) >= 1){ ?>
                    <form method="post">
                        <h2>Sites edition</h2>
                         <br/>
                        <div class="col-sm-5 col-sm-push-1">
                        <div class="form-group">
                            <label>site name</label>
                              <input type="text" class="form-control" name="name" value="<?=!empty($main->post("name")) ? $main->post("name") : $_SESSION["site_infos"]["name"]; ?>">
                          </div>
                        <div class="form-group">
                            <label>site url adress: (authorized)</label>
                            <ul class="col-sm-10 col-sm-push-1">
                            <li>http(s)://www.url.com</li>
                            <li>http(s)://url.com</li>
                            <li>http(s)://www.url.com/folder/page.html</li>
                            </ul>
                            <input type="url" class="form-control" name="url" placeholder="facebook.com" value="<?=!empty($main->post("url")) ? $main->post("url") : $_SESSION["site_infos"]["url"]; ?>">
                            <br/>
                            <input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action
                        </div>
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="edit_site" class="btn btn-success col-sm-3" value="Submit form"/>
                        <br/><br/> <br/><span> Add another one ? <a href="?src=user.sites-add"> Here</a></span>
                        </div>
                    </form>
                    <?php } else { ?>
                     <span class="not-found"></span>
                  <?php } ?>
         </div>
</div>




