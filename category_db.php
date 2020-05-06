<?php
	include('connect.php');
    $user_id = (int) $_SESSION[SESS_PRE.'_SESS_USER_ID'];

    //print_r($_REQUEST); exit;

    $cat_id = (isset($_REQUEST["cat_id"]))?$_REQUEST["cat_id"]:0;

    if (isset($_REQUEST["page"])) 
        $current_page = $_REQUEST["page"];
    else
        $current_page = 1;

    $num_show = 6; /* Default records per page */
    if(isset($_REQUEST["rec_per_pg"])) 
        $num_show = $_REQUEST["rec_per_pg"];

    $sortby = 'id DESC'; /* Default sort by */
    if( isset($_REQUEST["sortby"]) && !empty($_REQUEST["sortby"]) && !is_null($_REQUEST["sortby"]) ) 
        $sortby = $_REQUEST["sortby"];
    if( $sortby == 'id' )
       $sortby = 'id DESC'; 

    $start_from = ($current_page-1) * $num_show;
    $limit = " LIMIT $start_from , $num_show"; 

    $strquery = 'SELECT DISTINCT p.* FROM product p 
                LEFT JOIN product_category pc ON pc.product_id = p.id 
                WHERE pc.isDelete=0 AND p.isDelete=0';
    if( $cat_id > 0 )
        $strquery .= ' AND pc.category_id = ' . (int) $cat_id;
    //print $strquery; 
    $rs = @mysqli_query($myconn, $strquery);

    $total_records = @mysqli_num_rows($rs);
    $total_pages = ceil($total_records / $num_show);

    // if total records are less than start no. to be displayed
    if( $total_records < $start_from )
    {
        $start_from = 0;
        $current_page = 1;
        $limit = " LIMIT $start_from , $num_show"; 
    }
    $strquery .= ' ORDER BY ' . $sortby;
    $strquery .= $limit;
    //print $strquery; 
    $rs = @mysqli_query($myconn, $strquery);
?>

    <div class="products_iso"> 
        <div class="row"> 
            <div class="col"> 
                <div class="product-grid">
<?php
    $cat_slug = '';
    if( $cat_id > 0 )
        $cat_slug = $db->getValue('category', 'slug', 'id='.(int)$cat_id . ' AND isDelete=0');

    if( @mysqli_num_rows($rs) > 0 )
    {
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

            $product_url = SITEURL.'prod/';
            if( !empty($cat_slug) )
                $product_url .= $cat_slug . '/';
            $product_url .= $row['slug'].'/';
    ?>
          <!-- <div class=""> -->
            <div class="product discount product_filter">
              <div class="product_image">
                <a href="<?php echo $product_url; ?>"><img src="<?php echo SITEURL.PRODUCT.$row['image_path']; ?>" alt="<?php echo $row['name']; ?>" title="<?php echo $row['name']; ?>"></a>
              </div>
              
              <div class="product_info">
                <h6 class="product_name"><a href="<?php echo $product_url; ?>"><?php echo $row['name']; ?></a></h6>
                <div class="product_price"><?php echo CUR.$db->num($row['price']); ?>
              </div>
            </div>
            <div class="red_button add_to_cart_button"><a href="javascript:void(0);" onclick="add_to_cart(<?php echo $row['id']; ?>);">Add to Cart</a></div>
          </div>
    <?php
        }
    }
    else
       echo 'No record found';
 ?>
                <!-- </div>  -->
            </div> 
        </div> 
    </div>

 <!-- Start Pagination -->
 <?php
   if( $total_pages > 1 )
   {
        $disp_start = $start_from + 1;
        $disp_end = $start_from + $num_show;
        if( $disp_end > $total_records )
            $disp_end = $total_records;
 ?>
    <div class="product_sorting_container product_sorting_container_bottom clearfix">
        <span class="showing_results">Showing <?php echo $disp_start; ?>–<?php echo $disp_end; ?> of <?php echo $total_records; ?> results</span>
        <div class="pages d-flex flex-row align-items-center">
            <div class="page_current">
                <span><?php echo $current_page; ?></span>
                <ul class="page_selection">
                <?php
                    for($i=0; $i<$total_pages; $i++)
                    {
                        echo '<li><a href="javascript:void(0);" onclick="my_page(' . ($i+1) . ');">' . ($i+1) . '</a></li>';
                    }   
                ?>
                </ul>
            </div>
            <div class="page_total"><span>of</span> <?php echo $total_pages; ?></div>
            <div id="next_page_1" class="page_next"><a href="javascript:void(0);" onclick="my_page(<?php echo ($current_page+1); ?>);"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a></div>
        </div>
    </div>
 <?php 
   }
 ?>
 <!-- End Pagination -->

