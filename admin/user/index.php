<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
//จำหน้าเว็บเพจล่าสุด จะได้กลับไปที่เดิมได้
checkAdminUser();	//ตรวจสอบว่าได้ล็อกอินในฐานะ admin หรือไม่

//ตรวจสอบว่าเงื่อนไขที่ส่งมาพร้อมกับการคลิกปุ่มสำหรับแสดงรายชื่อ User, เพิ่ม User,แก้ไข User  
//หรือเปลี่ยนรหัส โดยจะมีการส่งค่า $_GET['view'] มาด้วย ซึ่งต้องดูว่าผู้ใช้ต้องการทำอะไร
$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

switch ($view) {
	case 'list' :			//กรณีเรียกดู User ทั้งหมด
		$content 	= 'list.php';		
		$pageTitle 	= 'Shop Admin Control Panel - View Users';
		break;

	case 'add' :			//กรณีเพิ่ม User ใหม่
		$content 	= 'add.php';		
		$pageTitle 	= 'Shop Admin Control Panel - Add Users';
		break;

	case 'modify' :		//กรณีแก้ไข User
		$content 	= 'modify.php';		
		$pageTitle 	= 'Shop Admin Control Panel - Modify Users';
		break;

	default :				//ค่าเริ่มต้น คือดูรายชื่อ User
		$content 	= 'list.php';		
		$pageTitle 	= 'Shop Admin Control Panel - View Users';
}

$script    = array('user.js');	//กำหนดไฟล์ JavaScript เข้าไปยังตัวแปร $script ที่เป็น array

require_once '../include/template.php';	//เรียกใช้ไฟล์ Template
?>
