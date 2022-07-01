<?php
require_once 'config.php';

/*********************************************************
*                 CHECKOUT FUNCTIONS 
*********************************************************/
function saveOrder()
{
	$orderId       = 0;		//ในตอนแรกรหัสใบสั่งสินค้าจะเป็น 0 
	$shopConfig = getShopConfig();
	$shippingCost  = $shopConfig['shippingCost'];

	//กำหนด array() ขึ้นมา เพื่อเก็บข้อมูลของการผู้ชื้อและผู้รับสินค้า
	$requiredField = array('hidShippingFirstName', 'hidShippingLastName', 'hidShippingEmail', 'hidShippingAddress1', 'hidShippingCity', 'hidShippingPostalCode',
						   'hidPaymentFirstName', 'hidPaymentLastName', 'hidPaymentEmail', 'hidPaymentAddress1', 'hidPaymentCity', 'hidPaymentPostalCode');
	//ตรวจสอบข้อมูลของผู้ซื้อ และผู้รับสินค้า			
	if (checkRequiredPost($requiredField)) {
	    extract($_POST);
		
		// กำหนดให้อักษรตัวแรกเป็นตัวใหญ่ 
		$hidShippingFirstName = ucwords($hidShippingFirstName);
		$hidShippingLastName  = ucwords($hidShippingLastName);
		$hidPaymentFirstName  = ucwords($hidPaymentFirstName);
		$hidPaymentLastName   = ucwords($hidPaymentLastName);
		$hidShippingCity      = ucwords($hidShippingCity);
		$hidPaymentCity       = ucwords($hidPaymentCity);
				
		//ดึงข้อมูลจากตะกร้าสินค้ามาเก็บที่ตัวแปร $cartContent ซึ่งผลลัพธ์จะเป็น array()	
		$cartContent = getCartContent();
		$numItem     = count($cartContent);
		
		//เซฟรายละเอียดการสั่งซื้อ 
		$sql = "INSERT INTO tbl_order(od_date, od_last_update, od_shipping_first_name, od_shipping_last_name, od_shipping_email, od_shipping_address1, 
		                              od_shipping_address2, od_shipping_phone, od_shipping_state, od_shipping_city, od_shipping_postal_code, od_shipping_cost,
                                      od_payment_first_name, od_payment_last_name, od_payment_email, od_payment_address1, od_payment_address2, 
									  od_payment_phone, od_payment_state, od_payment_city, od_payment_postal_code)
                VALUES (NOW(), NOW(), '$hidShippingFirstName', '$hidShippingLastName', '$hidShippingEmail', '$hidShippingAddress1', 
				        '$hidShippingAddress2', '$hidShippingPhone', '$hidShippingState', '$hidShippingCity', '$hidShippingPostalCode', '$shippingCost',
						'$hidPaymentFirstName', '$hidPaymentLastName', '$hidPaymentEmail', '$hidPaymentAddress1', 
						'$hidPaymentAddress2', '$hidPaymentPhone', '$hidPaymentState', '$hidPaymentCity', '$hidPaymentPostalCode')";
		$result = dbQuery($sql);
		
		//ดึงรหัสใบสั่งซื้อออกมา (ในที่นี้คือ ฟิลด์ od_id)
		$orderId = dbInsertId();
		
		if ($orderId) {
			//จัดเก็บรายละเอียดการซื้อว่ามีสินค้าชิ้นใดบ้าง และจำนวนกี่ชิ้นลงในตาราง tbl_order_item
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "INSERT INTO tbl_order_item(od_id, pd_id, od_qty)
						VALUES ($orderId, {$cartContent[$i]['pd_id']}, {$cartContent[$i]['ct_qty']})";
				$result = dbQuery($sql);					
			}
		
			
			//อัพเดทสต๊อคสินค้าในตาราง tbl_product
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "UPDATE tbl_product 
				        SET pd_qty = pd_qty - {$cartContent[$i]['ct_qty']}
						WHERE pd_id = {$cartContent[$i]['pd_id']}";
				$result = dbQuery($sql);					
			}
			
			
			// ลบรายการสินค้าออกจากตะกร้าสินค้า ซึ่งก็คือลบข้อมูลออกจากตาราง tbl_cart
			for ($i = 0; $i < $numItem; $i++) {
				$sql = "DELETE FROM tbl_cart
				        WHERE ct_id = {$cartContent[$i]['ct_id']}";
				$result = dbQuery($sql);					
			}							
		}					
	}
	
	//รีเทิร์นค่ารหัสใบสั่งซื้อกลับไปกับฟังก์ชัน
	return $orderId;
}

/*
	Get order total amount ( total purchase + shipping cost )
*/
function getOrderAmount($orderId)
{
	$orderAmount = 0;
	
	$sql = "SELECT SUM(pd_price * od_qty)
	        FROM tbl_order_item oi, tbl_product p 
		    WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
			
			UNION
			
			SELECT od_shipping_cost 
			FROM tbl_order
			WHERE od_id = $orderId";
	$result = dbQuery($sql);

	if (dbNumRows($result) == 2) {
		$row = dbFetchRow($result);
		$totalPurchase = $row[0];
		
		$row = dbFetchRow($result);
		$shippingCost = $row[0];
		
		$orderAmount = $totalPurchase + $shippingCost;
	}	
	
	return $orderAmount;	
}

function getCustomerEmail($orderId)
{
	$customerEmail = '';
	$sql = "SELECT od_shipping_email
	        FROM tbl_order
		    WHERE od_id = $orderId";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 1) {
		while($row = dbFetchAssoc($result)){
			extract($row);
			$customerEmail = $od_shipping_email;
		}
	}	
	
	return $customerEmail;	
}

function getOrderTableForMail($orderId)
{

	$emailMessage = '';
	// get ordered items
	$sql = "SELECT pd_name, pd_price, od_qty
	    FROM tbl_order_item oi, tbl_product p 
		WHERE oi.pd_id = p.pd_id and oi.od_id = $orderId
		ORDER BY od_id ASC";

	$result = dbQuery($sql);
	$orderedItem = array();
	while ($row = dbFetchAssoc($result)) {
		$orderedItem[] = $row;
	}


	// get order information
	$sql = "SELECT od_date, od_last_update, od_status, od_shipping_first_name, od_shipping_last_name, od_shipping_address1, 
               od_shipping_address2, od_shipping_phone, od_shipping_state, od_shipping_city, od_shipping_postal_code, od_shipping_cost, 
			   od_payment_first_name, od_payment_last_name, od_payment_address1, od_payment_address2, od_payment_phone,
			   od_payment_state, od_payment_city , od_payment_postal_code,
			   od_memo
	    FROM tbl_order 
		WHERE od_id = $orderId";

	$result = dbQuery($sql);
	extract(dbFetchAssoc($result));
	
	$emailMessage .= '<p>&nbsp;</p>
    <table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
        <tr> 
            <td colspan="2" align="center" id="infoTableHeader">รายละเอียดของการสั่งสินค้า</td>
        </tr>
        <tr> 
            <td width="150" class="label">หมายเลขใบสั่งสินค้า</td>
            <td class="content">'.$orderId.'</td>
        </tr>
        <tr> 
            <td width="150" class="label">วันสั่งสินค้า</td>
            <td class="content">'.$od_date.'</td>
        </tr>
    </table>
</form>';


	$emailMessage .= '
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr align="center" class="label"> 
        <td>รายการ</td>
        <td>ราคาต่อหน่วย</td>
        <td>รวมย่อย</td>
    </tr>';

$numItem  = count($orderedItem);
$subTotal = 0;
for ($i = 0; $i < $numItem; $i++) {
	extract($orderedItem[$i]);
	$subTotal += $pd_price * $od_qty;
	$emailMessage .=
    '<tr class="content"> 
        <td>'.$od_qty.' X '.$pd_name.'</td>
        <td align="right">'.displayAmount($pd_price).'</td>
        <td align="right">'.displayAmount($od_qty * $pd_price).'</td>
    </tr>';
}
	$emailMessage .=
    '<tr class="content"> 
        <td colspan="2" align="right">รวม</td>
        <td align="right">'.displayAmount($subTotal).'</td>
    </tr>
    <tr class="content"> 
        <td colspan="2" align="right">ค่าจัดส่ง</td>
        <td align="right">'.displayAmount($od_shipping_cost).'</td>
    </tr>
    <tr class="content"> 
        <td colspan="2" align="right">รวมสุทธิ</td>
        <td align="right">'.displayAmount($od_shipping_cost + $subTotal).'</td>
    </tr>
</table>
<!--
<hr>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Shipping Information</td>
    </tr>
    <tr> 
        <td width="150" class="label">First Name</td>
        <td class="content">'.$od_shipping_first_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Last Name</td>
        <td class="content">'.$od_shipping_last_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address1</td>
        <td class="content">'.$od_shipping_address1.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address2</td>
        <td class="content">'.$od_shipping_address2.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Phone Number</td>
        <td class="content">'.$od_shipping_phone.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Province / State</td>
        <td class="content">'.$od_shipping_state.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">City</td>
        <td class="content">'.$od_shipping_city.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Postal Code</td>
        <td class="content">'.$od_shipping_postal_code.'</td>
    </tr>
</table>
<table width="550" border="0"  align="center" cellpadding="5" cellspacing="1" class="detailTable">
    <tr id="infoTableHeader"> 
        <td colspan="2">Payment Information</td>
    </tr>
    <tr> 
        <td width="150" class="label">First Name</td>
        <td class="content">'.$od_payment_first_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Last Name</td>
        <td class="content">'.$od_payment_last_name.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address1</td>
        <td class="content">'.$od_payment_address1.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Address2</td>
        <td class="content">'.$od_payment_address2.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Phone Number</td>
        <td class="content">'.$od_payment_phone.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Province / State</td>
        <td class="content">'.$od_payment_state.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">City</td>
        <td class="content">'.$od_payment_city.'</td>
    </tr>
    <tr> 
        <td width="150" class="label">Postal Code</td>
        <td class="content">'.$od_payment_postal_code.'</td>
    </tr>
</table>
<p>&nbsp;</p>
-->';
	return $emailMessage;

}

?>