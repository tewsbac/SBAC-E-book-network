<?php
if (!defined('WEB_ROOT')) {
	exit;
}
//กำหนด title ให้กับเว็บเพจ โดยเบื้องต้นจะนำเอาชื่อเว็บมาเป็น title
$shopName = $shopConfig['name'];
$pageTitle = $shopName;
//แต่ถ้ามีการส่งรหัสสินค้า หรือรหัสประเภทสินค้ามา ชื่อ title ก็จะถูกแสดงด้วยชื่อสินค้า					//หรือประเภทสินค้าแทน
if (isset($_GET['p']) && (int)$_GET['p'] > 0) {
	$pdId = (int)$_GET['p'];
	$sql = "SELECT pd_name
			FROM tbl_product
			WHERE pd_id = $pdId";
	
	$result    = dbQuery($sql);
	$row       = dbFetchAssoc($result);
	$pageTitle = $row['pd_name'];
} else if (isset($_GET['c']) && (int)$_GET['c'] > 0) {
	$catId = (int)$_GET['c'];
	$sql = "SELECT cat_name
	        FROM tbl_category
			WHERE cat_id = $catId";

    $result    = dbQuery($sql);
	$row       = dbFetchAssoc($result);
	$pageTitle = $row['cat_name'];
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $pageTitle; ?></title>
	<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<link href="include/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="include/bootstrap/css/bootstrap.theme.css" rel="stylesheet" type="text/css">
	<link href="include/bootstrap/css/shop.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" type="text/javascript" src="include/bootstrap/js/jquery-1.11.0.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="library/common.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/bootstrap/js/bootstrap.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="library/ajax-page.js"></script>
</head>
<body>
<div class="container-fluid">
<! -- กำหนด Container หลัก โดยอ้างอิงกับคลาสของ Bootstrap -- >