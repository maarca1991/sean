<?php

include("connect.php");
$name 				= 	$db->clean($_POST['name']);
$email 				= 	$db->clean($_POST['email']);
$password 			= 	$db->clean($_POST['password']);

if($email!="" && $password!="")
{
	$dup_where = "email = '".$email."'";
	$r = $db->dupCheck("employee",$dup_where);
	if($r)
	{
		$_SESSION['MSG'] = "Duplicate_User";
		$db->rplocation(SITEURL."signin/");
		exit;
	}
	else
	{
		$rows 	= array(
			"name"					=> $name,
			"email"					=> $email,
			"password"				=> md5($password)
		);

		$uid = $db->insert('employee',$rows);
	
		if($uid!='')
		{
			$_SESSION['MSG'] = "Success_Signup";
			?>
			<script>
			location.href = '<?php echo SITEURL."signin/"; ?>';
			</script>
			<?php
		}
	}
}
else
{
	$_SESSION['MSG'] = 'FILL_ALL_DATA';
	$db->rplocation(SITEURL."signin/");
}
?>