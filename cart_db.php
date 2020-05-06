<?php
	include('connect.php');
	$user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
	$cart_id = $_SESSION[SESS_PRE.'_SESS_CART_ID'];

	$mode = $_REQUEST['mode'];

	if( $mode == 'update_qty' )
	{
		$product_id = $_REQUEST['product_id'];
		$color_id = $_REQUEST['color_id'];
		$size_id = $_REQUEST['size_id'];
		$qty = $_REQUEST['qty'];

		$db->update_quantity($cart_id, $product_id, $color_id, $size_id, $qty);
		$price = $db->getValue('product_price', 'price', 'product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
		
		$rs_order = $db->getData('cart', 'tax, sub_total, grand_total', 'id=' . (int) $cart_id . ' AND isDelete=0');
		$row_order = @mysqli_fetch_assoc($rs_order);

		$res['product_total'] = CUR.$db->num($price * $qty);
		$res['sub_total'] = CUR.$db->num($row_order['sub_total'], 2, ".", "", false);
		$res['tax'] = CUR.$db->num($row_order['tax'], 2, ".", "", false);
		$res['grand_total'] = CUR.$db->num($row_order['grand_total'], 2, ".", "", false);

		echo json_encode($res);

		//echo CUR.$db->num($price * $qty);
		exit;		
	}

	if( $mode == 'delete' )
	{
		$product_id = $_REQUEST['product_id'];
		$color_id = $_REQUEST['color_id'];
		$size_id = $_REQUEST['size_id'];

		$db->remove_from_cart($cart_id, $product_id, $color_id, $size_id);

		
		$rs_order = $db->getData('cart', 'tax, sub_total, grand_total', 'id=' . (int) $cart_id . ' AND isDelete=0');
		$row_order = @mysqli_fetch_assoc($rs_order);

		$res['no_of_item'] = $db->items_in_cart($cart_id);
		$res['sub_total'] = CUR.$db->num($row_order['sub_total'], 2, ".", "", false);
		$res['tax'] = CUR.$db->num($row_order['tax'], 2, ".", "", false);
		$res['grand_total'] = CUR.$db->num($row_order['grand_total'], 2, ".", "", false);

		echo json_encode($res);

		//echo $db->items_in_cart($cart_id);
		exit;		
	}
?>