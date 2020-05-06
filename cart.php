<?php 
	include('connect.php');
    if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
        $db->location(SITEURL.'login/');

    $cart_id = 0;

    if( isset($_SESSION[SESS_PRE.'_SESS_CART_ID']) && $_SESSION[SESS_PRE.'_SESS_CART_ID'] > 0 )
    {
        $cart_id = $_SESSION[SESS_PRE.'_SESS_CART_ID'];
    }

    $strquery = 'SELECT ct.*, p.name AS product_name, p.image_path, p.slug, c.name AS color, s.name AS size 
                 FROM cart_detail ct 
                 LEFT JOIN product p ON p.id = ct.product_id 
                 LEFT JOIN color c ON c.id = ct.color_id 
                 LEFT JOIN size s ON s.id = ct.size_id 
                 WHERE ct.cart_id = ' . (int) $cart_id . ' AND ct.isDelete=0 AND p.isDelete=0';
    //print $strquery;
    $rs = @mysqli_query($myconn, $strquery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Cart</title>
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
	<div class="contact_container shopping-cart">
		
	<div class="pb-5">
    	<div class="container">
    	<div class="cart-heading text-left py-5 mb-4"><h2>My Cart</h2></div>
    	<div class="row">
      <div class="col-lg-8">
        <div class="rounded shadow mb-5">

        <?php
            if( @mysqli_num_rows($rs) > 0 )
            {
        ?>
          <!-- Shopping cart table -->
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="border-0 bg-light">
                    <div class="p-2 px-3 text-uppercase">Product</div>
                  </th>
                  <th scope="col" class="border-0 bg-light text-center">
                    <div class="py-2 text-uppercase">Price</div>
                  </th>
                  <th scope="col" class="border-0 bg-light text-center">
                    <div class="py-2 text-uppercase">Quantity</div>
                  </th>
                  <th scope="col" class="border-0 bg-light text-center">
                    <div class="py-2 text-uppercase">Total</div>
                  </th>
                  <th scope="col" class="border-0 bg-light text-center">
                    <div class="py-2 text-uppercase">Remove</div>
                  </th>
                </tr>
              </thead>
              <tbody>
              <?php
                $counter = 0;
                while( $row = @mysqli_fetch_assoc($rs) )
                {
                    $counter++;
              ?>
               <tr id="row_<?php echo $counter; ?>">
                  <td scope="row" class="border-0">
                    <div class="p-2 d-flex align-items-start">
                      <input type="hidden" name="product_id[]" id="product_id_<?php echo $counter; ?>" value="<?php echo $row['product_id']; ?>">
                      <input type="hidden" name="color_id[]" id="color_id_<?php echo $counter; ?>" value="<?php echo $row['color_id']; ?>">
                      <input type="hidden" name="size_id[]" id="size_id_<?php echo $counter; ?>" value="<?php echo $row['size_id']; ?>">

                      <img src="<?php echo SITEURL.PRODUCT.$row['image_path']; ?>" alt="" width="70" class="img-fluid rounded shadow-sm">
                      <div class="ml-3 d-inline-block align-middle">
                        <h5 class="mb-0"> <a href="<?php echo SITEURL; ?>prod/<?php echo $row['slug']; ?>/" class="text-dark d-inline-block align-middle"><?php echo $row['product_name']; ?></a></h5>
                        <span class="text-muted font-weight-normal font-italic d-block">Color: <?php echo $row['color']; ?></span>
                        <span class="text-muted font-weight-normal font-italic d-block">Size: <?php echo $row['size']; ?></span>
                      </div>
                    </div>
                  </td>
                  <td class="border-0 align-middle text-center"><strong><?php echo CUR.$db->num($row['price']); ?></strong></td>
                  <td class="border-0 align-middle text-center"><input type="number" name="qty[]" id="qty_<?php echo $counter; ?>" class="form-control text-center num" value="<?php echo $row['qty']; ?>"></td>
                  <td class="border-0 align-middle text-center"><strong id="total_<?php echo $counter; ?>"><?php echo CUR.$db->num($row['sub_total']); ?></strong></td>
                  <td class="border-0 align-middle text-center"><a href="javascript:void(0);" onclick="remove_item(<?php echo $counter; ?>);" class="text-dark"><i class="fa fa-trash"></i></a></td>
                </tr>
              <?php
                }
              ?>
              </tbody>
            </table>
          </div>
          <!-- End -->
        <?php
            }
            else
            {
        ?>
        <div class="col-sm-12 col-sm-offset-3 text-center">
          <h3>Your shopping cart is empty</h3>
          <img src="<?php echo SITEURL; ?>images/Cart-empty.gif">
          <br /><br />
        </div>
        <?php
            }
        ?>
        </div>
        <a href="<?php echo SITEURL; ?>categories/" class="btn btn-dark rounded-pill py-2">Continue Shopping</a>
      </div>

      <div class="col-lg-4">
      	<div class="bg-light rounded-pill px-4 py-3 text-uppercase font-weight-bold">Order Summary </div>
        <?php
            $rs_cart = $db->getData('cart', '*', 'id=' . (int) $cart_id . ' AND isDelete=0');
            $row_cart = @mysqli_fetch_assoc($rs_cart);
        ?>
      	<div class="shadow px-4 pb-3">
           <!-- <p class="font-italic mb-4">Shipping and additional costs are calculated based on values you have entered.</p> -->
            <ul class="list-unstyled mb-4">
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Sub Total </strong><strong id="subtotal"><?php echo CUR.$db->num($row_cart['sub_total'], 2, ".", "", false); ?></strong></li>
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Tax</strong><strong id="tax"><?php echo CUR.$db->num($row_cart['tax'], 2, ".", "", false); ?></strong></li>
              <li class="d-flex justify-content-between py-3 border-bottom"><strong class="text-muted">Grand Total</strong>
                <h5 class="font-weight-bold" id="grandtotal"><?php echo CUR.$db->num($row_cart['grand_total'], 2, ".", "", false); ?></h5>
              </li>
            </ul>
            <?php
              if( $row_cart['grand_total'] > 0 )
              {
            ?>
            <a href="<?php echo SITEURL; ?>checkout/" class="btn btn-dark rounded-pill py-2 btn-block">Proceed to Checkout</a>
            <?php
              }
            ?>
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
<script src="<?php echo SITEURL; ?>js/contact_custom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".loader").hide(); 
    });

    $('.num').change(function() {
        //alert($(this).val() );
        if( $(this).val() == 'undefined' || $(this).val() <= 0 )
            $(this).val(1);
        //alert('========' + $(this).val());

        var qty = $(this).val();
        var id = $(this).attr('id');
        counter = id.replace('qty_', '');

        product_id = $('#product_id_'+counter).val();
        color_id = $('#color_id_'+counter).val();
        size_id = $('#size_id_'+counter).val();

        $.ajax({
            url: '<?php echo SITEURL; ?>cart_db.php', 
            method: 'post', 
            dataType: 'json', 
            data: 'mode=update_qty&product_id='+product_id+'&color_id='+color_id+'&size_id='+size_id+'&qty='+qty,
            beforeSend: function(){
                $(".loader").show(); 
                $(document.body).addClass('no-pointer');    
            },
            success: function(res) {
                $('#total_'+counter).html(res.product_total);
                $('#subtotal').html(res.sub_total);
                $('#tax').html(res.tax);
                $('#grandtotal').html(res.grand_total);

                $(".loader").hide();
                $(document.body).removeClass('no-pointer');
            }
        });
    });

    function remove_item(counter)
    {
      if( confirm('Are you sure to remove the product?') )
      {
        product_id = $('#product_id_'+counter).val();
        color_id = $('#color_id_'+counter).val();
        size_id = $('#size_id_'+counter).val();

        $.ajax({
            url: '<?php echo SITEURL; ?>cart_db.php', 
            method: 'post', 
            dataType: 'json', 
            data: 'mode=delete&product_id='+product_id+'&color_id='+color_id+'&size_id='+size_id,
            beforeSend: function(){
                $(".loader").show(); 
                $(document.body).addClass('no-pointer');    
            },
            success: function(res) {
                $('#checkout_items').html(res.no_of_item);     // update cart total in header
                $('#subtotal').html(res.sub_total);
                $('#tax').html(res.tax);
                $('#grandtotal').html(res.grand_total);
                $('#row_'+counter).remove();

                $(".loader").hide();
                $(document.body).removeClass('no-pointer');
            }
        });
      }
    }

</script>
</body>

</html>
