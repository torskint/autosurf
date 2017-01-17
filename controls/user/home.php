<?php

try{
	$session->autorized($db, $main, $tables, $vip, $errors);	 
	
	$referrals = $user->countActiveReferrals($db, $session, $errors);
	if(!isset($_SESSION["accounts"]["referrals"])){ $_SESSION["accounts"]["referrals"] = $referrals; }
	if($referrals > $session->values("referrals")){  $referrals_plus = $referrals - $session->values("referrals"); }
	else if($referrals < $session->values("referrals")){  $referrals_plus = $referrals - $session->values("referrals"); }
	else { $referrals_plus = false; }
	
	$urls = $sites->mysites($db, $session)->rowCount();
	if(!isset($_SESSION["accounts"]["urls"])){ $_SESSION["accounts"]["urls"] = $urls; }
	if($urls > $session->values("urls")){  $urls_plus = $urls - $session->values("urls"); }
	else if($urls < $session->values("urls")){  $urls_plus = $urls - $session->values("urls"); }
	else { $urls_plus = false; }
	
	$urlsYouAreBlacklisted = $abuses->blacklistCounter($db, $session);
	if(!isset($_SESSION["accounts"]["blacklist"])){ $_SESSION["accounts"]["blacklist"] = $urlsYouAreBlacklisted; }
	if($urlsYouAreBlacklisted > $session->values("blacklist")){  $blacklist_plus = $urlsYouAreBlacklisted - $session->values("blacklist"); }
	else if($urlsYouAreBlacklisted < $session->values("blacklist")){  $blacklist_plus = $urlsYouAreBlacklisted - $session->values("blacklist"); }
	else { $blacklist_plus = false; }
	
	$totalVisitsOnMySites = $sites->totalVisitsOnMySites($db, $session, $errors);
	if(!isset($_SESSION["accounts"]["total_views_receive"])){ $_SESSION["accounts"]["total_views_receive"] = $totalVisitsOnMySites; }
	if(($totalVisitsOnMySites - $session->values("total_views_receive")) > 0){  $total_views_receive_plus = $totalVisitsOnMySites - $session->values("total_views_receive"); } else { $total_views_receive_plus = false; }
	
	$userdatas = $user->userDatas($db, $session, $errors);
	if($userdatas["points"] - $session->values("points") > 0){ $balance_plus = $userdatas["points"] - $session->values("points"); } else { $balance_plus = false; }	   		
	$total_points_for_referer = $user->total_referer_referrals_points($db, $session);
	if(!isset($_SESSION["accounts"]["points_for_referer"])){ $_SESSION["accounts"]["points_for_referer"] = $total_points_for_referer;  }
	if(($total_points_for_referer - $session->values("points_for_referer")) > 0){ $referer_reward_plus = $total_points_for_referer - $session->values("points_for_referer"); } else { $referer_reward_plus = false; }
	if($userdatas["websites_visited"] - $session->values("websites_visited") > 0){ $total_websites_visited_plus = $userdatas["websites_visited"] - $session->values("websites_visited"); } else { $total_websites_visited_plus = false; }
	
	$dailyviews = $surfbar->dayViews($db, $session);
	if(empty($session->values("dailyviews"))){ $_SESSION["accounts"]["dailyviews"] = $dailyviews; }
	if($dailyviews - $session->values("dailyviews") > 0){ $dailyviews_plus = $dailyviews - $session->values("dailyviews"); } else { $dailyviews_plus = false; }
	
	$usersWhoAreblacklistedYou = $abuses->userWhoAreblacklistedYou($db, $session, $sites, $errors);
	if(!isset($_SESSION["accounts"]["blacklister"])){ $_SESSION["accounts"]["blacklister"] = intval($usersWhoAreblacklistedYou); }
	if($usersWhoAreblacklistedYou > $session->values("blacklister")){ $blacklister_plus = $usersWhoAreblacklistedYou - $session->values("blacklister"); }
	else if($usersWhoAreblacklistedYou < $session->values("blacklister")){ $blacklister_plus = $usersWhoAreblacklistedYou - $session->values("blacklister"); }
	else { $blacklister_plus = false; }
	
	if(!$errors->getFlash()){}
	
} catch(Exception $e){
	$errors->errors
		(
	   	 $e->getMessage(), 
	   	 $e->getCode()
		);
}
