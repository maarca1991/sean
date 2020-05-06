<?php
	header('Content-Type: application/json');
	include("connect.php");

	if($_REQUEST['img'] == 'catimg')
	{
		$IMAGENM_SLUG 	= "_cat";
		$IMAGEPATH_T 	= CATEGORY_T;
		$IMAGEPATH_A 	= CATEGORY_A;

		$error		 = false;
		$absolutedir = dirname(__FILE__);
		$dir		 = $IMAGEPATH_A;
		$serverdir	 = $absolutedir.$dir;
		$tmp		 = explode(',',$_REQUEST['data']);
		$imgdata 	 = base64_decode($tmp[1]);
		$extension	 = strtolower(end(explode('.',$_REQUEST['name'])));

		if(isset($_SESSION['image_path']) && $_SESSION['image_path']!=""){
			unlink($IMAGEPATH_T.$_SESSION['image_path']);
		}
		$filename = time()."_".rand(1,9999999).$IMAGENM_SLUG.".".$extension;

		if ($_REQUEST['name'] != "") 
		{
			$_SESSION['image_path']=$filename;
			$handle	= fopen($IMAGEPATH_T.$filename,'w');
			fwrite($handle, $imgdata);
			fclose($handle);
			$response = array(
				"status" 	=> "success",
				"url" 		=> $IMAGEPATH_T.$filename.'?'.time(),
				"filename" 	=> $filename
			);
		}
	}
	else if($_REQUEST['img'] == 'prodimg')
	{
		$IMAGENM_SLUG 	= "_prod";
		$IMAGEPATH_T 	= PRODUCT_T;
		$IMAGEPATH_A 	= PRODUCT_A;

		$error		 = false;
		$absolutedir = dirname(__FILE__);
		$dir		 = $IMAGEPATH_A;
		$serverdir	 = $absolutedir.$dir;
		$tmp		 = explode(',',$_REQUEST['data']);
		$imgdata 	 = base64_decode($tmp[1]);
		$extension	 = strtolower(end(explode('.',$_REQUEST['name'])));

		if(isset($_SESSION['image_path']) && $_SESSION['image_path']!=""){
			unlink($IMAGEPATH_T.$_SESSION['image_path']);
		}
		$filename = time()."_".rand(1,9999999).$IMAGENM_SLUG.".".$extension;

		if ($_REQUEST['name'] != "") 
		{
			$_SESSION['image_path']=$filename;
			$handle	= fopen($IMAGEPATH_T.$filename,'w');
			fwrite($handle, $imgdata);
			fclose($handle);
			$response = array(
				"status" 	=> "success",
				"url" 		=> $IMAGEPATH_T.$filename.'?'.time(),
				"filename" 	=> $filename
			);
		}
	}
	else if($_REQUEST['img'] == 'prod_alt_img')
	{
		$IMAGENM_SLUG 	= "_prod_alt";
		$IMAGEPATH_T 	= PRODUCT_T;
		$IMAGEPATH_A 	= PRODUCT_A;

		$error		 = false;
		$absolutedir = dirname(__FILE__);
		$dir		 = $IMAGEPATH_A;
		$serverdir	 = $absolutedir.$dir;
		$tmp		 = explode(',',$_REQUEST['data']);
		$imgdata 	 = base64_decode($tmp[1]);
		$extension	 = strtolower(end(explode('.',$_REQUEST['name'])));

		if(isset($_SESSION['image_path']) && $_SESSION['image_path']!=""){
			unlink($IMAGEPATH_T.$_SESSION['image_path']);
		}
		$filename = time()."_".rand(1,9999999).$IMAGENM_SLUG.".".$extension;

		if ($_REQUEST['name'] != "") 
		{
			$_SESSION['image_path']=$filename;
			$handle	= fopen($IMAGEPATH_T.$filename,'w');
			fwrite($handle, $imgdata);
			fclose($handle);
			$response = array(
				"status" 	=> "success",
				"url" 		=> $IMAGEPATH_T.$filename.'?'.time(),
				"filename" 	=> $filename
			);
		}
	}

	print json_encode($response);
?>