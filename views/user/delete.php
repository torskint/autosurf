        <div class="container-fluid well">
            <div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Delete account</h2> <br/>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        
                        <div class="form-group">
                            <label>Account e-mail</label>
                                <input type="email" class="form-control" name="email_delete" value="<?=$session->values('email'); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Account password</label>
                                <input type="password" class="form-control" name="password_delete" value="<?=$main->post('password_delete'); ?>">
                            <span><input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action </span>
                          </div>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <br/>
                        <input type="submit" name="report_abuses" class="btn btn-sm btn-success" value="Delete your account"/>
                        <br/><br/><span> Account profile is  ? <a href="?src=user.home">Here</a></span>
                        </div>
                    </form>
                </div>
        </div>
