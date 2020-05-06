<?php 
	if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'pc-11' || $_SERVER['HTTP_HOST'] == '192.168.0.115'){

	    $Protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off' || $_SERVER['SERVER_PORT']==443) ? "https://" : "http://";
		
	    $SITEURL = $Protocol.$_SERVER['HTTP_HOST']."/project/sean/";
	    $ADMINURL = $Protocol.$_SERVER['HTTP_HOST']."/project/sean/apanel/";
	}
	else 
	{
	    $SITEURL = "http://devlopix.com/projects/sean/";
	    $ADMINURL = "http://devlopix.com/projects/sean/apanel/";
	}       
		
	define('SITEURL', $SITEURL);
	define('ADMINURL', $ADMINURL);
	define('SITENAME','Foxtel');
	define('SITETITLE','Foxtel');
	define('ADMINTITLE','Foxtel');
	define('CURR','&dollar;');				
?>