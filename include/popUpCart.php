<?php
require_once '../library/config.php';
require_once '../library/category-functions.php';
require_once '../library/product-functions.php';
require_once '../library/cart-functions.php';

if(isset($_GET['productAddTominCart'])){
	$productId = $_GET['productAddTominCart'];
// does the product exist ?
	$sql = "SELECT pd_id, pd_qty
	        FROM tbl_product
			WHERE pd_id = $productId";
	$result = dbQuery($sql);
	$numRow = dbNumRows($result);

	if (dbNumRows($result) != 1) {
		// the product doesn't exist
		exit();
	} else {
		// how many of this product we
		// have in stock
		$row = dbFetchAssoc($result);
		$currentStock = $row['pd_qty'];

		if ($currentStock == 0) {
			// we no longer have this product in stock
			// show the error message
			setError('The product you requested is no longer in stock');
			header('Location: cart.php');
			exit;
		}
	}		
	
	// current session id
	$sid = session_id();
	
	// check if the product is already
	// in cart table for this session
	$sql = "SELECT pd_id
	        FROM tbl_cart
			WHERE pd_id = $productId AND ct_session_id = '$sid'";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 0) {
		// put the product in cart table
		$sql = "INSERT INTO tbl_cart (pd_id, ct_qty, ct_session_id, ct_date)
				VALUES ($productId, 1, '$sid', NOW())";
		$result = dbQuery($sql);
	} else {
		// update product quantity in cart table
		$sql = "UPDATE tbl_cart 
		        SET ct_qty = ct_qty + 1
				WHERE ct_session_id = '$sid' AND pd_id = $productId";		
				
		$result = dbQuery($sql);		
	}	
}
/*
	$sqlRecent = "SELECT pd_id, pd_qty, pd_name
	        FROM tbl_cart
			ORDER BY pd_id DESC LIMIT 1";
	$resultRecent = dbQuery($sqlRecent);
	$numRowRecent = dbNumRows($resultRecent);
	if($numRowRecent != 1){
		exit();
	}
	$rowRecent = dbFetchAssoc($resultRecent);
	extract($rowRecent);
	*/
?>


<div class="panel panel-info">
  <div class="panel-heading">สินค้าที่ใส่ลงในตะกร้า</div>
  <div class="panel-body">
	<table cellspacing="0" cellpadding="2" id="minicart" class="table">
	<?php
	$subTotal = 0;
	$pd_name = "$ct_qty x $pd_name";		
	$subTotal += $pd_price * $ct_qty;
	?>
 	<tr>
   		<td><a href="#" id="product-<?php echo $pd_id; ?>" class="show-detail-product"><?php echo $pd_name; ?></a></td>
  		<td width="30%" align="right"><?php echo displayAmount($ct_qty * $pd_price); ?></td>
 	</tr>
  	<tr>
  		<span class="hidden-cat-product" id="productmini-<?php $cat_id; ?>" hidden></span>
  		<td align="right">รวมย่อย</td>
  		<td width="30%" align="right"><?php echo displayAmount($subTotal); ?></td>
 	</tr>
	</table> 
	</div>
</div>



