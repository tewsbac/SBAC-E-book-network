<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$product = getProductDetail($pdId, $catId);

// we have $pd_name, $pd_price, $pd_description, $pd_image, $cart_url
extract($product);
?> 
<table width="100%" border="0" cellspacing="0" cellpadding="10">
 <tr> 
  <td align="center"><img src="<?php echo $pd_image; ?>" class="img-responsive img-rounded" border="0" alt="<?php echo $pd_name; ?>"></td>
  <td valign="middle" width="50%">
<strong><?php echo $pd_name; ?></strong><br>
ราคา : <?php echo displayAmount($pd_price); ?><br>
<?php
// if we still have this product in stock
// show the 'Add to cart' button
if ($pd_qty > 0) {
?>


<button type="button" class="btn btn-info btn-sm" name="btnAddToCart" value="Add To Cart &gt;" onClick="window.location.href='<?php echo $cart_url; ?>';">
  <span class="glyphicon glyphicon-shopping-cart"></span>ใส่ตะกร้า >>
</button>
<?php
} else {
	echo '<span class="label label-warning">สินค้าหมด</span>';
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
