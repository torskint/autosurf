<?php

class surf {
	private $tts=15;
	private $interval=1000;
	private $site_id = null;
	#private $surfreward_location= "controls/admin/plugins/surfReward.php";
	private $surfreward_location = "?src=user.surf_rw";
	
	public function tts(){
		return $this->tts;
	}
	
	public function head(){
		$_SESSION["start"]=time();
		$a = "<script>\ntop.window.moveTo(0,0);\nif(document.all){\ntop.window.resizeTo(screen.availWidth,screen.availHeight);\n}\nelse if (document.layers||document.getElementById) {\nif\n(top.window.outerHeight<screen.availHeight||top.window.outerWidth<screen.availWidth){\ntop.window.outerHeight = screen.availHeight;\ntop.window.outerWidth = screen.availWidth;\n}\n}\n</script>\n";
		return $a."<script>
	   	 $(window).load(function(){
	   	 isReady = true;
	   	 count = {$this->tts};";
	}
	
	public function pause(){
		return "$('#pause').click(function(){
	   	 if(count > 0){
	   		 isReady = false; 
	   		 $('#counter').html('Pause');
	   	 }
	   	 return false;
		});";
	}
	
	public function play(){
		return "$('#play').click(function(){
	   	 if(count > 0){ isReady = true; }
	   	 return false;
		});";
	}
	
	public function foot($datas){
		#$this->site_id = $datas["id"];
		return "setInterval(function(){
	   	 if(isReady){
	   		 if(count >=0){
	   	   	  $('#counter').html(count); 
	   	   	  count--;
	   		 } else {
	   	   	  $.post
	   	   	  (
	   	   	  
	   	   	  '".$this->surfreward_location."', 
	   	   	  { SURF_VALIDATE:'". serialize(array_merge($datas, ["start"=>$_SESSION["start"]])) ."'},
	   	   		  function(datas){ 
	   	   	   	   if(datas == ''){ return 0; }
	   	   	   	   else { return 1; }
	   	   		  }
	   	   	  );
	   	   	  location.reload();
	   		 }
	   	 }
		}, $this->interval);
	});
	</script>";
	}
	
}

