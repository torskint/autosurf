
<div class="container-fluid well">
<div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Registration form</h2>
                         <br/>
                        <div class="col-sm-5 col-sm-push-1">
                        <div class="form-group">
                            <label for="username"> user name</label>
                            <input type="text" name="username" value="<?=$main->post('username'); ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email adress</label>
                            <input type="email" name="email" value="<?=$main->post('email'); ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="email_confirm">Confirm email</label>
                            <input type="email" name="email_confirm" value="<?=$main->post('email_confirm'); ?>" class="form-control"/>
                        </div>
                        </div>
                        <div class="col-sm-5 col-sm-push-1">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" value="<?=$main->post('password'); ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirm Password</label>
                            <input type="password" name="password_confirm" value="<?=$main->post('password_confirm'); ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                        <label for="country">your country</label>
                        <select name="country" class="form-control">
                        <option value="">Choose country</option>
                        <?php foreach($main->countryList($db) as $k=>$pays){ $k = $k+1; ?>
                        <option value="<?=$k; ?>"><?=strtolower($pays["country"]); ?> </option>
                        <?php } ?>
                        </select>
                        </div>
                        </div>
                        <br/>
                        <div class="col-sm-6">
                        <input type="submit" class="btn btn-success col-sm-6" value="Submit"/>
                        <br/><br/> <br/><span> Already registred ? <a href="?src=main.home">sign in now</a></span>
                        </div>
                    </form>
                </div>
</div>
