<?php
	include('connect.php');

	$name = $db->clean($_REQUEST['name']);
	$email = $db->clean($_REQUEST['email']);
	$phone = $db->clean($_REQUEST['phone']);
	$message = $_REQUEST['message'];

	$rows = array(
			'name' => $name, 
			'email' => $email,
			'phone' => $phone, 
			'message' => $message,
		);
	$db->insert('contact', $rows);
	if( ISMAIL )
	{
		include("include/notification.class.php");
		$nt = new Notification();

		$subject = SITETITLE . ": Contact request received";
		include("mailbody/admin_contact.php");
		//die($body);
		$nt->sendEmail(SITEMAIL, $subject, $body);
	}



?>