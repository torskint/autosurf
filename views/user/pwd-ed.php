<div class="container-fluid well">
            <div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Change password</h2> <br/>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        
                        <div class="form-group">
                        <label>Old password :</label>
                        <input type="password" class="form-control" name="password_actual" value="<?=$main->post("password_actual"); ?>">
                        </div>
                        <div class="form-group">
                        <label>New password</label>
                        <input type="password" class="form-control" name="password_new" value="<?=$main->post("password_new"); ?>">
                        </div>
                        <div class="form-group">
                        <label>Confirm new password</label>
                        <input type="password" class="form-control" name="password_new_confirm" value="<?=$main->post("password_new_confirm"); ?>">
                        </div>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <br/>
                        <input type="submit" name="update_password_form" value="Update your password" class="btn btn-success col-s"/>
                        <br/><br/><span> Or edit your email  ? <a href="?src=user.email-ed">Here</a></span>
                        </div>
                    </form>
                </div>
        </div>