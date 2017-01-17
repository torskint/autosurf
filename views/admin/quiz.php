<div class="container-fluid">
<h2 class="page-header">ADMINISTRATION</h2>
<div class="form col-sm-12">

<form method="post">
<h2 class="page-header">Edit QUIZ</h2> <br/>
<div class="form-group">
<label>Quiz</label>
<textarea class="form-control" name="contents" rows="15"><?=$quiz; ?></textarea>
</div>
<label for="action">
<input type="checkbox" name="action" value="CONFIRM_PROCESS"> confirm action 
</label>
<br/> <br/>
<input type="submit" name="update_quiz" value="Update quiz" class="btn btn-warning"/>
<br/><br/><span> See more ? <a href="?src=main.quiz"> Click here !.</a></span>
</form>

</div>
</div>