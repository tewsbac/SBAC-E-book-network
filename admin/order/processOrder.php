<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

checkAdminUser();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'modify' :
        modifyOrder();
        break;

    default :
        // if action is not defined or unknown
        // move to main category page
        header('Location: index.php');
}



function modifyOrder()
{
//กำหนดค่าให้ตัวแปรซึ่งคือ รหัสใบสั่งซื้อ และสถานะของใบสั่งซื้อ
	if (!isset($_GET['oid']) || (int)$_GET['oid'] <= 0
	    || !isset($_GET['status']) || $_GET['status'] == '') {
		header('Location: index.php');
	}
	
	//เก็บรหัสใบสั่งสินค้า และสถานะที่ได้รับใส่ในตัวแปร
	$orderId = (int)$_GET['oid'];
	$status  = $_GET['status'];
    
    $sql = "UPDATE tbl_order
            SET od_status = '$status', od_last_update = NOW()
            WHERE od_id = $orderId";
	//อัพเดทสถานะในฐานข้อมูล
    $result = dbQuery($sql);
	
	//เปลี่ยนไปยังหน้า index.php พร้อมแสดงสถานะใหม่
	header("Location: index.php?view=list&status=$status");    
}
?>