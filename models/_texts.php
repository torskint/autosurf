<?php

class _texts {
	
	//MAIL
	public function registred_mail(array $datas){
		return array(
			"subject" => "Account confirmation",
			"body" => "Dear <b>{$datas["username"]}</b> ,"."\r\n\r\n".
			"Congratulations, registration successful !!!"."\r\n".
			"Get all acknowledgments our team."."\r\n".
			"In order to complete the registration process we recommend that you submit your email address by clicking on the following link : "."\r\n".
			"{$datas["confirmation_link"]}"."\r\n"."\r\n".
			"If clicking the link does not work, please copy and paste the URL into your browser instead."."\r\n"."\r\n".
			"If you did not make this request, you can ignore this message."."\r\n"."\r\n".
			"Sincerely, our team."
		);
	}
	
	//MAIL
	public function pwdreset_mail(array $datas){
		return array(
			"subject" => "Password reset",
			"body" => "Dear <b>{$datas["username"]}</b> ,"."\r\n"."\r\n".
			"There was recently a request to change the password for your account"."\r\n".
			"If you requested this password change, please click on the following link to reset your password:"."\r\n".
			"{$datas["pwdreset_link"]}"."\r\n"."\r\n".
			"If clicking the link does not work, please copy and paste the URL into your browser instead."."\r\n"."\r\n".
			"If you did not make this request, you can ignore this message and your password will remain the same."."\r\n"."\r\n".
			"Sincerely, our team."
		);
	}
	
	//MAIL
	public function accountConfirmed_mail(array $datas){
		return array(
			"subject" => "Account validate",
			"body" => "Dear <b>{$datas["username"]}</b> ,"."\r\n".
			"Congratulations, your account is successfuly validate. !!!
			We are very pleased to have you among our users. Below, a summary of your personal account informations. 
			
				Username : <b>{$datas["username"]}</b>
				Email : <b>{$datas["email"]}</b>
				Password : (Your account password)
				Balance : <b>{$datas["bonus"]}</b> points
				Date : <b>".date("M d, Y", $datas["date"])."</b>
				
			Soon, Dear <b>{$datas["username"]}</b> .".
			"<p> If you have any questions please contact us.<br/>Cordially, our team. </p>"
		);
	}
	
	//MP
	public function mp_newUserRefererReward(array $datas_){
		return array(
			"subject" => "[Bonus] New referrals",
			"message" => "Dear <span class='color-th bold'>{$datas_["username"]} </span>,"."<br><br>".
			"<p> Congratulations, you have received a bonus <span class='color-th bold'>{$datas_['bonus']}</span> credits for the sponsoring a new user. !!! <br> </p>".
			"<p>Get all acknowledgments our team. </p>".
			"<p> If you have any questions please contact us. <br/>Cordially, our team. </p>"
		);
	}
	
	//MP
	public function mp_pendingSiteActivated(array $datas_){
		$datas = $datas_["site"];
		return array(
			"subject" => "Site activate",
			"message" => "Dear <span class='color-th bold'>{$datas_["username"]} </span>,"."<br><br>".
			"<p> Congratulations, your site <span class='bold'>{$datas["name"]}</span> (<span class='color-th bold'>{$datas["url"]}</span>) has been activated !!! <br> </p>
			<br/>
			<ul style='margin-left:10%;list-style-type:circle;'>
				<li>Informations about <span class='color-th bold'>{$datas["url"]}</span> </li>
				<li>Name : {$datas["name"]} </li>
				<li>Surf bar points : {$datas["actual_surfbar_points"]} </li>
				<li>Add , ".date("M d, Y", $datas["add_at"])." </li>
			</ul>
			<br/>
			<p> If you have any questions please contact us . <br/>Cordially, our team.</p>"
		);
	}
	
	//MP
	public function mp_pendingSiteDeleted(array $datas_){
		$datas = $datas_["site"];
		return array(
			"subject" => "Site delete",
			"message" => "Dear <span class='color-th bold'>{$datas_["username"]} </span>," ."<br><br>".
			"<p>Your site <span class='bold'>{$datas["name"]}</span> (<span class='color-th bold'>{$datas["url"]}</span>) has been deleted."."<br></p>".
			"<p>Except error of our hand, this site has been detected as invalid and not meet standards.". "<br><br></p>".
			"<p>If you think this decision is wrong, please contact a admin.". "<br/>". "Cordially, our team.</p>"
		);
	}
	
	//MP
	public function mp_isGotenAsReferrals(array $datas){
		return array(
			"subject" => "New referer",
			"message" => "Dear <span class='color-th bold'>{$datas["username"]} </span>,"."<br><br>".
			"<p>The user <span class='bold'>{$datas["referer_name"]}</span> is your new sponsor."."<br></p>".
			"<p> You won a bonus of <span class='color-th bold'>{$datas["bonus"]} </span> credits for this. "."<br></p>".
			"<p> If you have any questions please contact us <br/><br/>Cordially, our team. </p>"
		);
	}
}