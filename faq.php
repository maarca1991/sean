<?php 
	include('connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>FAQ</title>
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
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>FAQ</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- FAQ -->

		<div class="row">

			<div class="col-lg-12 get_in_touch_col">
				<div class="get_in_touch_contents">
					<h1>FAQ</h1>

					<?php
						$rs = $db->getData('faq', 'question, answer', 'isDelete=0', 'display_order');
						while( $row = @mysqli_fetch_assoc($rs) )
						{
					?>
					<div class="mb-5">
						<h5><?php echo $row['question']; ?></h5>
						<div><?php echo $row['answer']; ?></div>
					</div>
					<?php
						}
					?>
				</div>
			</div>

		</div>
	</div>

	<!-- Footer -->
	<?php include('include/footer.php'); ?>
</div>

<?php include('include/js.php'); ?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCIwF204lFZg1y4kPSIhKaHEXMLYxxuMhA"></script>
<script src="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="<?php echo SITEURL; ?>js/contact_custom.js"></script>
</body>

</html>
