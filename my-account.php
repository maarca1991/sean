<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');

    $user_id = 0;
    if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
    {
        $user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>My Account</title>
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
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i>Change Password</a></li>
					</ul>
				</div>

			</div>
		</div>

		<!-- Contact Us -->

		<div class="row justify-content-center">
		  <div class="col-lg-4 get_in_touch_col">
		  	<div class="shadow p-5">
			    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
			      	<a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">
			      		Order History
			      	</a>
			      	<a class="nav-link" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="false">
			      		Change Password
			      	</a>
			    </div>
			</div>
		  </div>
		  <div class="col-lg-8 get_in_touch_col">
		  	<div class="shadow p-5">
			    <div class="tab-content" id="v-pills-tabContent">
			      	<!-- START: Order History  -->
			      	<input type="hidden" name="my_page" id="my_page" value="1">
			      	<div class="tab-pane fadefade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
						<div id="show_history"></div>
				  	</div>
			      	<!-- END: Order History  -->

			      	<!-- START: Change Password  -->
			      	<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
				      	<h3 class="mb-3">Change Password</h3>
						<form name="frm" id="frm" method="post" action="<?php echo SITEURL; ?>process-change-password/">
							<input type="password" name="old_password" id="old_password" class="form_input input_name input_ph" required="required" data-error="Password is required." placeholder="Current Password">
							<input type="password" name="new_password" id="new_password" class="form_input input_name input_ph" required="required" data-error="Password is required." placeholder="New Password">
							<input type="password" name="confirm_password" id="confirm_password" class="form_input input_name input_ph" required="required" data-error="Password is required." placeholder="Confirm Password">
							<div class="">
								<button id="submit" type="submit" class="red_button message_submit_btn trans_300" value="Submit">Submit</button>
							</div>
						</form>
			      	</div>
			      	<!-- END: Change Password  -->
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
    $(document).ready(function() {
    	my_page(1);
        $(".loader").hide(); 
    });

	function my_page(id){
		var pageNum = id; 

		$.ajax({
			url: '<?php echo SITEURL; ?>order-history/',
			type: 'post',
			data: {page:pageNum},
			beforeSend: function(){
				$(".loader").show();
			},
			success: function(data) {
				$('.pagination li a.active').removeClass('active');
				$("#" + pageNum + ' a').addClass('active');
				$("#show_history").html(data); 
				$("#my_page").val(id);
				$(".loader").hide();              
			},               
		}); // end ajax call          
	}

	$(function(){
		$("#frm").validate({
			rules: {
				old_password:{required: true},
				new_password:{required: true},
				confirm_password:{required: true, equalTo: "#new_password"},
			},
			messages: {
				old_password:{required:"Please enter current password."},
				new_password:{required:"Please enter new password."},
				confirm_password:{required:"Please enter confirm password.", equalTo:"New password and Confirm password do not match."},
			}
		});
	});
</script>
</body>

</html>
