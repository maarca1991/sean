<?php
	include("connect.php");
	$id 		= $db->clean($_POST['id']);
	$slug 		= $db->clean($_POST['slug']);
	$newpass 	= $db->clean($_POST['newpass']);

	if($id != '' && $slug!="" && $newpass!="")
	{
		$check_user = $db->getData('employee', "*", "md5(id) = '".$id."' AND forgot_pass_string='".$slug."'");
		if(@mysqli_num_rows($check_user) > 0)
		{
			$rows = array(
						"password" => md5($newpass),
						"forgot_pass_string" =>"0"
					);
			$db->update('employee', $rows, "md5(id)='".$id."'");
			
			$_SESSION['MSG'] = 'Update_Pass';
			$db->location(SITEURL);			
		}
		else
		{
			$_SESSION['MSG'] = 'Link_Expired';
			$db->location(SITEURL."forgot-password/");
		}
	}
	else
	{
		$_SESSION['MSG'] = "INVALID_DATA";
		$db->location(SITEURL."set-new-password/".$id."/".$slug."/");
		exit;
	}
?>