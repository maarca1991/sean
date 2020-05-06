<?php 
	include('connect.php');
	if( !isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) || $_SESSION[SESS_PRE.'_SESS_USER_ID'] <= 0 )
		$db->location(SITEURL.'login/');

    $user_id = 0;
    if( isset($_SESSION[SESS_PRE.'_SESS_USER_ID']) && $_SESSION[SESS_PRE.'_SESS_USER_ID'] > 0 )
    {
        $user_id = $_SESSION[SESS_PRE.'_SESS_USER_ID'];
    }

    if (isset($_REQUEST["page"])) 
        $current_page = $_REQUEST["page"];
    else
        $current_page = 1;

    $num_show = 25; /* Default records per page */
    $start_from = ($current_page-1) * $num_show;
    $limit = " LIMIT $start_from , $num_show"; 

	$rs_order = $db->getData('cart', '*', 'employee_id=' . (int) $user_id . ' AND order_status <> 1 AND isDelete=0', 'order_date DESC, id DESC');

    $total_records = @mysqli_num_rows($rs_order);
    $total_pages = ceil($total_records / $num_show);

    // if total records are less than start no. to be displayed
    if( $total_records < $start_from )
    {
        $start_from = 0;
        $current_page = 1;
        $limit = " LIMIT $start_from , $num_show"; 
    }

    $rs_order = $db->getData('cart', '*', 'employee_id=' . (int) $user_id . ' AND order_status <> 1 AND isDelete=0', 'order_date DESC, id DESC' . $limit);

?>
	<h3 class="mb-3">Order History</h3>
	<?php
		if( @mysqli_num_rows($rs_order) > 0 ) 
		{
	?>
	<table class="table">
		<thead>
		<tr>
			<th>#</th>
			<th>Order #</th>
			<th>Order Date</th>
			<th>Amount</th>
			<th>Status</th>
		</tr>
		</thead>
		<tbody>
		<?php
			$counter = $start_from;
			while( $row_order = @mysqli_fetch_assoc($rs_order) )
			{
				$counter++;
		?>
			<tr>
				<td><?php echo $counter; ?></td>
				<td><a href="<?php echo SITEURL; ?>order-details/<?php echo $row_order['id']; ?>/" class="text-dark"><?php echo $row_order['order_no']; ?></a></td>
				<td><?php echo $db->date($row_order['order_date'], 'm/d/Y'); ?></td>
				<td class="text-center"><?php echo CUR.$db->num($row_order['grand_total']); ?></td>
				<td><?php 
						switch( $row_order['order_status'] )
						{
							case 0:
								echo 'Cancelled';
								break; 
							case 2:
								echo 'Completed';
								break; 
							case 3:
								echo 'Shipped';
								break; 
							case 4:
								echo 'Delivered';
								break; 
							default:
								echo 'In Progress';
								break; 
						}
					?>
				</td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
	<?php
		}
		else
		{
			echo 'No record found';
		}
	?>

 <!-- Start Pagination -->
 <?php
   if( $total_pages > 1 )
   {
 ?>
 <div class="col-xs-12">
     <div class="shop-toolbar fix">
         <div class="pagination text-center">
             <ul>
                 <?php
                     if( $current_page > 1 )
                        echo '<li id="prev"><a href="javascript:void(0);" onclick="my_page(' . ($current_page-1) . ');"><i class="fa fa-angle-left"></i></a></li>';
                     for($i=0; $i<$total_pages; $i++)
                     {
                        echo '<li id="' . ($i+1) . '"><a href="javascript:void(0);" onclick="my_page(' . ($i+1) . ');"';
                        if( $current_page == ($i+1) )
                           echo ' class="active"';
                        echo '>' . ($i+1) . '</a></li>';
                     }
                     if( $current_page < $total_pages )
                        echo '<li id="next"><a href="javascript:void(0);" onclick="my_page(' . ($current_page+1) . ');"><i class="fa fa-angle-right"></i></a></li>';
                 ?>
             </ul>
         </div>
     </div>
 </div>
 <?php 
   }
 ?>
 <!-- End Pagination -->
