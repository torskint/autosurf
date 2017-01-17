
<div class="container-fluid well">
<div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2> Add new user</h2>
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
                            <label>Choose user grade : </label>
                            <select name="member_type" class="form-control">
                            <option>Member type</option>
                            <?php foreach($member_grades as $k=>$index){ ?>
                            <option value="<?=$k; ?>"><?=$index; ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        </div>
                        <div class="col-sm-5 col-sm-push-1">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" value="<?=$main->post('password'); ?>" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="points">Account balance</label>
                            <input type="number" name="points" value="" class="form-control"/>
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
                        <div class="col-xs-12 bold">
                        <input type="checkbox" name="me_rf" value="1"> Referrer > You
                        <br/> <br/>
                        </div>
                        
                        <div class="col-sm-6">
                        <input type="submit" class="btn btn-success col-sm-6" value="Submit"/>
                        <br/><br/>
                        </div>
                    </form>
                </div>
</div>
