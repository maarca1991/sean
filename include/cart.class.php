<?php
	class Cart extends Functions
	{
		public function generateOrderNumber($cart_id = 0 )
		{
			$max = 0;
			if( $cart_id <= 0 )
			{
				$max = $this->getValue('cart', 'MAX(id)', 'isDelete=0');
				$max++;
			}
			else
			{
				$max = $cart_id;
			}
			$n = 7 - strlen($max);
			for($i=0; $i<$n; $i++)
				$max = '0' . $max;
			return 'FXTL-'.$max;
		}

		public function items_in_cart( $cart_id )
		{
			return (int) $this->getValue('cart_detail', 'COUNT(*)', 'cart_id=' . (int) $cart_id . ' AND isDelete=0');
		}

		public function add_to_cart($user_id, $product_id, $color_id, $size_id, $qty)
		{
			$cart_id = 0;
			$cart_detail_id = 0;

			if( isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID'] > 0 )
			{
				$cart_id = $_SESSION[SESS_PRE.'_SESS_CART_ID'];
			}
			else
			{
				//New cart
				/* Order Status : 0=Cancelled, 1=In Progress, 2=Completed, 3=Shipped, 4=Delivered */
				$order_no = $this->generateOrderNumber();
				$rows = array(
							  'employee_id' => $user_id,
							  'order_status' => 1, 
							  'order_no' => $order_no, 
						);
				$cart_id = $this->insert('cart', $rows);
				$_SESSION[SESS_PRE.'_SESS_CART_ID'] = $cart_id;
			}

			// check existing cart for similar product
			$cart_detail_id = $this->getValue('cart_detail', 'id', 'cart_id='. (int) $cart_id . ' AND product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
			if( $cart_detail_id > 0 )
			{
				// edit qty for existing product on the cart
				$existing_qty = (int) $this->getValue('cart_detail', 'qty', 'id=' . (int) $cart_detail_id);
				$qty += $existing_qty;

				$price = $this->getValue('product_price', 'price', 'product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
				$product_total = $this->num($price * $qty);

				$rows = array(
					'cart_id' => $cart_id, 
					'product_id' => $product_id, 
					'color_id' => $color_id, 
					'size_id' => $size_id, 
					'qty' => $qty, 
					'price' => $price, 
					'sub_total' => $product_total, 
				);
				$this->update('cart_detail', $rows, 'id=' . (int) $cart_detail_id);
			}
			else
			{
				$price = $this->getValue('product_price', 'price', 'product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
				$product_total = $this->num($price * $qty);

				// add product to the cart
				$rows = array(
					'cart_id' => $cart_id, 
					'product_id' => $product_id, 
					'color_id' => $color_id, 
					'size_id' => $size_id, 
					'qty' => $qty, 
					'price' => $price, 
					'sub_total' => $product_total, 
				);
				$cart_detail_id = $this->insert('cart_detail', $rows);
			}

			// update cart master table
			$this->update_cart_total($cart_id);
		}

		public function remove_from_cart($cart_id, $product_id, $color_id, $size_id)
		{
			$rows = array('isDelete' => 1);
			$this->update('cart_detail', $rows, 'cart_id=' . (int) $cart_id . ' AND product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id);

			// update cart master table
			$this->update_cart_total($cart_id);
		}

		public function update_quantity($cart_id, $product_id, $color_id, $size_id, $qty)
		{
			$price = $this->getValue('product_price', 'price', 'product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id . ' AND isDelete=0');
			$product_total = $this->num($price * $qty);

			$rows = array(
					'qty' => $qty, 
					'sub_total' => $product_total, 
				);
			$this->update('cart_detail', $rows, 'cart_id=' . (int) $cart_id . ' AND product_id=' . (int) $product_id . ' AND color_id=' . (int) $color_id . ' AND size_id=' . (int) $size_id);

			// update cart master table
			$this->update_cart_total($cart_id);
		}

		public function update_cart_total($cart_id)
		{
			$subtotal = $this->getValue('cart_detail', 'SUM(sub_total)', 'cart_id=' . (int)$cart_id . ' AND isDelete=0');
			$subtotal = $this->num($subtotal);
			$tax = $this->num(($subtotal * TAX_RATE) / 100);
			$grandtotal = $this->num($subtotal + $tax);

			$rows = array(
					'sub_total' => $subtotal, 
					'tax_rate' => TAX_RATE, 
					'tax' => $tax, 
					'grand_total' => $grandtotal, 
				);
			$this->update('cart', $rows, 'id=' . (int) $cart_id);
		}
	}
	
?>