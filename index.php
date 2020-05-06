<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITETITLE; ?></title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/main_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/responsive.css">
</head>

<body>
<div class="loader"></div>
<div class="super_container">
	<?php include('include/header.php'); ?>
	<!-- Slider -->

	<div class="main_slider" style="background-image:url(images/banner2.jpg)">
		<div class="container fill_height">
			<div class="row align-items-center fill_height">
				<div class="col">
					<div class="main_slider_content" style="padding-left: 190px;">
						<h2>WELCOME</h2>
						<h6>To the uniform purchasing system by <br />DREAMTIME APPAREL, for the purchase <br />of workwear, uniforms & accessories <br />to Foxtel employee's & contractors.</h6>
						<div class="grey_button shop_now_button"><a href="<?php echo SITEURL; ?>categories/">SHOP NOW</a></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner -->

	<div class="banner">
		<div class="container">
			<div class="row">
			<?php
				$rs_cat = $db->getData('category', '*', 'isFeatured=1 AND isDelete=0');
				while( $row_cat = @mysqli_fetch_assoc($rs_cat) )
				{
			?>
				<div class="col-md-4">
					<div class="banner_item align-items-center" style="background-image:url(<?php echo SITEURL.CATEGORY.$row_cat['image_path']; ?>)">
						<div class="banner_category">
							<a href="<?php echo SITEURL; ?>cat/<?php echo $row_cat['slug']; ?>"><?php echo $row_cat['name']; ?></a>
						</div>
					</div>
				</div>
			<?php
				}
			?>
			</div>
		</div>
	</div>

	<!-- New Arrivals -->

	<div class="new_arrivals">
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<div class="section_title new_arrivals_title">
						<h2>Current Range</h2>
					</div>
				</div>
			</div>
			<div class="row align-items-center">
				<div class="col text-center">
					<div class="new_arrivals_sorting">
						<ul class="arrivals_grid_sorting clearfix button-group filters-button-group">
							<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center active is-checked" data-filter="*">all</li>
						<?php
							$rs_cat = $db->getData('category', 'name, slug', 'isFeatured=1 AND isDelete=0', 'display_order');
							while( $row_cat = @mysqli_fetch_assoc($rs_cat) )
							{
						?>
							<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".<?php echo $row_cat['slug']; ?>"><?php echo $row_cat['name']; ?></li>
						<?php
							}
						?>
							<!-- <li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".accessories">accessories</li>
							<li class="grid_sorting_button button d-flex flex-column justify-content-center align-items-center" data-filter=".men">men's</li> -->
						</ul>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="product-grid" data-isotope='{ "itemSelector": ".product-item", "layoutMode": "fitRows" }'>

					<?php
						$rs = $db->getData('product', '*', 'isFeatured=1 AND isDelete=0');
						while( $row = @mysqli_fetch_assoc($rs) )
						{
							$str_slug = '';
							$strquery = 'SELECT c.slug FROM category c 
										LEFT JOIN product_category pc ON pc.category_id = c.id 
										WHERE pc.product_id='.(int)$row['id'].' AND c.isDelete=0';
							//print $strquery . '<br />';
							$rs_cat = @mysqli_query($myconn, $strquery);
							while( $row_cat = @mysqli_fetch_assoc($rs_cat) )
								$str_slug .= $row_cat['slug'] . ' ';
					?>
						<div class="product-item <?php echo $str_slug; ?>">
							<div class="product discount product_filter">
								<div class="product_image">
									<a href="<?php echo SITEURL.'prod/'.$row['slug']; ?>/"><img src="<?php echo SITEURL.PRODUCT.$row['image_path']; ?>" alt="<?php echo $row['name']; ?>" title="<?php echo $row['name']; ?>"></a>
								</div>								
								
								<div class="product_info">
									<h6 class="product_name"><a href="<?php echo SITEURL.'prod/'.$row['slug']; ?>/"><?php echo $row['name']; ?></a></h6>
									<div class="product_price"><?php echo CUR.$db->num($row['price']); ?></div>
								</div>
							</div>
							<div class="red_button add_to_cart_button"><a href="javascript:void(0);" onclick="add_to_cart(<?php echo $row['id']; ?>);">Add to Cart</a></div>
						</div>
					<?php
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php include('include/footer.php'); ?>
</div>

<?php include('include/js.php'); ?>
<script src="<?php echo SITEURL; ?>js/custom.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".loader").hide();
	});

	function add_to_cart(product_id)
	{
		$.ajax({
			url: '<?php echo SITEURL; ?>product_db.php', 
			method: 'post', 
			data: 'mode=add_general&product_id='+product_id, 
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
