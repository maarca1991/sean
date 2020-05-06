<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');

    $user_id = 0;
    if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
    {
        $user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
    }

    $cart_id = 0;
    if( isset($_REQUEST['id']) && $_REQUEST['id'] > 0 )
    	$cart_id = $_REQUEST['id'];

    $rs_cart = $db->getData('cart', '*', 'id=' . (int) $cart_id . ' AND employee_id=' . (int) $user_id . ' AND isDelete=0');
    $row_cart = @mysqli_fetch_assoc($rs_cart);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Order Detail</title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_responsive.css">
</head>

<body>
<div class="loader"></div>
<div class="super_container">

	<?php include('include/header.php'); ?>
	<div class="container contact_container">
		<div class="row">
			<div class="col">

				<!-- Breadcrumbs -->

				<div class="breadcrumbs d-flex flex-row align-items-center">
					<ul>
						<li><a href="<?php echo SITEURL; ?>">Home</a></li>
						<li><a href="<?php echo SITEURL; ?>my-account/"><i class="fa fa-angle-right" aria-hidden="true"></i>My Account</a></li>
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Order Detail</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- Order detail -->

		<div class="row">

			<div class="col-lg-12 get_in_touch_col">
				<div class="shadow p-5">
					<div class="get_in_touch_contents">
						<h3 class="mb-3">Order Detail</h3>
						<table class="table table-bordered">
							<tr>
								<td><strong>Order Number</strong></td>
								<td><?php echo $row_cart['order_no']; ?></td>
								<td><strong>Sub Total</strong></td>
								<td><?php echo CUR.$db->num($row_cart['sub_total']); ?></td>
							</tr>
							<tr>
								<td><strong>Order Date</strong></td>
								<td><?php echo $db->date($row_cart['order_date'], 'm/d/Y'); ?></td>
								<td><strong>Tax</strong></td>
								<td><?php echo CUR.$db->num($row_cart['tax']); ?></td>
							</tr>
							<tr>
								<td><strong>Order Status</strong></td>
								<td><?php 
											switch( $row_cart['order_status'] )
											{
												case 0:
													echo 'Cancelled';
													break; 
												case 2:
													echo 'Completed';
													break; 
												case 3:
													echo 'Shipped';
													break; 
												case 4:
													echo 'Delivered';
													break; 
												default:
													echo 'In Progress';
													break; 
											}
										?>
								</td>
								<td><strong>Order Amount</strong></td>
								<td><?php echo CUR.$db->num($row_cart['grand_total']); ?></td>
							</tr>
						</table>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Image</th>
									<th>Product Name</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$counter = 0;
							    $strquery = 'SELECT ct.*, p.name AS product_name, p.image_path, c.name AS color, s.name AS size 
							                 FROM cart_detail ct 
							                 LEFT JOIN product p ON p.id = ct.product_id 
							                 LEFT JOIN color c ON c.id = ct.color_id 
							                 LEFT JOIN size s ON s.id = ct.size_id 
							                 WHERE ct.cart_id = ' . (int) $cart_id . ' AND ct.isDelete=0 AND p.isDelete=0';
							    //print $strquery;
							    $rs_detail = @mysqli_query($myconn, $strquery);
    							while( $row_detail = @mysqli_fetch_assoc($rs_detail) )
    							{
    								$counter++;
    						?>
								<tr>
									<td class="text-center"><?php echo $counter; ?></td>
									<td class="text-center"><img src="<?php echo SITEURL.PRODUCT.$row_detail['image_path']; ?>" class="img-fluid order-detail-img"></td>
									<td><?php echo $row_detail['product_name']; ?>
				                        <span class="text-muted font-weight-normal font-italic d-block">Color: <?php echo $row_detail['color']; ?></span>
				                        <span class="text-muted font-weight-normal font-italic d-block">Size: <?php echo $row_detail['size']; ?></span>
									</td>
									<td class="text-center"><?php echo $row_detail['qty']; ?></td>
									<td class="text-center"><?php echo CUR.$db->num($row_detail['sub_total']); ?></td>
								</tr>
    						<?php
    							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-lg-12 mt-3">
				<a href="<?php echo SITEURL; ?>my-account/" class="btn btn-dark rounded-pill py-2">Back to List</a>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php include('include/footer.php'); ?>
</div>

<?php include('include/js.php'); ?>
<script src="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="<?php echo SITEURL; ?>js/contact_custom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".loader").hide(); 
    });	
</script>
</body>

</html>
