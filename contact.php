<?php 
	include('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Contact Us</title>
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
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Contact</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- Contact Us -->

		<div class="row">

			<div class="col-lg-6 contact_col">
				<div class="contact_contents">
					<img src="<?php echo SITEURL; ?>images/contact-img.png" class="img-fluid" width="100" alt="" srcset="">
					<h1>Contact Us</h1>
					<p>There are many ways to contact us. You may drop us a line, give us a call or send an email, choose what suits you the most.</p>
					<div class="row mt-5">
						<div class="col-md-1"><p><img src="<?php echo SITEURL; ?>images/phone-icon.png" /></p></div>
						<div class="col-md-9"><p><a href="tel:<?php echo str_replace(' ', '-', SITEPHONE); ?>"><?php echo SITEPHONE; ?></a></p></div>
					</div>
					<div class="row mt-2">
						<div class="col-md-1"><p><img src="<?php echo SITEURL; ?>images/email-icon.png" /></p></div>
						<div class="col-md-9"><p><a href="mailto:<?php echo SITEMAIL; ?>" target="_blank"><?php echo SITEMAIL; ?></a></p></div>
					</div>
					<div class="row mt-2">
						<div class="col-md-1"><p><img src="<?php echo SITEURL; ?>images/address-icon.png" /></p></div>
						<div class="col-md-9"><p><a href="" style="pointer-events: none;"><?php echo SITEADDRESS; ?></a></p></div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 get_in_touch_col">
				<div class="get_in_touch_contents">
					<h4>Fill Out the below for a quick response</h4>
					<p>Fill out the form below to receive a free and confidential.</p>
					<form name="frm" id="frm" method="post" action="<?php echo SITEURL; ?>contact_db.php">
						<div>
							<input type="text" name="name" id="name" class="form_input input_ph" placeholder="Name" maxlength="75" required>
							<input type="email" name="email" id="email" class="form_input input_ph" placeholder="Email" maxlength="100" required>
							<input type="text" name="phone" id="phone" class="form_input input_ph" placeholder="Phone" maxlength="25" required>
							<textarea name="message" id="message" class="input_ph input_message" placeholder="Message" rows="3" required></textarea>
						</div>
						<div>
							<button id="review_submit" type="submit" class="red_button message_submit_btn trans_300" value="Submit">Send Message</button>
						</div>
					</form>
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
                name:{required:true}, 
                email:{required:true, email:true}, 
                phone:{required:true}, 
                message:{required:true}, 
            },
            messages: {
                name:{required:"Please enter name."}, 
                email:{required:"Please enter email address.", email:"Please enter valid email address."}, 
                phone:{required:"Please enter contact number."}, 
                message:{required:"Please enter your message."}, 
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    });
	
</script>
</body>

</html>
