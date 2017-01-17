<div class="container-fluid well">
            <div id="signup" class="container jumbotron">
                    <form method="post">
                        <h2>Add text</h2> <br/>
                         <br/>
                        <div class="col-sm-8 col-sm-push-1">
                        
                        <div class="form-group">
                    <label> Name</label>
                    <input type="text" name="subject_text" value="<?=$main->post('subject_text'); ?>" class="form-control"/>
                </div>
                
                <label for="action">
                <input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action 
                </label>
                <br/> <br/>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message_text" class="form-control" rows="3"><?=$main->post('message_text'); ?></textarea>
                </div>
                <br/>
                        
                        </div>
                        <br/>
                        <div class="col-sm-12">
                        <input type="submit" name="TEXT-ADD" class="btn btn-success col-sm-3" value="Submit button"/>
                        <br/><br/>
                        </div>
                    </form>
                </div>
        </div>
