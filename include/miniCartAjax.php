<?php
require_once '../library/config.php';
require_once '../library/category-functions.php';
require_once '../library/product-functions.php';
require_once '../library/cart-functions.php';

if(isset($_GET['productAddTominCart'])){	//ตรวจสอบรหัสสินค้าที่ส่งเข้ามา
	$productId = $_GET['productAddTominCart'];
//ตรวจดูว่ามีสินค้านี้อยู่ในร้านค้าหรือไม่ 
	$sql = "SELECT pd_id, pd_qty
	        FROM tbl_product
			WHERE pd_id = $productId";
	$result = dbQuery($sql);
	$numRow = dbNumRows($result);

	if (dbNumRows($result) != 1) {
		//ถ้าไม่มีสินค้ารายการนี้อยู่ให้ออกไปเลย
		exit();
	} else {
		//ตรวจดูว่ามีสินค้าชิ้นนี้อยู่กี่ชิ้นในสต็อกสินค้า
		$row = dbFetchAssoc($result);
		$currentStock = $row['pd_qty'];

		if ($currentStock == 0) {
			//ถ้าไม่มีสินค้าในสต็อก ให้กำหนดค่า error
			setError('ขออภัย ไม่มีสินค้านี้อยู่ในสต็อกแล้ว');
			//ออกไปเลย
			exit;
		}

	}		
	
	//เก็บค่า session id 
	$sid = session_id();
	
	//ตรวจดูว่าสินค้ารายการนี้ อยู่ในตะกร้าสินค้าอยู่ก่อนแล้วหรือไม่
	//โดยเข้าไปดูในตาราง tbl_cart โดยตรวจสอบว่า  session เดียวกันนี้มีรายการสินค้านี้แล้วหรือยัง
	$sql = "SELECT pd_id
	        FROM tbl_cart
			WHERE pd_id = $productId AND ct_session_id = '$sid'";
	$result = dbQuery($sql);
	
	//ถ้าไม่เคยมีสินค้ารายการนี้มาก่อนในตะกร้า
	if (dbNumRows($result) == 0) {
		//เพิ่มสินค้าลงในตะกร้า
		$sql = "INSERT INTO tbl_cart (pd_id, ct_qty, ct_session_id, ct_date)
				VALUES ($productId, 1, '$sid', NOW())";
		$result = dbQuery($sql);
	} else {
		//แต่ถ้ามีสินค้านี้ในตะกร้าแล้ว เพิ่มจำนวนสินค้าในตะกร้า
		$sql = "UPDATE tbl_cart 
		        SET ct_qty = ct_qty + 1
				WHERE ct_session_id = '$sid' AND pd_id = $productId";		
				
		$result = dbQuery($sql);		
	}	
}
deleteAbandonedCart();		//ลบตะกร้าสินค้าของเมื่อวาน 
	
$cartContent = getCartContent();	//นำเอารายการสินค้าในตะกร้ามาเก็บใน $cartContent
$numItem = count($cartContent);		//นับจำนวนรายการสินค้าในตะกร้า
?>

<?php
if ($numItem > 0) {
?>
<div class="panel panel-info">
  <div class="panel-heading">ตะกร้าสินค้า</div>
  <div class="panel-body">
  
  
	<table cellspacing="0" cellpadding="2" id="minicart" class="table">
	<?php
	$subTotal = 0;
	for ($i = 0; $i < $numItem; $i++) {
		extract($cartContent[$i]);
		$pd_name = "$ct_qty x $pd_name";		//แสดงจำนวน x ชื่อสินค้า
		$subTotal += $pd_price * $ct_qty;		//คำนวณหาผลรวมย่อย
	?>
 	<tr>
   		<td><a href="#" id="product-<?php echo $pd_id; ?>" class="show-detail-product"><?php echo $pd_name; ?></a></td>
  		<td width="30%" align="right"><?php echo displayAmount($ct_qty * $pd_price); ?></td>
 	</tr>
	<?php
	} //สิ้นสุดลูป for
	?>
  	<tr>
  		<span class="hidden-cat-product" id="productmini-<?php $cat_id; ?>" hidden></span>
  		<td align="right">Sub-total</td>
  		<td width="30%" align="right"><?php echo displayAmount($subTotal); ?></td>
 	</tr>
 	<tr>
 		<td align="right">Shipping</td>
  		<td width="30%" align="right"><?php echo displayAmount($shopConfig['shippingCost']); ?></td>
 	</tr>
  	<tr>
  		<td align="right">Total</td>
  		<td width="30%" align="right"><?php echo displayAmount($subTotal + $shopConfig['shippingCost']); ?></td>
 	</tr>
  	<tr>
  		<td colspan="2">&nbsp;</td>
  	</tr>
  	<tr>
  		<td colspan="2" align="center"><a href="cart.php?action=view" class="btn btn-success">ไปยังตะกร้าสินค้า</a></td>
 	</tr> 
	</table> 
	</div>
</div>


<?php	
} else {
?>

			<div class="alert alert-warning">ยังไม่มีสินค้าในตะกร้า</div>

<?php
}
?>