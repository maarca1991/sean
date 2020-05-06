<?php
	include('connect.php');

	$rs_prod = $db->getData('product', 'id', 'isDelete=0');
	while( $row_prod = @mysqli_fetch_assoc($rs_prod) )
	{
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 5, 'size_id' => 1, 'price' => 110);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 5, 'size_id' => 2, 'price' => 120);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 5, 'size_id' => 3, 'price' => 130);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 5, 'size_id' => 4, 'price' => 140);
		$db->insert('product_price', $rows);

		$rows = array('product_id' => $row_prod['id'], 'color_id' => 6, 'size_id' => 1, 'price' => 110);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 6, 'size_id' => 2, 'price' => 120);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 6, 'size_id' => 3, 'price' => 130);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 6, 'size_id' => 4, 'price' => 140);
		$db->insert('product_price', $rows);

		$rows = array('product_id' => $row_prod['id'], 'color_id' => 7, 'size_id' => 1, 'price' => 110);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 7, 'size_id' => 2, 'price' => 120);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 7, 'size_id' => 3, 'price' => 130);
		$db->insert('product_price', $rows);
		$rows = array('product_id' => $row_prod['id'], 'color_id' => 7, 'size_id' => 4, 'price' => 140);
		$db->insert('product_price', $rows);
	}

	echo 'Script executed successfully !!!';
?>