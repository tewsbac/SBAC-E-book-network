<?php

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
include_once '../library/category-functions.php';
include_once '../library/product-functions.php';



if(!isset($_GET['pdId'])){
	exit();
}
$pdId = (isset($_GET['pdId']))?$_GET['pdId']:0;
$catId = (isset($_GET['catId']))?$_GET['catId']:0;

$product = getProductDetail($pdId, $catId);

// we have $pd_name, $pd_price, $pd_description, $pd_image, $cart_url
extract($product);
?> 
<table width="100%" border="0" cellspacing="0" cellpadding="10">
 <tr> 
  <td align="center">
  	<img src="<?php echo $pd_image; ?>" class="img-responsive img-rounded" border="0" alt="<?php echo $pd_name; ?>">
  	<br><input name="btnHome" type="button" id="btnHome-<?php echo $cat_id; ?>" value="&lt;&lt; กลับไปยังร้านค้า" class="btn btn-success home-link">
  	<p>&nbsp;</p>
  </td>
  <td width="2%">
  	<p>&nbsp;</p>
  </td>
  <td valign="middle" width="50%">
<strong><?php echo $pd_name; ?></strong><br>
ราคา : <?php echo displayAmount($pd_price); ?><br>
<?php
// if we still have this product in stock
// show the 'Add to cart' button
		if ($pd_qty > 0) {
?>
			<span>ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" name="btnAddToCart" class="btn btn-primary add-to-cart" id="cart-<?php echo $pdId; ?>">
				<span class="glyphicon glyphicon-shopping-cart"></span>ใส่ตะกร้า
			</button>
<?php
		} else {
?>
			<span style="text-decoration:line-through;color:#818181;">ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" class="disabled btn btn-danger btn-xs">สินค้าหมด</button>
<?php
		}


?>

<hr>
<div class="panel panel-default">
  <div class="panel-heading">รายละเอียด</div>
  <div class="panel-body">
		<?php echo $pd_description; ?>
  </div>
</div>
  </td>
</table>


