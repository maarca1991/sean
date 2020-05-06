<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');

	$slug = '';
	$cat_id = 0;

	if( isset($_REQUEST['slug']) )
	{
		$slug = $_REQUEST['slug'];
		$cat_id = $db->getValue('category', 'id', 'slug="' . $slug . '" AND isDelete=0');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Category</title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/categories_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/categories_responsive.css">
</head>

<body>
<div class="loader"></div>
<div class="super_container">

	<?php include('include/header.php'); ?>

	<div class="container product_section_container">
		<div class="row">
			<div class="col product_section clearfix">

				<!-- Breadcrumbs -->

				<div class="breadcrumbs d-flex flex-row align-items-center">
					
				</div>

				<!-- Sidebar -->

				<div class="sidebar">
					<div class="sidebar_section">
						<div class="sidebar_title">
							<h5>Product Category</h5>
						</div>
						<ul class="sidebar_categories">
						<?php
							$rs = $db->getData('category', 'name, slug', 'isDelete=0', 'display_order');
							while( $row = @mysqli_fetch_assoc($rs) )
							{
								$flg = 0;
								if( $row['name'] == 'women' )
									$flg = 1;

								echo '<li ';
								if( $flg )
									echo 'class="active"';
								echo '><a href="' . SITEURL.'cat/'.$row['slug'] . '/">';
								if( $flg )
									echo '<span><i class="fa fa-angle-double-right" aria-hidden="true"></i></span>';
								echo $row['name'] . '</a></li>';
							}
						?>
						</ul>
					</div>

				</div>

				<!-- Main Content -->

				<div class="main_content">
					<input type="hidden" name="my_page" id="my_page" value="1">
					<input type="hidden" name="my_rpp" id="my_rpp" value="8">
					<input type="hidden" name="my_sort" id="my_sort" value="">

					<!-- Products -->

					<!-- Product Sorting -->
					<div class="product_sorting_container product_sorting_container_top">
						<ul class="product_sorting">
							<li>
								<span class="type_sorting_text" id="dis_sort">Default Sorting</span>
								<i class="fa fa-angle-down"></i>
								<ul class="sorting_type">
									<li class="type_sorting_btn" data-isotope-option='{ "sortBy": "id" }'><span>Default Sorting</span></li>
									<li class="type_sorting_btn" data-isotope-option='{ "sortBy": "price" }'><span>Price</span></li>
									<li class="type_sorting_btn" data-isotope-option='{ "sortBy": "name" }'><span>Product Name</span></li>
								</ul>
							</li>
							<li>
								<span>Show</span>
								<span class="num_sorting_text" id="dis_rpp">8</span>
								<i class="fa fa-angle-down"></i>
								<ul class="sorting_num">
									<li class="num_sorting_btn" onclick="set_rpp(8);"><span>8</span></li>
									<li class="num_sorting_btn" onclick="set_rpp(16);"><span>16</span></li>
									<li class="num_sorting_btn" onclick="set_rpp(24);"><span>24</span></li>
								</ul>
							</li>
						</ul>
					</div>

					<div id="my_list"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<?php include('include/footer.php'); ?>
</div>

<?php include('include/js.php'); ?>
<script src="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
<script src="<?php echo SITEURL; ?>js/categories_custom.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		my_page(1);
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

	function my_page(id){
		var pageNum = id; 
		var rpp = $('#my_rpp').val();
		var sortby = $('#my_sort').val();

		$.ajax({
			url: '<?php echo SITEURL; ?>category_db.php',
			type: 'post',
			data: {page:pageNum,rec_per_pg:rpp,sortby:sortby,cat_id:<?php echo $cat_id; ?>},
            beforeSend: function(){
                $(".loader").show(); 
                $(document.body).addClass('no-pointer');    
            },
			success: function(data) {
				$('.pagination li a.active').removeClass('active');
				$("#" + pageNum + ' a').addClass('active');
				$("#my_list").html(data); 
				$("#my_page").val(id);

                $(".loader").hide();
                $(document.body).removeClass('no-pointer');
			},               
		}); // end ajax call          
	}

	function set_rpp(rec) {
		$('#my_rpp').val(rec);
		$('#dis_rpp').html(rec);
		my_page(1);
	}

	$('.type_sorting_btn').click(function() {
		var attr = $(this).attr('data-isotope-option');

		attr = attr.replace('{', '');
		attr = attr.replace('}', '');
		attr = attr.replace('"sortBy"', '');
		attr = attr.replace(':', '');
		attr = attr.replace('"', '');
		attr = attr.split(' ').join('');
		attr = attr.replace('"', '');
		//alert(attr);

		$('#my_sort').val(attr);
		if( attr == 'id' )
			$('#dis_sort').html('Default Sorting');
		else if( attr == 'price' )
			$('#dis_sort').html('Price');
		else if( attr == 'name' )
			$('#dis_sort').html('Product Name');

		my_page($('#my_page').val());
	});
</script>
</body>

</html>
