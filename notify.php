<?php
	//error_reporting(E_ALL);
	include("connect.php"); 
	//$a = json_encode($_POST);
	$a = json_encode($_REQUEST);
	$_POST = json_decode($a, true);
	//@mail("crazycoder08@gmail.com", "Paypal Response ", $a);
	 
	if(isset($_POST))
	{
		//if($_POST['payment_status'] == 'Completed')
		if($_POST['st'] == 'Completed')
		{
			$paypal_response = json_encode($_POST);
			$custom_arr = explode(",", $_POST['cm']);
           
			$user_id = $custom_arr[0];
			$cart_id = $custom_arr[1];
			$grandtotal = $custom_arr[2];
			$history_id = $custom_arr[3];

			if(isset($cart_id) && $cart_id > 0 )
			{				
				$adate = date('Y-m-d H:i:s');
				//$total_amount = $_POST['mc_gross'];
				//$transaction_id = $_POST['txn_id'];
				$total_amount = $_POST['amt'];
				$transaction_id = $_POST['tx'];
					
				$rows = array(
						"payment_status" => 2, 
						"payment_date" => $adate, 
						"txn_id" => $transaction_id, 
						"err_msg" => 'success', 
					);
				$db->update("payment_history", $rows, 'id=' . $history_id);

				$rows = array(
						'order_status' => 2, 
						'order_date' => $adate, 
					);
				$db->update('cart', $rows, 'id=' . (int) $cart_id);

				unset($_SESSION[SESS_PRE.'_SESS_CART_ID']);
			}
		}
	}
?>