<?php

include_once '../library/config.php';
include_once '../library/database.php';
include_once '../library/common.php';
include_once '../library/category-functions.php';



$catId = 0;
if(isset($_GET['c'])){
	$catId= $_GET['c'];
}

$searchTerm = $_GET['strSearch'];
$escapedSearchTerm = mysql_real_escape_string($searchTerm);
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}

$productsPerRow = 4;
$productsPerPage = 8;

$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, cat_id, pd_description
		FROM tbl_product
		WHERE pd_name LIKE '%$escapedSearchTerm%' OR
		pd_description LIKE '%$escapedSearchTerm%'";

$pagingLink = getPagingLinkForHomeAjax($sql, $productsPerPage, $catId, '-search-list');
echo $pagingLink;
?>