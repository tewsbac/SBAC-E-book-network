<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
checkAdminUser();	//ตรวจสอบว่า Admin ได้ Login แล้วหรือไม่
//ดูว่าได้ส่งค่า $_GET['view'] อะไรมายัง index.php

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :				//กรณีดูรายการ
		$content 	= 'list.php';		
		$pageTitle 	= 'Shop Admin Control Panel - View Category';
		break;

	case 'add' :				//กรณีเพิ่มประเภทสินค้า
		$content 	= 'add.php';		
		$pageTitle 	= 'Shop Admin Control Panel - Add Category';
		break;

	case 'modify' :				//กรณีแก้ไขประเภทสินค้า
		$content 	= 'modify.php';		
		$pageTitle 	= 'Shop Admin Control Panel - Modify Category';
		break;

	default :		//หากไม่ต้องเงื่อนไขข้างบน ให้แสดงรายการประเภทสินค้า
		$content 	= 'list.php';		
		$pageTitle 	= 'Shop Admin Control Panel - View Category';
}


$script    = array('category.js');		//กำหนดไฟล์ javaScript ที่จะใช้งาน

require_once '../include/template.php';	//เลือกใช้งาน template.php
?>