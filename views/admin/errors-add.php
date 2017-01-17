<div class="container-fluid well">
            <div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Add errors</h2> <br/>
                         <br/>
                        <div class="col-sm-8 col-sm-push-1">
                        
                        <div class="form-group">
                    <label> Name</label>
                    <input type="text" name="subject_errors" value="<?=$main->post('subject_errors'); ?>" class="form-control"/>
                </div>
                <div class="form-group">
                    <label> Type</label>
                    <select name="id_errors" class="form-control">
                    <option>Select type</option>
                    <?php foreach(["ERROR", "SUCCESS", "HOME"] as $k=>$type){ ?>
                    <option value="<?=$k; ?>"><?=$type; ?></option>
                    <?php } ?>
                    </select>
                </div>
                <label for="action">
                <input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action 
                </label>
                <br/> <br/>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message_errors" class="form-control" rows="3"><?=$main->post('message_errors'); ?></textarea>
                </div>
                <br/>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="ERRORS-ADD" class="btn btn-success col-sm-3" value="Submit button"/>
                        <br/><br/>
                        </div>
                    </form>
                </div>
        </div>
