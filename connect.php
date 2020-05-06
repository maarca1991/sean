<?php
	ob_start();
	error_reporting(0);
	session_start();
	date_default_timezone_set('Australia/Melbourne');
	
	include("include/define.php");
	include("include/function.class.php");

	$db = new Cart();

    if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
    {
        $tmp_user_id = (int) $db->getValue('employee', 'id', 'id=' . $_SESSION[SESS_PRE.'_SESS_USER_ID'] . ' AND isDelete=0');
        if( $tmp_user_id <= 0 )
        {
        	unset($_SESSION[SESS_PRE.'_SESS_USER_ID']);
        	unset($_SESSION[SESS_PRE.'_SESS_CART_ID']);
        	$db->location(SITEURL);
        }
    }
?>