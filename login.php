<?php 
	include('connect.php');
	if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
		$db->location(SITEURL.'my-account/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_responsive.css">
</head>

<body>

<div class="super_container">

	<?php include('include/header.php'); ?>
	<div class="container contact_container">
		<div class="row">
			<div class="col">

				<!-- Breadcrumbs -->

				<div class="breadcrumbs d-flex flex-row align-items-center">
					<ul>
						<li><a href="<?php echo SITEURL; ?>">Home</a></li>
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Login</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- Contact Us -->

		<div class="row justify-content-center">

			<div class="col-lg-6 get_in_touch_col">
				<div class="shadow p-5">
					<div class="get_in_touch_contents">
						<h1 class="mb-3 text-center">Login</h1>
						<form name="frm" id="frm" method="post" action="<?php echo SITEURL; ?>process-login/">
							<div>
								<input id="email" class="form_input input_email input_ph" type="email" name="email" placeholder="Email" required="required" data-error="Valid email is required.">
								<input id="password" class="form_input input_name input_ph" type="password" name="password" placeholder="Password" required="required" data-error="Password is required.">
							</div>
							<div class="text-center">
								<button id="submit" type="submit" class="red_button message_submit_btn trans_300" value="Submit">Submit</button>
							</div>
							<div class="text-center mt-3">
								<a href="<?php echo SITEURL; ?>forgot-password/">Forgot Password?</a>
							</div>
						</form>
					</div>
				</div>
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
	$(function(){
		$("#frm").validate({
			ignore: "",
			rules: {
				email:{required:true, email:true}, 
				password:{required:true}, 
			},
			messages: {
				email:{required:"Please enter registered email.", email:"Please enter valid email address."}, 
				password:{required:"Please enter password."}, 
			},
			errorPlacement: function(error, element) {
				error.insertAfter(element);
			}
		});
	});
	
</script>
</body>

</html>
