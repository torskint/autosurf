
                  <div class="panel panel-default">
                        <div class="panel-heading centere" id="righ">
                            <a href="?src=user.abuses" id="report" name="report" class="pull-left"> REPORT ABUSES </a> 
                            <!-- &nbsp;&nbsp
                            &nbsp; Now <span class="pull-left badge"><?=$nbSurfer; ?> </span> surfer(s) &nbsp; -->
                            <span class="pull-right badge"> <span class="glyphicon glyphicon-user"></span> :: <?=$nbSurfer; ?></span> &nbsp;
                            
                            <a data-href="#play" id="play" name="play" class="btn btn-success glyphicon glyphicon-play"> PLAY </a> &nbsp;&nbsp
                            <a data-href="#pause" id="pause" name="pause" class="btn btn-warning glyphicon glyphicon-pause"> PAUSE </a> &nbsp;&nbsp
                            
                            <span id="counter" class="pull-right badge"><?=$surfbar->tts()+1; ?></span> &nbsp;
                            <span class="pull-right badge"> <span class="glyphicon glyphicon-eye-open"></span> :: <?=$daySitesViews; ?></span>
                        </div> &nbsp;
                    <iframe id="surfbar" src="<?=!is_file($site['url']) ? $sit9e['url'] : $defaultUrls ; ?>" frameborder="NO" noresize></iframe>
                  </div>