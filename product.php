<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');

	$slug = '';
	$cat_slug = '';
	$cat_id = 0;
	$product_id = 0;
	$cat_name = '';

	if( isset($_REQUEST['slug']) )
	{
		$slug = $_REQUEST['slug'];
		$product_id = $db->getValue('product', 'id', 'slug="' . $slug . '" AND isDelete=0');
	}

	if( isset($_REQUEST['cat_slug']) )
	{
		$cat_slug = $_REQUEST['cat_slug'];
		$cat_id = $db->getValue('category', 'id', 'slug="' . $cat_slug . '" AND isDelete=0');
		$cat_name = $db->getValue('category', 'name', 'id=' . (int) $cat_id . ' AND isDelete=0');
	}

	if( $cat_id == 0 )
	{
		$cat_id = $db->getValue('product_category', 'category_id', 'product_id='. (int) $product_id . ' AND isDelete=0');
		$rs_cat = $db->getData('category', 'name, slug', 'id=' . (int) $cat_id . ' AND isDelete=0');
		$row_cat = @mysqli_fetch_assoc($rs_cat);
		$cat_name = $row_cat['name'];
		$cat_slug = $row_cat['slug'];
	}

	$rs_prod = $db->getData('product', '*', 'id='. (int) $product_id . ' AND isDelete=0');
	$row = @mysqli_fetch_assoc($rs_prod);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $row['name']; ?></title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/single_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/single_responsive.css">
</head>

<body>
<div class="loader"></div>

<div class="super_container">
	<?php include('include/header.php'); ?>

	<div class="container single_product_container">
		<div class="row">
			<div class="col">
				<!-- Breadcrumbs -->
				<div class="breadcrumbs d-flex flex-row align-items-center">
					<ul>
						<li><a href="<?php echo SITEURL; ?>">Home</a></li>
						<li><a href="<?php echo SITEURL; ?>cat/<?php echo $cat_slug; ?>/"><i class="fa fa-angle-right" aria-hidden="true"></i><?php echo $cat_name; ?></a></li>
						<li class="active"><a href="#"><i class="fa fa-angle-right" aria-hidden="true"></i><?php echo $row['name']; ?></a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-7">
				<div class="single_product_pics">
					<div class="row">
						<div class="col-lg-3 thumbnails_col order-lg-1 order-2">
							<div class="single_product_thumbnails">
								<ul>
									<li class="active"><img src="<?php echo SITEURL.PRODUCT.$row['image_path']; ?>" alt="" data-image="<?php echo SITEURL.PRODUCT.$row['image_path']; ?>"></li>
								<?php
									$rs_alt = $db->getData('product_alt_image', 'image_path', 'product_id='.(int) $row['id'] . ' AND isDelete=0');
									while( $row_alt = @mysqli_fetch_assoc($rs_alt) )
									{
										echo '<li><img src="' . SITEURL.PRODUCT.$row_alt['image_path'] . '" alt="" data-image="' . SITEURL.PRODUCT.$row_alt['image_path'] . '"></li>';
									}
								?>
								</ul>
							</div>
						</div>
						<div class="col-lg-9 image_col order-lg-2 order-1">
							<div class="single_product_image">
								<div class="single_product_image_background" style="background-image:url(<?php echo SITEURL.PRODUCT.$row['image_path']; ?>)"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="product_details">
					<div class="product_details_title">
						<h2><?php echo $row['name']; ?></h2>
						<h4><?php echo $row['SKU']; ?></h4>
						<!-- <p><?php //echo substr(strip_tags($row['description']), 0, 200); ?>...</p> -->
						<?php if($row['description'] != '<p>---</p>') echo $row['description']; ?>

						<?php
							if( !empty($row['fabric']) && !is_null($row['fabric']) )
							{
								echo '<br /><h6>FABRIC</h6>';
								echo $row['fabric'];
							}
						?>

						<?php
							if( !empty($row['features']) && !is_null($row['features']) )
							{
								echo '<br /><h6>FEATURES</h6>';
								echo $row['features'];
							}
						?>
					</div>
					<div class="product_price" id="product_price"><?php echo CUR . $db->num($row['price']); ?></div>
					<div class="product_color">
						<span class="mr-3">Select Color:</span>
						<select name="color_id" id="color_id" class="form_input" onchange="show_price();">
						<?php
							$strquery = 'SELECT DISTINCT color_id, c.name FROM product_price pp
										 LEFT JOIN color c ON c.id = pp.color_id
										 WHERE product_id= ' . (int) $product_id . ' AND pp.isDelete=0 AND c.isDelete=0 
										 ORDER BY c.name';
							$rs_color = @mysqli_query($myconn, $strquery);
							while( $row_color = @mysqli_fetch_assoc($rs_color) )
							{
								echo '<option value="' . $row_color['color_id'] . '">' . $row_color['name'] . '</option>';
							}

						?>	
						</select>
					</div>
					<div class="product_color">
						<span class="mr-3">Select Shape:</span>
						<select name="size_id" id="size_id" class="form_input" onchange="show_price();">
						<?php
							$strquery = 'SELECT DISTINCT size_id, s.name FROM product_price pp
										 LEFT JOIN size s ON s.id = pp.size_id
										 WHERE product_id= ' . (int) $product_id . ' AND pp.isDelete=0 AND s.isDelete=0';
							$rs_size = @mysqli_query($myconn, $strquery);
							while( $row_size = @mysqli_fetch_assoc($rs_size) )
							{
								echo '<option value="' . $row_size['size_id'] . '">' . $row_size['name'] . '</option>';
							}

						?>	
						</select>
					</div>
					<div class="quantity d-flex flex-column flex-sm-row align-items-sm-center">
						<span>Quantity:</span>
						<div class="quantity_selector">
							<span class="minus" onclick="show_price('minus');"><i class="fa fa-minus" aria-hidden="true"></i></span>
							<span id="quantity_value">1</span>
							<span class="plus" onclick="show_price('plus');"><i class="fa fa-plus" aria-hidden="true"></i></span>
						</div>
						<div class="red_button add_to_cart_button"><a href="javascript:void(0);<?php //echo SITEURL.'cart/'; ?>" onclick="add_to_cart();">Add to Cart</a></div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Tabs -->

	<div class="tabs_section_container">

		<div class="container">
			<div class="row">
				<div class="col">
					<div class="tabs_container">
						<ul class="tabs d-flex flex-sm-row flex-column align-items-left align-items-md-center justify-content-center">
							<li class="tab active" data-active-tab="tab_1"><span>Description</span></li>
							<li class="tab" data-active-tab="tab_2"><span>Additional Information</span></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">

					<!-- Tab Description -->

					<div id="tab_1" class="tab_container active">
						<div class="row">
							<div class="col-lg-12 desc_col">
								<div class="tab_title">
									<h4>Description</h4>
								</div>
								<div class="tab_text_block">
									<h2><?php echo $row['name']; ?></h2>
									<?php if($row['description'] != '<p>---</p>') echo $row['description']; ?>

									<?php
										if( !empty($row['fabric']) && !is_null($row['fabric']) )
										{
											echo '<br /><h6>FABRIC</h6>';
											echo $row['fabric'];
										}
									?>

									<?php
										if( !empty($row['features']) && !is_null($row['features']) )
										{
											echo '<br /><h6>FEATURES</h6>';
											echo $row['features'];
										}
									?>

								</div>
							</div>
						</div>
					</div>

					<!-- Tab Additional Info -->

					<div id="tab_2" class="tab_container">
						<div class="row">
							<div class="col additional_info_col">
								<div class="tab_title additional_info_title">
									<h4>Additional Information</h4>
								</div>
								<?php echo $row['additional_info']; ?>
							</div>
						</div>
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
<script src="<?php echo SITEURL; ?>js/single_custom.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		show_price();
		$(".loader").hide();
	});
	function show_price(act='')
	{
		var color = $('#color_id').val();
		var size = $('#size_id').val();
		var qty = $('#quantity_value').text();

		if( act == 'plus' )
			qty++;
		if( act == 'minus' )
			qty--;
		if( qty <= 0 )
			qty = 1;

		$.ajax({
			url: '<?php echo SITEURL; ?>product_db.php', 
			method: 'post', 
			data: 'mode=get_price&color_id='+color+'&size_id='+size+'&product_id=<?php echo $product_id; ?>&qty='+qty, 
            beforeSend: function(){
                $(".loader").show(); 
                $(document.body).addClass('no-pointer');    
            },
			success: function(res) {
				$('#product_price').html(res);

                $(".loader").hide();
                $(document.body).removeClass('no-pointer');
			}
		});
	}

	function add_to_cart()
	{
		var qty = $('#quantity_value').text();
		var color = $('#color_id').val();
		var size = $('#size_id').val();

		$.ajax({
			url: '<?php echo SITEURL; ?>product_db.php', 
			method: 'post', 
			data: 'mode=add_to_cart&color_id='+color+'&size_id='+size+'&product_id=<?php echo $product_id; ?>&qty='+qty, 
            beforeSend: function(){
                $(".loader").show(); 
                $(document.body).addClass('no-pointer');    
            },
			success: function(res) {
                $(".loader").hide();
                $(document.body).removeClass('no-pointer');
				window.location = '<?php echo SITEURL; ?>cart/';
			}
		});
	}
</script>
</body>

</html>
