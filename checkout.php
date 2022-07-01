<?php
require_once 'library/config.php';
require_once 'library/cart-functions.php';
require_once 'library/checkout-functions.php';
require_once 'library/customer-functions.php';
require_once 'library/category-functions.php';

$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;
if (isCartEmpty()) {
	// หากตะกร้าสินค้าว่าง ก็ให้กับไปยัง cart.php
	header('Location: cart.php');
} else if (isset($_GET['step']) && (int)$_GET['step'] > 0 && (int)$_GET['step'] <= 3) {
	$step = (int)$_GET['step']; //ตรวจสอบว่าเป็น step อะไร 1,2 หรือ 3

	$includeFile = '';
	if ($step == 1) {	//หากเป็นขั้นตอนแรก
		$includeFile = 'shippingAndPaymentInfo.php';
		$pageTitle   = 'Checkout - Step 1 of 2';
	} else if ($step == 2) {	//เมื่อเป็นขั้นตอนที่ 2
		$includeFile = 'checkoutConfirmation.php';
		$pageTitle   = 'Checkout - Step 2 of 2';
	} else if ($step == 3) {	//หรือเป็นขั้นตอนที่ 3
		$orderId     = saveOrder();
		$orderAmount = getOrderAmount($orderId);
		
		$_SESSION['orderId'] = $orderId;	//เก็บรหัสการสั่งสินค้าเข้า session
		
		//ตรวจสอบว่าเป็นการชำระค่าสินค้าในแบบใด 
		// ถ้าเป็นการโอนเงิน (cod) ก็ให้ไปยังหน้า success.php
		// แต่ถ้าชำระด้วย PayPal ก็ให้ส่งไปยัง paypal/payment.php แทน 
		if ($_POST['hidPaymentMethod'] == 'cod') {
			header('Location: success.php');
			exit;
		} else {
			$includeFile = 'paypal/payment.php';	
		}
	}
} else {
	//ถ้าไม่มีการส่งค่า step ก็ให้กลับไปยังหน้า index.php 
	header('Location: index.php');
}
require_once 'include/header.php';

?>
<script language="JavaScript" type="text/javascript" src="library/checkout.js"></script>
<div class="row">
  <div class="col-md-12"> <?php require_once 'include/nevMenu.php'; ?></div>
</div>

<div class="row">
  <div class="col-md-12"> <?php require_once 'include/top.php'; ?></div>
</div>

<div class="row">
  <div class="col-md-2"><?php require_once 'include/leftNav.php'; ?></div>
  
  <div class="col-md-7">
      	<div id="category-container"> 
  			<?php //require_once 'include/categoryList.php'; ?>
  		</div>
  		<div id="product-container">
  		<!--******แสดงข้อมูลว่าเป็นขั้นตอนใด*******-->
  		<?php
  			require_once "include/$includeFile";
  		?>
  			
		</div>	
		<div id="lastest-link-content">
		</div>
	</div>

  	<div class="col-md-3">
  		<div id="cart-content-mini"></div>
  		<div><?php require_once 'include/widgets/otherWidget.php';?></div>
  		<div><?php require_once 'include/widgets/widget2.php';?></div>
  	</div>
</div>

<script>

$(function(){

});

</script>

<?php require_once 'include/footer.php'; ?>
