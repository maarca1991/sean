<?php
	include("connect.php");
	
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0)
		$db->location(SITEURL."login/");
	else
		$id = (int) $_SESSION[SESS_PRE.'_SESS_USER_ID'];

	$uid = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
	if( $uid != '' )
	{
		$opassword  = $db->clean($_POST['old_password']);
		$npassword  = $db->clean($_POST['new_password']);
		$rpassword  = $db->clean($_POST['confirm_password']);
		
		$chpass_r = $db->getData("employee", "password", "isDelete=0 AND id=".$uid);
		$chpass_d = @mysqli_fetch_assoc($chpass_r);

		if($chpass_d['password'] == md5($opassword))
		{
			if($chpass_d['password']!= md5($npassword))
			{
				$db->update("employee", array("password"=>md5($npassword)), "id=".$uid);
				$_SESSION['MSG'] = "Success_Update";
				$db->location(SITEURL."my-account/");
				exit;
			} 
			else 
			{
			  $_SESSION['MSG'] = "Both_pass_mismatch";
			  $db->location(SITEURL."my-account/");
			  exit;
			}
		}else{
			$_SESSION['MSG'] = "Worng_Password_Old";
			$db->location(SITEURL."my-account/");
			exit;
		}
	}
	else
	{
		 $db->location(SITEURL."login/");
	}
?>