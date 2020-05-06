<?php
	include('connect.php');

	$mode = $_REQUEST['mode'];

	if( $mode == 'get_price' )
	{
		$product_id = $_REQUEST['product_id'];
		$color_id = $_REQUEST['color_id'];
		$size_id = $_REQUEST['size_id'];
		$qty = $_REQUEST['qty'];

		$price = $db->getValue('product_price', 'price', 'product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
		echo CUR.$db->num($price * $qty);
		exit;		
	}
	if( $mode == 'add_to_cart' )
	{
		$product_id = $_REQUEST['product_id'];
		$color_id = $_REQUEST['color_id'];
		$size_id = $_REQUEST['size_id'];
		$qty = $_REQUEST['qty'];

		if(isset($_SESSION[SESS_PRE.'_SESS_USER_ID']))
			$user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
		else
			$user_id = 0;

		$db->add_to_cart($user_id, $product_id, $color_id, $size_id, $qty);
	}

	if( $mode == 'add_general' )
	{
		$product_id = $_REQUEST['product_id'];
		$color_id = 0;
		$size_id = 0;
		$qty = 1;

		if(isset($_SESSION[SESS_PRE.'_SESS_USER_ID']))
			$user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
		else
			$user_id = 0;

		$strquery = 'SELECT DISTINCT color_id FROM product_price pp
					 LEFT JOIN color c ON c.id = pp.color_id
					 WHERE product_id= ' . (int) $product_id . ' AND pp.isDelete=0 AND c.isDelete=0 
					 ORDER BY c.name LIMIT 0, 1';
		$rs_color = @mysqli_query($myconn, $strquery);
		$row_color = @mysqli_fetch_assoc($rs_color);
		$color_id = $row_color['color_id'];

		$strquery = 'SELECT DISTINCT size_id FROM product_price pp
					 LEFT JOIN size s ON s.id = pp.size_id
					 WHERE product_id= ' . (int) $product_id . ' AND pp.isDelete=0 AND s.isDelete=0 LIMIT 0, 1';
		$rs_size = @mysqli_query($myconn, $strquery);
		$row_size = @mysqli_fetch_assoc($rs_size);
		$size_id = $row_size['size_id'];

		$db->add_to_cart($user_id, $product_id, $color_id, $size_id, $qty);
	}
?>