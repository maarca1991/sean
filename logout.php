<?php
	include('connect.php');

	unset($_SESSION[SESS_PRE.'_SESS_USER_ID']);
	unset($_SESSION[SESS_PRE.'_SESS_USER_NAME']);
	
	$db->location(SITEURL);
?>