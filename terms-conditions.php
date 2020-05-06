<?php 
	include('connect.php');
	$rs = $db->getData('static_page', '*', 'id=1 AND isDelete=0');
    $row = @mysqli_fetch_assoc($rs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Terms & Conditions</title>
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
                        <h2><?php echo $row['title']; ?></h2>
                    </div>
                    <div class="row">
                        <div class="col-12 column-container">
                            <div class="column">
                                <?php echo $row['descr']; ?>
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
</body>

</html>
