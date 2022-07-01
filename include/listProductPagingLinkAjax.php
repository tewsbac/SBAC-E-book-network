<?php

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
include_once '../library/category-functions.php';

$catId = 0;
if(isset($_GET['c'])){
	$catId= $_GET['c'];		//กำหนดรหัสประเภทสินค้า
}

	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];	//กำหนดหมายเลขหน้า
	} else {
		$page = 1;			//ถ้าไม่ได้กำหนดหมายเลขหน้า ให้เลขหน้าเป็น 1
	}

$productsPerRow = 4;		//จำนวนสินค้าต่อแถว
$productsPerPage = 8;		//จำนวนสินค้าต่อหนึ่งหน้า
//กำหนด SQL
$children = array_merge(array($catId), getChildCategories(NULL, $catId));
$children = ' (' . implode(', ', $children) . ')';


$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, c.cat_id
		FROM tbl_product pd, tbl_category c
		WHERE c.cat_id = $catId AND pd.cat_id IN $children 
		ORDER BY pd_name";
//เรียกฟังก์ชัน getPagingLinkForHomeAjax() เพื่อสร้างลิงก์ 
$pagingLink = getPagingLinkForHomeAjax($sql, $productsPerPage, $catId, '-product-list');
echo $pagingLink;
?>