<?php 
    include('connect.php');
    if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
        $db->location(SITEURL.'login/');

    //$a = json_encode($_POST);
    $a = json_encode($_REQUEST);
    $_POST = json_decode($a, true);
    //@mail("crazycoder08@gmail.com", "Paypal Response ", $a);

    //print_r($_POST);
     
    if(isset($_POST))
    {
        //if($_POST['payment_status'] == 'Completed')
        if($_POST['st'] == 'Completed')
        {
            $paypal_response = json_encode($_POST);
            $custom_arr = explode(",", $_POST['cm']);
           
            $user_id = $custom_arr[0];
            $cart_id = $custom_arr[1];
            $grandtotal = $custom_arr[2];
            $history_id = $custom_arr[3];

            if(isset($cart_id) && $cart_id > 0 )
            {               
                $adate = date('Y-m-d H:i:s');
                //$total_amount = $_POST['mc_gross'];
                //$transaction_id = $_POST['txn_id'];
                $total_amount = $_POST['amt'];
                $transaction_id = $_POST['tx'];
                    
                $rows = array(
                        "payment_status" => 2, 
                        "payment_date" => $adate, 
                        "txn_id" => $transaction_id, 
                        "err_msg" => 'success', 
                    );
                $db->update("payment_history", $rows, 'id=' . $history_id);

                $rows = array(
                        'order_status' => 2, 
                        'order_date' => $adate, 
                    );
                $db->update('cart', $rows, 'id=' . (int) $cart_id);

                unset($_SESSION[SESS_PRE.'_SESS_CART_ID']);

                if( ISMAIL )
                {
                    include("include/notification.class.php");
                    $nt = new Notification();

                    $rs_order = $db->getData('cart', '*', 'id=' . (int) $cart_id);
                    $row_order = @mysqli_fetch_assoc($rs_order);

                    $ardetail = array();
                    $strquery = 'SELECT ct.*, p.name AS product_name, p.image_path, p.slug, c.name AS color, s.name AS size 
                                 FROM cart_detail ct 
                                 LEFT JOIN product p ON p.id = ct.product_id 
                                 LEFT JOIN color c ON c.id = ct.color_id 
                                 LEFT JOIN size s ON s.id = ct.size_id 
                                 WHERE ct.cart_id = ' . (int) $cart_id . ' AND ct.isDelete=0 AND p.isDelete=0';
                    //print $strquery;
                    $rs_detail = @mysqli_query($myconn, $strquery);
                    while( $row_detail = @mysqli_fetch_assoc($rs_detail) )
                    {
                        array_push($ardetail, $row_detail);
                    }

                    /* START: admin email */
                    $subject = SITETITLE . ": New order received";
                    include("mailbody/admin_order.php");
                    //die($body);
                    $nt->sendEmail(SITEMAIL, $subject, $body);
                    /* END: admin email */

                    /* START: employee email */
                    $subject = SITETITLE . ": Your order has been placed successfully.";
                    include("mailbody/employee_order.php");
                    //die($body);
                    $nt->sendEmail($billing_email, $subject, $body);
                    /* END: employee email */
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Thank You</title>
<?php include('include/css.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/themify-icons/themify-icons.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>plugins/jquery-ui-1.12.1.custom/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_styles.css">
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL; ?>styles/contact_responsive.css">
</head>

<body>

    <div class="super_container">

        <?php include('include/header.php'); ?>
        <div class="contact_container shopping-cart">
            <div class="pb-5">
                <div class="container">
                    <div class="cart-heading text-left py-5 mb-4">
                        <h2>Thank You</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-12 column-container" style="text-align: center;">
                            <div class="mt-5">
                                <h3>We appreciate your business!</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 column-container" style="text-align: center;">
                            <img src="<?php echo SITEURL; ?>images/thank-you.gif" class="img-fluid">
                            <br /><br /><br />
                            <a href="<?php echo SITEURL; ?>" class="button color">Go To Home</a>
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
</body>

</html>
