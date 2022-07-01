<?php

include_once '../library/config.php';
include_once '../library/category-functions.php';
include_once '../library/product-functions.php';

$productsPerRow = 4;
$productsPerPage = 8;


	//ตรวจสอบหมายเลขหน้า ถ้าไม่มีก็ให้กำหนดให้หมายเลขหน้าเป็น 1
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
	
$searchTerm = $_GET['strSearch'];	//เก็บข้อความที่จะค้นหาเข้าไปในตัวแปร
//ใช้ฟังก์ชัน mysql_real_escape_string() เพื่อตัดอักษรที่จะทำให้การค้นกับฐานข้อมูลมีปัญหาออกไป

$escapedSearchTerm = mysql_real_escape_string($searchTerm);
// ในกรณีมีหลายหน้า กำหนดว่าจะให้แสดงตั้งแต่หน้าที่เท่าใด 
	$offset = ($page - 1) * $productsPerPage;
	
$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, cat_id, pd_description
		FROM tbl_product
		WHERE pd_name LIKE '%$escapedSearchTerm%' OR
		pd_description LIKE '%$escapedSearchTerm%'";
$pageSql =  $sql . " LIMIT $offset, $productsPerPage";	
		
//คิวรี่รายการสินค้าออกมา	
$result = dbQuery($pageSql);
$pagingLink = getPagingLinkForHomeAjax($sql, $productsPerPage);
$numProduct = dbNumRows($result);

//คำนวณหาความกว้างของตารางที่ใช้แสดงรูปภาพ

$columnWidth = (int)(100 / $productsPerRow);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="20" id="productGridTable">
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
			echo '<tr class="row-'.$i.'">';
		}
		//$cartURL = 'cart.php?action=add&p='.$pd_id; //ทำลิงค์สำหรับใส่ตะกร้าสินค้า
		// format how we display the price
		$pd_price = displayAmount($pd_price);
		//แสดงข้อมูลเกี่ยวกับตัวสินค้า
		echo "<td width=\"$columnWidth%\" align=\"center\" style=\"padding-top:10px;\">
		<a href=\"#product-container\" id=\"product-$pd_id\" class=\"show-detail-product\"><img src=\"$pd_thumbnail\" border=\"0\" class=\"img-thumbnail\">
		<br>$pd_name</a><br>";


		if ($pd_qty > 0) {		//ดูว่ามีสินค้าในสต็อกหรือไม่ 
?>
			<span>ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" name="btnAddToCart" class="btn btn-primary btn-xs add-to-cart" id="cart-<?php echo $pd_id; ?>">
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
	<tr><td width="100%" align="center" valign="center"><div class="alert alert-info">ไม่พบสินค้าที่ค้นหา</div></td></tr>
<?php	
}	
?>
</table>
<span hidden id="searchstr-<?php echo $searchTerm; ?>" class="hidden-search-product"></span>