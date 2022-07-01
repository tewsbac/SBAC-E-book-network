<?php
require_once 'config.php';

/*********************************************************
*                 SHOPPING CART FUNCTIONS 
*********************************************************/
 
function addToCart()
{
	// make sure the product id exist
	if (isset($_GET['p']) && (int)$_GET['p'] > 0) {
		$productId = (int)$_GET['p'];
	} else {
		header('Location: index.php');
	}
	
	// does the product exist ?
	$sql = "SELECT pd_id, pd_qty
	        FROM tbl_product
			WHERE pd_id = $productId";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) != 1) {
		// the product doesn't exist
		header('Location: cart.php');
	} else {
		// how many of this product we
		// have in stock
		$row = dbFetchAssoc($result);
		$currentStock = $row['pd_qty'];

		if ($currentStock == 0) {
			// we no longer have this product in stock
			// show the error message
			setError('The product you requested is no longer in stock');
			header('Location: cart.php');
			exit;
		}

	}		
	
	// current session id
	$sid = session_id();
	
	// check if the product is already
	// in cart table for this session
	$sql = "SELECT pd_id
	        FROM tbl_cart
			WHERE pd_id = $productId AND ct_session_id = '$sid'";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 0) {
		// put the product in cart table
		$sql = "INSERT INTO tbl_cart (pd_id, ct_qty, ct_session_id, ct_date)
				VALUES ($productId, 1, '$sid', NOW())";
		$result = dbQuery($sql);
	} else {
		// update product quantity in cart table
		$sql = "UPDATE tbl_cart 
		        SET ct_qty = ct_qty + 1
				WHERE ct_session_id = '$sid' AND pd_id = $productId";		
				
		$result = dbQuery($sql);		
	}	
	
	// an extra job for us here is to remove abandoned carts.
	// right now the best option is to call this function here
	deleteAbandonedCart();
	
	header('Location: ' . $_SESSION['shop_return_url']);				
}

/*
	Get all item in current session
	from shopping cart table
*/
function getCartContent()
{
	$cartContent = array();		//กำหนดตัวแปรแบบ array()

	$sid = session_id();		//เก็บค่า session id ในปัจจุบัน
	//เลือกข้อมูลจาก 3 ตาราง ได้แก่ tbl_cart, tbl_product และ tbl_category
	$sql = "SELECT ct_id, ct.pd_id, ct_qty, pd_name, pd_price, pd_thumbnail, pd.cat_id, pd.pd_qty
			FROM tbl_cart ct, tbl_product pd, tbl_category cat
			WHERE ct_session_id = '$sid' AND ct.pd_id = pd.pd_id AND cat.cat_id = pd.cat_id";
	
	$result = dbQuery($sql);		//เลือกโดยอ้างอิงจาก session id ในปัจจุบัน
	
	while ($row = dbFetchAssoc($result)) {
		if ($row['pd_thumbnail']) {	//นำเอาภาพ thumbnail ออกมา
			$row['pd_thumbnail'] = WEB_ROOT . 'images/product/' . $row['pd_thumbnail'];
		} else {		//ถ้าไม่มี thumbnail ใช้รูปที่เตรียมไว้แทน
			$row['pd_thumbnail'] = WEB_ROOT . 'images/no-image-small.png';
		}
		$cartContent[] = $row;	//นำข้อมูลจากฐานข้อมูลเก็บเข้า array
	}
	
	return $cartContent;	//รีเทิร์นค่าจาก array() กลับไป
}

/*
	Get all item in current session
	from shopping cart table
*/
function getCartNumItems()
{
	$cartContent = array();

	$sid = session_id();
	$sql = "SELECT ct_id, ct.pd_id, ct_qty, pd_name, pd_price, pd_thumbnail, pd.cat_id, pd.pd_qty
			FROM tbl_cart ct, tbl_product pd, tbl_category cat
			WHERE ct_session_id = '$sid' AND ct.pd_id = pd.pd_id AND cat.cat_id = pd.cat_id";
	
	$result = dbQuery($sql);
	$numRow = dbNumRows($result);
	return $numRow;
}

/*
	Remove an item from the cart
*/
function deleteFromCart($cartId = 0)
{
	//ตรวจสอบ $_GET['cid']แล้วกำหนดให้กับตัวแปร $cartId
	if (!$cartId && isset($_GET['cid']) && (int)$_GET['cid'] > 0) {
		$cartId = (int)$_GET['cid'];
	}

	//ลบรายการออกจากตาราง tbl_cart ที่มีรหัส ct_id เท่ากับ $cartId
	if ($cartId) {	
		$sql  = "DELETE FROM tbl_cart
				 WHERE ct_id = $cartId";

		$result = dbQuery($sql);
	}
	
	//redirect กลับไปยังหน้า cart.php
	header('Location: cart.php');	
}

/*
	Update item quantity in shopping cart
*/
function updateCart()
{
	$cartId     = $_POST['hidCartId'];		//เก็บรหัสตะกร้า ซึ่งเป็นแบบ array()
	$productId  = $_POST['hidProductId'];	//เก็บรหัสสินค้า ซึ่งเป็นแบบ array()
	$itemQty    = $_POST['txtQty'];			//เก็บจำนวนสินค้า ซึ่งเป็นแบบ array()
	$numItem    = count($itemQty);			//นับจำนวนรายการที่ส่งมา
	$numDeleted = 0;						//นับจำนวนรายการที่ลบ
	$notice     = '';
	
	//วนลูปตามจำนวนรายการที่ส่งมา
	for ($i = 0; $i < $numItem; $i++) {
		$newQty = (int)$itemQty[$i];
		if ($newQty < 1) {
			// ถ้าจำนวนสินค้าที่ลูกค้าเปลี่ยนน้อยกว่า 0 ให้ลบสินค้าออกจากตะกร้า
			deleteFromCart($cartId[$i]);	//เรียกฟังก์ชันสำหรับลบสินค้าจากตะกร้า
			$numDeleted += 1;
		} else {
			// ตรวจสอบสต๊อคสินค้าก่อนอัพเดท
			$sql = "SELECT pd_name, pd_qty
			        FROM tbl_product 
					WHERE pd_id = {$productId[$i]}";
			$result = dbQuery($sql);
			$row    = dbFetchAssoc($result);
			
			//ตรวจสอบว่าจำนวนสินค้าที่ลูกค้าแก้ไข มากกว่าจำนวนสต๊อคหรือไม่
			if ($newQty > $row['pd_qty']) {
				//ถ้าสินค้าที่ลูกค้าสั่งมากกว่าจำนวนสต็อคก็ให้กำหนดแค่จำนวนสต๊อค
				$newQty = $row['pd_qty'];

				//แสดงข้อความบอกลูกค้าว่า สินค้าเกินกว่าที่มีในสต๊อค
				if ($row['pd_qty'] > 0) {
					setError('ขออภัย จำนวนสินค้าที่คุณต้องการมากกว่าจำนวนสินค้าที่มีอยู่ในสต๊อค<br>จำนวนสินค้าจะถูกแก้ไขอัตโนมัติให้เท่ากับจำนวนสต๊อคที่มีอยู่');
				} else {
					//กรณีไม่มีสินค้าอยู่ในสต็อคเลย
					setError('ขออภัย, สินค้าที่คุณต้องการ (' . $row['pd_name'] . ') ในตอนนี้ไม่มีอยู่ในสต๊อคสินค้าของเรา');
					//ลบสินค้าออกจากตะกร้าสินค้า
					deleteFromCart($cartId[$i]);	
					$numDeleted += 1;					
				}
			} 
							
			//อัพเดทจำนวนสินค้าในตาราง tbl_cart โดยอ้างอิงจากรหัส $cartId
			//เนื่องจากการส่งค่า $cartId เป็น array() จึงต้องระบุ index ด้วยตัวแปร $i
			$sql = "UPDATE tbl_cart
					SET ct_qty = $newQty
					WHERE ct_id = {$cartId[$i]}";
				
			dbQuery($sql);
		}
	}
	
	if ($numDeleted == $numItem) {
		//ถ้าจำนวนรายการสินค้าที่ลบ เท่ากับจำนวนรายการของตะกร้าสินค้าในตอนแรก
		//ให้กลับไปยังหน้าเว็บเพจเดิมก่อนที่จะเข้ามายังตะกร้าสินค้า
		header("Location: $returnUrl" . $_SESSION['shop_return_url']);
	} else {
		header('Location: cart.php');	
	}
	
	exit;
}

function isCartEmpty()
{
	$isEmpty = false;
	
	$sid = session_id();
	$sql = "SELECT ct_id
			FROM tbl_cart ct
			WHERE ct_session_id = '$sid'";
	
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 0) {
		$isEmpty = true;
	}	
	
	return $isEmpty;
}

/*
	Delete all cart entries older than one day
*/
function deleteAbandonedCart()
{
	$yesterday = date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
	$sql = "DELETE FROM tbl_cart
	        WHERE ct_date < '$yesterday'";
	dbQuery($sql);		
}

function checkStockBeforeCheckout()
{
	$cartContent = getCartContent();
	$numItem = count($cartContent);
	if ($numItem > 0 ) {

		for ($i = 0; $i < $numItem; $i++) {
			extract($cartContent[$i]);

			// check current stock
			$sql = "SELECT pd_qty, pd_name
			        FROM tbl_product 
					WHERE pd_id = $pd_id";
			$result = dbQuery($sql);
			$row    = dbFetchAssoc($result);
			
			if ($ct_qty > $row['pd_qty']) {
				// we only have this much in stock
				$ct_qty = $row['pd_qty'];

				// if the customer put more than
				// we have in stock, give a notice
				if ($row['pd_qty'] > 0) {
					setError('The quantity you have requested is more than we currently have in stock. The number available is indicated in the &quot;Quantity&quot; box. ');
				} else {
					// the product is no longer in stock
					setError('Sorry, but the product you want (' . $row['pd_name'] . ') is no longer in stock');

					// remove this item from shopping cart
					deleteFromCart($ct_id);						
				}
			} 
							
			// update product quantity
			$sql = "UPDATE tbl_cart
					SET ct_qty = $ct_qty
					WHERE ct_id = $ct_id";
				
			dbQuery($sql);
		}
	}
}
?>