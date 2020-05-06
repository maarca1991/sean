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
	if( isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID'] > 0 )
	{
		$cart_id = $_SESSION[SESS_PRE.'_SESS_CART_ID'];
	}

	$billing_first_name = '';
	$billing_last_name = '';
	$billing_email = '';
	$billing_phone = '';
	$billing_address = '';
	$billing_address2 = '';
	$billing_country = '';
	$billing_state = '';
	$billing_city = '';
	$billing_zipcode = '';

	$saveDetails = $db->getValue('employee', 'saveDetails', 'id=' . (int) $user_id);
	if( $saveDetails )
	{
		$rs_detail = $db->getData('billing_shipping', '*', 'user_id=' . (int) $user_id . ' AND isDelete=0', 'adate DESC LIMIT 0, 1');
		$row_detail = @mysqli_fetch_assoc($rs_detail);

		$billing_first_name = $row_detail['billing_first_name'];
		$billing_last_name = $row_detail['billing_last_name'];
		$billing_email = $row_detail['billing_email'];
		$billing_phone = $row_detail['billing_phone'];
		$billing_address = $row_detail['billing_address'];
		$billing_address2 = $row_detail['billing_address2'];
		$billing_country = $row_detail['billing_country'];
		$billing_state = $row_detail['billing_state'];
		$billing_city = $row_detail['billing_city'];        
		$billing_zipcode = $row_detail['billing_zipcode'];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Checkout</title>
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
	<div class="contact_container checkout">

	<div class="pb-5">
		<div class="container">
			<div class="cart-heading text-left py-5 mb-4"><h2>Checkout</h2></div>
			<section id="checkout-container">
				<form name="frm" id="frm" method="post" action="<?php echo SITEURL; ?>checkout_db.php" class="needs-validation" novalidate>
				<div class="row">
					<!-- START: column 1 -->
					<div class="col-md-4">
						<h4 class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Billing address</h4>
						<div class="rounded shadow mb-5 px-3 pb-4">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="chksave_future" id="chksave_future" value="1">
								<label class="custom-control-label" for="chksave_future">Save for future orders</label>
							</div>
							<hr class="mb-4">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="billing_first_name">First name</label>
									<input type="text" class="form-control" name="billing_first_name" id="billing_first_name" value="<?php echo $billing_first_name; ?>" maxlength="50">
								</div>
								<div class="col-md-6 mb-3">
									<label for="billing_last_name">Last name</label>
									<input type="text" class="form-control" name="billing_last_name" id="billing_last_name" value="<?php echo $billing_last_name; ?>" maxlength="50">
								</div>
							</div>

							<div class="mb-3">
								<label for="billing_email">Email
									<!-- <span class="text-muted">(Optional)</span> -->
								</label>
								<input type="email" class="form-control" name="billing_email" id="billing_email" value="<?php echo $billing_email; ?>" maxlength="100">
							</div>

							<div class="mb-3">
								<label for="billing_phone">Telephone</label>
								<input type="text" class="form-control" name="billing_phone" id="billing_phone" value="<?php echo $billing_phone; ?>" maxlength="50">
							</div>

							<div class="mb-3">
								<label for="billing_address">Address</label>
								<input type="text" class="form-control" name="billing_address" id="billing_address" value="<?php echo $billing_address; ?>" maxlength="255">
							</div>

							<div class="mb-3">
								<label for="billing_address2">Address 2
									<span class="text-muted">(Optional)</span>
								</label>
								<input type="text" class="form-control" name="billing_address2" id="billing_address2" value="<?php echo $billing_address2; ?>" maxlength="255">
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="billing_country">Country</label>
									<select class="custom-select d-block w-100" name="billing_country" id="billing_country" onchange="show_state('billing', this.value);">
										<option value="">Choose...</option>
										<option value="Australia" <?php if($billing_country == 'Australia') echo 'selected'; ?>>Australia</option>
									</select>
								</div>
								<div class="col-md-6 mb-3" id="div_billing_state">
									<label for="billing_state">State</label>
									<select class="custom-select d-block w-100" name="billing_state" id="billing_state" onchange="show_city('billing', this.value);">
										<option value="">Choose...</option>
										<?php
											$rs_state = $db->getData('states', '*', 'isDelete=0', 'name');
											while( $row_state = @mysqli_fetch_assoc($rs_state) )
											{
												echo '<option value="' . $row_state['id'] . '"';
												if( $row_state['id'] == $billing_state )
													echo ' selected';
												echo '>' . $row_state['name'] . '</option>';
											}
										?>
									</select>
								</div>
								<div class="col-md-6 mb-3" id="div_billing_city">
									<label for="billing_city">City</label>
									<select class="form-control" name="billing_city" id="billing_city">
										<option value="">Choose...</option>
										<?php
											$rs_city = $db->getData('city', '*', 'state_id= ' . (int) $billing_state . ' AND isDelete=0', 'name');
											while( $row_city = @mysqli_fetch_assoc($rs_city) )
											{
												echo '<option value="' . $row_city['id'] . '"';
												if( $row_city['id'] == $billing_city )
													echo ' selected';
												echo '>' . $row_city['name'] . '</option>';
											}
										?>
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label for="billing_zipcode">Zip</label>
									<input type="text" class="form-control" name="billing_zipcode" id="billing_zipcode" value="<?php echo $billing_zipcode; ?>" maxlength="10">
								</div>
							</div>
						</div>
					</div>
					<!-- END: column 1 -->

					<!-- START: column 2 -->
					<div class="col-md-4">
						<h4 class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Shipping address</h4>
						<div class="rounded shadow mb-5 px-3 pb-4">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="chksame_billing" id="chksame_billing">
								<label class="custom-control-label" for="chksame_billing">Same as Billing address</label>
							</div>
							<hr class="mb-4">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="shipping_first_name">First name</label>
									<input type="text" class="form-control" name="shipping_first_name" id="shipping_first_name" value="" maxlength="50">
								</div>
								<div class="col-md-6 mb-3">
									<label for="shipping_last_name">Last name</label>
									<input type="text" class="form-control" name="shipping_last_name" id="shipping_last_name" value="" maxlength="50">
								</div>
							</div>

							<div class="mb-3">
								<label for="shipping_email">Email
									<!-- <span class="text-muted">(Optional)</span> -->
								</label>
								<input type="email" class="form-control" name="shipping_email" id="shipping_email" value="" maxlength="100">
							</div>

							<div class="mb-3">
								<label for="shipping_phone">Telephone</label>
								<input type="text" class="form-control" name="shipping_phone" id="shipping_phone" value="" maxlength="50">
							</div>

							<div class="mb-3">
								<label for="shipping_address">Address</label>
								<input type="text" class="form-control" name="shipping_address" id="shipping_address" value="" maxlength="255">
							</div>

							<div class="mb-3">
								<label for="shipping_address2">Address 2
									<span class="text-muted">(Optional)</span>
								</label>
								<input type="text" class="form-control" name="shipping_address2" id="shipping_address2" value="" maxlength="255">
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="shipping_country">Country</label>
									<select class="custom-select d-block w-100" name="shipping_country" id="shipping_country" onchange="show_state('shipping', this.value);">
										<option value="">Choose...</option>
										<option value="Australia">Australia</option>
									</select>
								</div>
								<div class="col-md-6 mb-3" id="div_shipping_state">
									<label for="shipping_state">State</label>
									<select class="custom-select d-block w-100" name="shipping_state" id="shipping_state" onchange="show_city('shipping', this.value);">
										<option value="">Choose...</option>
										<?php
											$rs_state = $db->getData('states', '*', 'isDelete=0', 'name');
											while( $row_state = @mysqli_fetch_assoc($rs_state) )
											{
												echo '<option value="' . $row_state['id'] . '">' . $row_state['name'] . '</option>';
											}
										?>
									</select>
								</div>
								<div class="col-md-6 mb-3" id="div_shipping_city">
									<label for="shipping_city">City</label>
									<select class="form-control" name="shipping_city" id="shipping_city">
										<option value="">Choose...</option>
										<?php
											$rs_city = $db->getData('city', '*', 'state_id= ' . (int) $shipping_state . ' AND isDelete=0', 'name');
											while( $row_city = @mysqli_fetch_assoc($rs_city) )
											{
												echo '<option value="' . $row_city['id'] . '">' . $row_city['name'] . '</option>';
											}
										?>
									</select>
								</div>
								<div class="col-md-6 mb-3">
									<label for="shipping_zipcode">Zip</label>
									<input type="text" class="form-control" name="shipping_zipcode" id="shipping_zipcode" value="" maxlength="10">
								</div>
							</div>
						</div>
					</div>
					<!-- END: column 2 -->

					<!-- START: column 3 -->
					<div class="col-md-4 mb-4">
						<!-- START: Payment -->
						<!-- <h4 class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Payment</h4>
						<div class="rounded shadow mb-5 px-3 pb-4">
							<div class="d-block my-3">
								<div class="custom-control custom-radio mr-2">
									<input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked>
									<label class="custom-control-label" for="credit">Credit card</label>
								</div>
								<div class="custom-control custom-radio mr-2">
									<input id="debit" name="paymentMethod" type="radio" class="custom-control-input">
									<label class="custom-control-label" for="debit">Debit card</label>
								</div>
								<div class="custom-control custom-radio mr-2">
									<input id="paypal" name="paymentMethod" type="radio" class="custom-control-input">
									<label class="custom-control-label" for="paypal">Paypal</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label for="cc-name">Name on card</label>
									<input type="text" class="form-control" id="cc-name" placeholder="Full name as displayed on card">
								</div>
								<div class="col-md-12 mb-3">
									<label for="cc-number">Credit card number</label>
									<input type="text" class="form-control" id="cc-number">
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="cc-expiration">Expiration</label>
									<input type="text" class="form-control" id="cc-expiration">
								</div>
								<div class="col-md-6 mb-3">
									<label for="cc-expiration">CVV</label>
									<input type="text" class="form-control" id="cc-cvv">
								</div>
							</div>
							<div class="payment-methods">
								<p class="pt-4 mb-2">Payment Options</p>
								<hr>
								<ul class="list-inline d-flex">
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-visa"></i>
									</li>
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-stripe"></i>
									</li>
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-paypal"></i>
									</li>
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-jcb"></i>
									</li>
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-discover"></i>
									</li>
									<li class="mx-1 text-info">
										<i class="fa-2x fa fa-cc-amex"></i>
									</li>
								</ul>
							</div>
							<div class="input-group mt-4">
								<input type="text" class="form-control" placeholder="Promo code">
								<div class="input-group-append">
									<button type="submit" class="btn btn-secondary">Redeem</button>
								</div>
							</div>
						</div> -->
						<!-- END: Payment -->

						<!-- START: Cart -->
						<h4 class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Your cart</h4>
						<div class="rounded shadow mb-5 px-3 pb-4">
							<ul class="list-group mb-3">
							<?php
								$strquery = 'SELECT ct.*, p.name AS product_name, p.image_path, p.slug, c.name AS color, s.name AS size FROM cart_detail ct 
											 LEFT JOIN product p ON p.id = ct.product_id 
											 LEFT JOIN color c ON c.id = ct.color_id 
											 LEFT JOIN size s ON s.id = ct.size_id 
											 WHERE ct.cart_id = ' . (int) $cart_id . ' AND ct.isDelete=0 AND p.isDelete=0';
								//print $strquery;
								$rs = @mysqli_query($myconn, $strquery);
								while( $row = @mysqli_fetch_assoc($rs) )
								{
							?>
								<li class="list-group-item d-flex justify-content-between lh-condensed">
									<div>
										<h6 class="my-0"><?php echo $row['product_name']; ?></h6>
										<small class="text-muted"><?php echo $row['color'] . ' / ' . $row['size']; ?></small>
									</div>
									<span class="text-muted"><?php echo CUR.$db->num($row['sub_total']); ?></span>
								</li>
							<?php
								}

								$order_total = $db->getValue('cart', 'grand_total', 'id=' . (int) $cart_id . ' AND isDelete=0');
							?>
								<li class="list-group-item d-flex justify-content-between">
									<span>Total</span>
									<strong><?php echo CUR.$db->num($order_total); ?></strong>
								</li>
							</ul>
							<!-- <button class="btn btn-primary btn-lg btn-block" name="btnsubmit" id="btnsubmit" type="submit">
							<i class="fa fa-credit-card"></i> Continue to Checkout</button> -->
							<div>
								<input type="radio" name="btnsubmit_case" id="btnsubmit_case" value="btnsubmit_case">
								<label for="btnsubmit_case">Case On Delivery</label>
							</div>
							<div>
								<input type="radio" name="btnsubmit_case" id="btnsubmit_case" value="btnsubmit_paypal">
								<label for="btnsubmit_case">Checkout With Paypal</label>
							</div>
							<button class="btn btn-primary btn-lg btn-block" name="btnsubmit" id="btnsubmit" type="submit">
							<i class="fa fa-credit-card"></i> Checkout</button>
						</div>
						<!-- END: Cart -->
					</div>
					<!-- END: column 3 -->
				</div>
				</form>
			</section>
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

	$(function(){
		$("#frm").validate({
			ignore: "",
			rules: {
				billing_first_name:{required:true}, 
				billing_last_name:{required:true}, 
				billing_email:{required:true, email:true}, 
				billing_phone:{required:true}, 
				billing_address:{required:true}, 
				billing_country:{required:true}, 
				billing_state:{required:true}, 
				billing_city:{required:true}, 
				billing_zipcode:{required:true}, 

				shipping_first_name:{required:true}, 
				shipping_last_name:{required:true}, 
				shipping_email:{required:true, email:true}, 
				shipping_phone:{required:true}, 
				shipping_address:{required:true}, 
				shipping_country:{required:true}, 
				shipping_state:{required:true}, 
				shipping_city:{required:true}, 
				shipping_zipcode:{required:true}, 
			},
			messages: {
				billing_first_name:{required:"Please enter first name."}, 
				billing_last_name:{required:"Please enter last name."}, 
				billing_email:{required:"Please enter email address.", email:"Please enter valid email address."}, 
				billing_phone:{required:"Please enter contact number."}, 
				billing_address:{required:"Please enter address."}, 
				billing_country:{required:"Please enter country."}, 
				billing_state:{required:"Please enter state."}, 
				billing_city:{required:"Please enter city."}, 
				billing_zipcode:{required:"Please enter zipcode."}, 

				shipping_first_name:{required:"Please enter first name."}, 
				shipping_last_name:{required:"Please enter last name."}, 
				shipping_email:{required:"Please enter email address.", email:"Please enter valid email address."}, 
				shipping_phone:{required:"Please enter contact number."}, 
				shipping_address:{required:"Please enter address."}, 
				shipping_country:{required:"Please enter country."}, 
				shipping_state:{required:"Please enter state."}, 
				shipping_city:{required:"Please enter city."}, 
				shipping_zipcode:{required:"Please enter zipcode."}, 
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element);
			}
		});

	   /* $('#btnsubmit').submit(function(event) {
			
			$.ajax({
				url     : "<?php echo SITEURL; ?>paypal_checkout.php",
				type    : "post",
				dataType: 'json',
				data : $('#stripe_form_submit').serialize(),
				 beforeSend  : function() 
					{
						$(".loading-div").removeClass("hide");  
					},
				success: function(res){
					//alert(res);
					if(res==2)
					{
						$.alert({title:'Oops..',type:'red',content:'Your cart quantity and shipping quantity not matched'});
					}
					else
					{
						$(".loading-div").addClass("hide");
						$("#paypal_frm_submit").html(res);
						setTimeout(function(){ 
							document.frmPayPal.submit();
						}, 3000);
					}
				}
			});

		});*/

		$('#chksame_billing').click(function() {
			$('#shipping_first_name').val($('#billing_first_name').val());
			$('#shipping_last_name').val($('#billing_last_name').val());
			$('#shipping_email').val($('#billing_email').val());
			$('#shipping_phone').val($('#billing_phone').val());
			$('#shipping_address').val($('#billing_address').val());
			$('#shipping_address2').val($('#billing_address2').val());
			$('#shipping_country').val($('#billing_country').val());
			$('#shipping_zipcode').val($('#billing_zipcode').val());

			show_state('shipping', $('#billing_country').val());
			setTimeout(function() {
				$('#shipping_state option[value="'+$('#billing_state').val()+'"]').attr('selected', 'selected');
				show_city('shipping', $('#billing_state').val())
				setTimeout(function() {
					$('#shipping_city option[value="'+$('#billing_city').val()+'"]').attr('selected', 'selected');
				}, 1000);
			}, 1000);
			$('#shipping_state option[value="'+$('#billing_state').val()+'"]').attr('selected', 'selected');
			$('#shipping_city option[value="'+$('#billing_city').val()+'"]').attr('selected', 'selected');
		});
	});

	function show_state(section, val)
	{
		$.ajax({
			url: '<?php echo SITEURL; ?>checkout_db.php', 
			method: 'post', 
			data: 'mode=state&country='+val+'&type='+section,
			beforeSend: function(){
				$(".loader").show(); 
				$(document.body).addClass('no-pointer');    
			},
			success: function(res) {
				//alert(res);
				$('#div_'+section+'_state').html(res);

				$(".loader").hide();
				$(document.body).removeClass('no-pointer');
			}
		});
	}

	function show_city(section, val)
	{
		$.ajax({
			url: '<?php echo SITEURL; ?>checkout_db.php', 
			method: 'post', 
			data: 'mode=city&state_id='+val+'&type='+section,
			beforeSend: function(){
				$(".loader").show(); 
				$(document.body).addClass('no-pointer');    
			},
			success: function(res) {
				//alert(res);
				$('#div_'+section+'_city').html(res);

				$(".loader").hide();
				$(document.body).removeClass('no-pointer');
			}
		});
	}
</script>
</body>

</html>
