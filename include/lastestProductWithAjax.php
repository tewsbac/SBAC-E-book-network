<?php

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
include_once '../library/category-functions.php';

$catId = 0;
if(isset($_GET['c'])){
	$catId= $_GET['c'];
}



$productsPerRow = 4;
$productsPerPage = 8;

	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
	
	// start fetching from this row number
	$offset = ($page - 1) * $productsPerPage;
	

$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, pd_image, c.cat_id
		FROM tbl_product pd, tbl_category c
		WHERE c.cat_id = pd.cat_id
		ORDER BY pd_name DESC";
		
$pageSql =  $sql . " LIMIT $offset, $productsPerPage";



$result     = dbQuery($pageSql);
$pagingLink = getPagingLinkForHomeAjax($sql, $productsPerPage);
$numProduct = dbNumRows($result);
?>


<div class="row">

<?php
if ($numProduct > 0 ) {

	$i = 0;
	while ($row = dbFetchAssoc($result)) {
?>
	
	<div class="col-sm-3 col-xs-6">
<?php
		extract($row);
		if ($pd_image) {
			$pd_image = WEB_ROOT . 'images/product/' . $pd_image;
		} else {
			$pd_image = WEB_ROOT . 'images/no-image-small.png';
		}
		
		$pd_price = displayAmount($pd_price);
		
		echo "<section align=\"center\" style=\"padding:5px;\" class=\"thumbnail\">
			<a href=\"#product-container\" id=\"product-$pd_id\" class=\"show-detail-product\">
				<img src=\"$pd_image\" border=\"0\" class=\"img-rounded\">
				<br>$pd_name</a><br><span hidden id=\"category-$cat_id\" class=\"hidden-cat-product\"></span>";


		if ($pd_qty > 0) {
?>
			<span>ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" name="btnAddToCart" class="btn btn-primary btn-xs add-to-cart" id="cart-<?php echo $pd_id; ?>" style="margin-bottom:10px;">
				<span class="glyphicon glyphicon-shopping-cart"></span>ใส่ตะกร้า
			</button>
<?php
		} else {
?>
			<span style="text-decoration:line-through;color:#818181;">ราคา : <?php echo $pd_price; ?></span><br>
			<button type="button" class="disabled btn btn-danger btn-xs" style="margin-bottom:10px;">สินค้าหมด</button>
<?php
		}
		echo "</section>";
		
		
?>
	</div><!--div class="col-sm-3"-->
	
<?php
		if($i != 0){
			if ($i % $productsPerRow == $productsPerRow - 1) {
				echo '<div class="clearfix hidden-xs"></div>';
			} 
			if($i % 2 == 1){
				echo '<div class="clearfix visible-xs hidden-md hidden-lg"></div>';
			}
		}
		$i += 1;
	}
	

} else {
?>
	<div class="col-md-12">
		<section width="100%" align="center" valign="center"><div class="alert alert-info">ยังไม่มีสินค้าในหมวดหมู่นี้</div></section>
	</div>
<?php	
}	
?>

</div>	
		


