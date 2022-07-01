<?php

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
include_once '../library/category-functions.php';

	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}

$productsPerRow = 4;
$productsPerPage = 8;

$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, c.cat_id
		FROM tbl_product pd, tbl_category c
		WHERE c.cat_id = pd.cat_id 
		ORDER BY pd_name DESC";

$pagingLink = getPagingLinkForHomeAjax($sql, $productsPerPage);
echo $pagingLink;
?>