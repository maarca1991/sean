<?php
	include("connect.php");
	include("include/notification.class.php");

	$email 	= $db->clean($_POST['email']);

	if($email!="" && !filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		$check_user_r = $db->getData('employee', '*', "email = '".$email."' AND isDelete=0");
		
		if(@mysqli_num_rows($check_user_r)>0)
		{
			$check_user_d = @mysqli_fetch_assoc($check_user_r);
			$id = $check_user_d['id'];
			$name = $check_user_d['name'];
			$email = $check_user_d['email'];
			$fps = $db->generateRandomString(8);

			$rows = array("forgot_pass_string" => $fps);
			$db->update('employee', $rows, "id=".$id);

			if(ISMAIL)
			{
				$nt = new Notification();
				include("mailbody/forgot_pass_str.php");
				$subject = SITETITLE." Password Recovery";
				//die($body);
				$toemail = $email;
				$nt->sendEmail($toemail, $subject, $body);
			}
			
			$_SESSION['MSG'] = 'Success_Fsent';
			$db->location(SITEURL);
		}
		else
		{
			$_SESSION['MSG'] = 'No_Data_Found';
			$db->location(SITEURL."forgot-password/");
		}
	}
	else
	{
		$_SESSION['MSG'] = 'Something_Wrong';
		$db->location(SITEURL."forgot-password/");
	}
?>