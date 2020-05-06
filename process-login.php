<?php
	include("connect.php");
	
	$email 		= $db->clean($_POST['email']);
	$password 	= $db->clean($_POST['password']);

	if($email!="" && $password!="" && !filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		$check_user_r = $db->getData('employee', '*', "email = '".$email."' AND  password = '".md5($password)."' AND isDelete=0");
		if(@mysqli_num_rows($check_user_r)>0)
		{
			$check_user_d = @mysqli_fetch_assoc($check_user_r);
			
			$id = $check_user_d['id'];
			$name = $check_user_d['name'];
			
			$_SESSION[SESS_PRE.'_SESS_USER_ID'] = $id;
			$_SESSION[SESS_PRE.'_SESS_USER_NAME'] = $name;
			
			$_SESSION['MSG'] = 'Login_success';

			// set cart session
			$cart_id = (int) $db->getValue('cart', 'id', 'order_status=1 AND user_id=' . (int)$_SESSION[SESS_PRE.'_SESS_USER_ID'] . ' AND isDelete=0');
			if( $cart_id > 0 )
				$_SESSION[SESS_PRE.'_SESS_CART_ID'] = $cart_id;

			//$db->location(SITEURL."my-account/");
			$db->location(SITEURL);
			exit;
		}
		else
		{
			$_SESSION['MSG'] = 'Invalid_Email_Password';
			//$db->location(SITEURL);
			$db->location(SITEURL.'login/');
		}
	}
	else
	{
		$_SESSION['MSG'] = 'Something_Wrong';
		//$db->location(SITEURL);
		$db->location(SITEURL.'login/');
	}
?>