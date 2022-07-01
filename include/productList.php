<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$productsPerRow = 4;
$productsPerPage = 8;

//$productList    = getProductList($catId);
$children = array_merge(array($catId), getChildCategories(NULL, $catId));
$children = ' (' . implode(', ', $children) . ')';

$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, c.cat_id
		FROM tbl_product pd, tbl_category c
		WHERE c.cat_id = $catId AND pd.cat_id IN $children 
		ORDER BY pd_name";
$result     = dbQuery(getPagingQuery($sql, $productsPerPage));
$pagingLink = getPagingLinkForBootStrap($sql, $productsPerPage, "c=$catId");
$numProduct = dbNumRows($result);

// the product images are arranged in a table. to make sure
// each image gets equal space set the cell width here
$columnWidth = (int)(100 / $productsPerRow);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="20">
<?php 
if ($numProduct > 0 ) {

	$i = 0;
	while ($row = dbFetchAssoc($result)) {
	
		extract($row);
		if ($pd_thumbnail) {
			$pd_thumbnail = WEB_ROOT . 'images/product/' . $pd_thumbnail;
		} else {
			$pd_thumbnail = WEB_ROOT . 'images/no-image-small.png';
		}
	
		if ($i % $productsPerRow == 0) {
			echo '<tr>';
		}
		$cartURL = 'cart.php?action=add&p='.$pd_id; //ทำลิงค์สำหรับใส่ตะกร้าสินค้า
		// format how we display the price
		$pd_price = displayAmount($pd_price);
		
		echo "<td width=\"$columnWidth%\" align=\"center\" style=\"padding-top:10px;\"><a href=\"" . $_SERVER['PHP_SELF'] . "?c=$catId&p=$pd_id" . "\"><img src=\"$pd_thumbnail\" border=\"0\" class=\"img-thumbnail\">
		<br>$pd_name</a><br>";


		if ($pd_qty > 0) {
?>
			<span>ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" name="btnAddToCart"  onClick="window.location.href='<?php echo $cartURL; ?>';" class="btn btn-primary btn-xs">
				<span class="glyphicon glyphicon-shopping-cart"></span>ใส่ตะกร้า
			</button>
<?php
		} else {
?>
			<span style="text-decoration:line-through;color:#818181;">ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" class="disabled btn btn-danger btn-xs">สินค้าหมด</button>
<?php
		}
		echo "</td>\r\n";
	
		if ($i % $productsPerRow == $productsPerRow - 1) {
			echo '</tr>';
		}
		
		$i += 1;
	}
	
	if ($i % $productsPerRow > 0) {
		echo '<td colspan="' . ($productsPerRow - ($i % $productsPerRow)) . '">&nbsp;</td>';
	}
	
} else {
?>
	<tr><td width="100%" align="center" valign="center"><div class="alert alert-info">ยังไม่มีสินค้าในหมวดหมู่นี้</div></td></tr>
<?php	
}	
?>
</table>
<p align="center"><?php echo $pagingLink; ?></p>