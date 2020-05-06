<?php 
	include('connect.php');
	if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
		$db->location(SITEURL.'my-account/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reset Password</title>
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
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Reset Password</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- Contact Us -->

		<div class="row justify-content-center">

			<div class="col-lg-6 get_in_touch_col">
				<div class="shadow p-5">
					<div class="get_in_touch_contents">
						<h1 class="text-center mb-3">Reset Password</h1>
						<form name="frm" id="frm" method="post" action="<?php echo SITEURL; ?>process-set-new-password/">
	                      	<input type="hidden" name="slug" id="slug" value="<?php echo $_REQUEST['slug']; ?>" />
	                      	<input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id']; ?>" />
							<div>
								<input type="password" name="newpass" id="newpass" class="form_input input_name input_ph" placeholder="Enter New Password" required="required" autocomplete="off">
							</div>
							<div>
								<input type="password" name="cnewpass" id="cnewpass" class="form_input input_name input_ph" placeholder="Confirm New Password" required="required" autocomplete="off">
							</div>
							<div class="text-center">
								<button id="review_submit" type="submit" class="red_button message_submit_btn trans_300" value="Submit">Submit</button>
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
			rules: {
				newpass:{required: true, minlength:6},
				cnewpass:{required: true, equalTo: "#newpass"},
			},
			messages: {
				newpass:{required:"Please enter new password."},
				cnewpass:{required:"Please enter confirm password.", equalTo:"New password and Confirm password do not match."},
			}
		});
	});
	
</script>
</body>

</html>
