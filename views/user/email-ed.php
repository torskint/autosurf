<div class="container-fluid well">
<div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Edit email</h2>
                         <br/>
                        <div class="col-sm-6 col-sm-push-2">
                        <div class="form-group">
                            <label>Old email adress :</label>
                            <input type="text" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label>New e-mail adress</label>
                            <input type="email" class="form-control" name="email_update" value="<?=$main->post("email_update"); ?>">
                        </div>
                        <div class="form-group">
                            <label>Your password</label>
                            <input type="text" class="form-control" name="password_update_email" value="<?=$main->post("password_update_email"); ?>">
                        </div>
                        </div>
                        
                        <br/>
                        <div class="col-sm-12">
                        <br/>
                        <input type="submit" class="btn btn-sm btn-success" name="update_email_form" value="Update your email"/>
                        <br/> <br/> <span> Edit your password ? <a href="?src=user.pwd-ed"> Click here !.</a></span>
                        </div>
                    </form>
                </div>
</div>




                