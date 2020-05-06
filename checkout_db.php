<?php
	include('connect.php');

	$mode = $_REQUEST['mode'];

	if( $mode == 'state' )
	{
		$country = $_REQUEST['country'];
		$type = $_REQUEST['type'];

		echo '<label for="' . $type . '_state">State</label>';
		echo '<select class="custom-select d-block w-100" name="' . $type . '_state" id="' . $type . '_state" onchange="show_city(\'' . $type . '\', this.value);">';
		echo '    <option value="">Choose...</option>';
		if( strtolower($country) == 'australia')
		{
			$rs_state = $db->getData('states', '*', 'isDelete=0', 'name');
			while( $row_state = @mysqli_fetch_assoc($rs_state) )
			{
				echo '<option value="' . $row_state['id'] . '">' . $row_state['name'] . '</option>';
			}
		}
		echo '</select>';
		exit;
	}

	if( $mode == 'city' )
	{
		$state_id = $_REQUEST['state_id'];
		$type = $_REQUEST['type'];

		echo '<label for="' . $type . '_city">City</label>';
		echo '<select class="form-control" name="' . $type . '_city" id="' . $type . '_city">';
		echo '    <option value="">Choose...</option>';
		$rs_city = $db->getData('city', '*', 'state_id= ' . (int) $state_id . ' AND isDelete=0', 'name');
		while( $row_city = @mysqli_fetch_assoc($rs_city) )
		{
			echo '<option value="' . $row_city['id'] . '">' . $row_city['name'] . '</option>';
		}
		echo '</select>';
		exit;
	}

	if( isset($_REQUEST['btnsubmit']) )
	{
		$billing_first_name = $db->clean($_REQUEST['billing_first_name']);
		$billing_last_name = $db->clean($_REQUEST['billing_last_name']);
		$billing_email = $db->clean($_REQUEST['billing_email']);
		$billing_phone = $db->clean($_REQUEST['billing_phone']);
		$billing_address = $db->clean($_REQUEST['billing_address']);
		$billing_address2 = $db->clean($_REQUEST['billing_address2']);
		$billing_country = $db->clean($_REQUEST['billing_country']);
		$billing_state = $db->clean($_REQUEST['billing_state']);
		$billing_city = $db->clean($_REQUEST['billing_city']);
		$billing_zipcode = $db->clean($_REQUEST['billing_zipcode']);

		$shipping_first_name = $db->clean($_REQUEST['shipping_first_name']);
		$shipping_last_name = $db->clean($_REQUEST['shipping_last_name']);
		$shipping_email = $db->clean($_REQUEST['shipping_email']);
		$shipping_phone = $db->clean($_REQUEST['shipping_phone']);
		$shipping_address = $db->clean($_REQUEST['shipping_address']);
		$shipping_address2 = $db->clean($_REQUEST['shipping_address2']);
		$shipping_country = $db->clean($_REQUEST['shipping_country']);
		$shipping_state = $db->clean($_REQUEST['shipping_state']);
		$shipping_city = $db->clean($_REQUEST['shipping_city']);
		$shipping_zipcode = $db->clean($_REQUEST['shipping_zipcode']);

		$saveDetails = $db->clean($_REQUEST['chksave_future']);

		$cart_id = 0;
		if( isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID'] > 0 )
		{
			$cart_id = $_SESSION[SESS_PRE.'_SESS_CART_ID'];
		}
		$user_id = 0;
		if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
		{
			$user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
		}

		$rows = array(
				'user_id' => (int) $user_id, 
				'cart_id' => (int) $cart_id, 
				'billing_first_name' => $billing_first_name, 
				'billing_last_name' => $billing_last_name, 
				'billing_email' => $billing_email, 
				'billing_phone' => $billing_phone, 
				'billing_address' => $billing_address, 
				'billing_address2' => $billing_address2, 
				'billing_country' => $billing_country, 
				'billing_state' => $billing_state, 
				'billing_city' => $billing_city, 
				'billing_zipcode' => $billing_zipcode, 
				'shipping_first_name' => $shipping_first_name, 
				'shipping_last_name' => $shipping_last_name, 
				'shipping_email' => $shipping_email, 
				'shipping_phone' => $shipping_phone, 
				'shipping_address' => $shipping_address, 
				'shipping_address2' => $shipping_address2, 
				'shipping_country' => $shipping_country, 
				'shipping_state' => $shipping_state, 
				'shipping_city' => $shipping_city, 
				'shipping_zipcode' => $shipping_zipcode, 
			);
		$db->insert('billing_shipping', $rows);

	/*	
		// Update order status and destroy session Cart ID
		$rows = array(
				'order_status' => 2, 
				'order_date' => date('Y-m-d H:i:s'), 
			);
		$db->update('cart', $rows, 'id=' . (int) $cart_id);
	*/

		// Update status in employee table to store details for future use
		$rows = array('saveDetails' => (int) $saveDetails);
		$db->update('employee', $rows, 'id=' . (int) $user_id);


		/* START: PayPal */

		$grand_total = $db->getValue('cart', 'grand_total', 'id=' . $cart_id . ' AND isDelete=0');
		$qty = $db->getValue('cart_detail', 'SUM(qty)', 'cart_id=' . $cart_id . ' AND isDelete=0');
		$adate = date("Y-m-d H:i:s");

		$rows = array(
				"employee_id" => (int) $user_id, 
				"cart_id" => (int) $cart_id, 
				"price" => $grand_total, 
				"payment_status" => 1, 
				"payment_date" => $adate, 
			);
		$history_id = $db->insert("payment_history", $rows);

		if($_REQUEST['btnsubmit_case'] == "btnsubmit_paypal"){
			$pay_form = '<script type="text/javascript">
				document.onkeydown = function (e) {
					return false;
				}

				if (document.layers) {
					document.captureEvents(Event.MOUSEDOWN);
					document.onmousedown = function () {
						return false;
					};
				}
				else {
					document.onmouseup = function (e) {
						if (e != null && e.type == "mouseup") {
							if (e.which == 2 || e.which == 3) {
								return false;
							}
						}
					};
				}

				document.oncontextmenu = function () {
					return false;
				};

				document.onkeydown = function(e) {
					if(event.keyCode == 123) {
						return false;
					}
					if(e.ctrlKey && e.shiftKey && e.keyCode == "I".charCodeAt(0)){
						return false;
					}
					if(e.ctrlKey && e.shiftKey && e.keyCode == "J".charCodeAt(0)){
						return false;
					}
					if(e.ctrlKey && e.keyCode == "U".charCodeAt(0)){
						return false;
					}
				}

			</script><div class="bag_loader"><img src="'.SITEURL.'images/ajax-loader.gif" style="margin-left:48%;margin-top:20%;width:120px;"><p class="text-center" style="    text-align: center;
				font-size: 28px;
				font-family: "Source Sans Pro", sans-serif">Please Wait redirecting to paypal</p></div>
				<form method="post" action="'.PAYPAL_URL.'" name="frmPayPal" id="frmPayPal">
					<input type="hidden" name="amount" id="amount" value="'.$grand_total.'">
					<input type="hidden" name="business" value="'.PAYPAL_EMAIL.'">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="item_name" value="FoxTel Product(s)">
					<input type="hidden" name="item_number" value="'.$qty.'">
					<input name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" type="hidden">
					<input type="hidden" name="currency_code" value="USD">
					<input type="hidden" name="rm" value="2">
					<input type="hidden" name="return" value="'.SITEURL.'thank-you/">
					<input type="hidden" name="cancel_return" value="'.SITEURL.'checkout/">
					<input type="hidden" name="notify_url" value="'.SITEURL.'notify.php">
					<input type="hidden" name="custom" value="'.$user_id.','.(int) $cart_id.','.$grand_total.','.$history_id.'">
				</form>';
			echo $pay_form; //exit;

			echo '<script type="text/javascript">document.frmPayPal.submit();</script>';
			//unset($_SESSION[SESS_PRE.'_SESS_CART_ID']);
			/* END: PayPal */ 

			//$db->location(SITEURL . 'thank-you/');
		}else{
			$db->location(SITEURL . 'thank-you/');
		}
		
	}
?>