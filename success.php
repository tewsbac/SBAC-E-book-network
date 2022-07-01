<?php
require_once 'library/config.php';
require_once 'library/checkout-functions.php';
require_once 'library/category-functions.php';

// if no order id defined in the session
// redirect to main page
if (!isset($_SESSION['orderId'])) {
	header('Location: ' . WEB_ROOT);
	exit;
}

$pageTitle   = 'Checkout Completed Successfully';
require_once 'include/header.php';
$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

require_once 'include/nevMenu.php';
//คลาสสำหรับการรับส่งเมล์
require_once 'library/PHPMailer/class.phpmailer.php';
require_once 'library/PHPMailer/class.smtp.php';
require_once 'library/PHPMailer/class.pop3.php';
$orderId = $_SESSION['orderId'];
$emailMessage = getOrderTableForMail($orderId);			//รายการสินค้าที่ลูกค้าซื้อ 
	
if ($shopConfig['sendOrderEmail'] == 'y') {		//ถ้ากำหนดให้มีการส่งอีเมล
												//ใน shop config
	$shopName = $shopConfig['name'];			//ชื่อร้านค้า
	$shopEmail   = $shopConfig['email'];		//อีเมลของทางร้าน
	//------------------------ส่งอีเมลไปยังลูกค้า---------------------------//
	$customerSubject = "[สั่งสินค้า] รหัสใบสั่งสินค้า: " . $_SESSION['orderId'] . ' จากร้าน:' . $shopName;
	$customerSubject = "=?UTF-8?B?".base64_encode($customerSubject)."?=";
	$customerEmail   = getCustomerEmail($orderId);	//อีเมลของลูกค้า
	$mail             = new PHPMailer();			//สร้าง Object จากคลาส PHPMailer
	$body             = $emailMessage;			//ข้อความที่จะส่งไปให้ลูกค้า
	$mail->IsSMTP(); 							// กำหนดว่าจะใช้เมลแบบ SMTP
	$mail->Host       = "mx1.hostinger.in.th"; 	// SMTP server

	$mail->SMTPAuth   = true; 					//เปิดการใช้งาน SMTP authentication
	$mail->Host       = "mx1.hostinger.in.th"; 	//กำหนดชื่อโฮส
	$mail->Port       = 2525;                   //กำหนดพอร์ต
	$mail->Username   = "thanathip@tansbacnon.ac.th"; //ชื่อ SMTP User 
	$mail->Password   = "admin1234";        	//รหัสผ่าน SMTP 
	$mail->SetFrom("$shopEmail", "$shopName");	//กำหนดอีเมลและชื่อผู้ส่ง
	$mail->Subject    = $customerSubject;		//กำหนดชื่อ Subject
	$mail->AltBody    = "การดูอีเมล์นี้ กรุณาใช้โปรแกรมที่สามารถดูอีเมล์แบบ HTML"; // optional, comment out and test
	$mail->MsgHTML($body);						//ส่งข้อความในแบบ HTML
	$address = $customerEmail;					//อีเมลของผู้รับ
	$mail->AddAddress($address, "[customer]");	//กำหนดอีเมลและชื่อของเมลผู้รับ
	$mail->Send();								//สั่งให้ส่งอีเมล
		
	//------------------------ส่งอีเมลไปยังร้านค้า-------------------------//
	$subject = "[New Order] " . $_SESSION['orderId'];
	$shopBody = "ลูกค้าสั่งสินค้า สามารถตรวจสอบรายละเอียดได้ที่ \n http://" . $_SERVER['HTTP_HOST'] . WEB_ROOT . 'admin/order/index.php?view=detail&oid=' . $_SESSION['orderId'] ;						//สร้างลิงก์เพื่อให้สามารถคลิกรหัสสินค้าได้
	$mail->Subject    = $subject;				//ชื่อ Subject
	$mail->AltBody    = "การดูอีเมล์นี้ กรุณาใช้โปรแกรมที่สามารถดูอีเมล์แบบ HTML";
	$mail->MsgHTML($shopBody);					//เนื้อหาที่จะส่งไปกับอีเมล
	$address = $shopEmail;						//อีเมลผู้รับ
	$mail->AddAddress($address, "$shopName");	//กำกับอีเมลผู้รับและชื่อของผู้รับ
	$mail->Send();								//ส่งอีเมล
	}
unset($_SESSION['orderId']);					//ยกเลิก session ใบสั่งสินค้า
require_once 'include/header.php';
?>

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
  			<!--************************************************************-->
			<!--******************** content ใส่ที่นี่ **************************-->
  			<!--************************************************************-->
<p>&nbsp;</p>
<table width="80%" border="0" align="center" cellpadding="1" cellspacing="0">
   <tr> 
      <td align="left" valign="top"> 
      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
               <td align="center"> <p>&nbsp;</p>
                  <div class="alert alert-success">
                  ขอขอบพระคุณที่ให้ความกรุณาเลือกซื้อสินค้าจากทางร้าน เราจะจัดส่งสินค้าให้ถึงมือท่านโดยเร็วที่สุด หากต้องการซื้อสินค้าเพิ่มเติมกรุณา <a href="index.php" btn btn-primary>คลิกที่นี่ 
                            </a>
                	</div>
                  <p>&nbsp;</p></td>
            </tr>
         </table>
    </td>
   </tr>
</table>
<br>
<br>
  			<!--************************************************************-->
			<!--******************** สิ้นสุดเนื้อหาตรงนี้ *************************-->
  			<!--************************************************************-->

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
	var n = true;
	$.ajax({
  		url:'include/miniCartAjax.php',
  		data:{link:n},
  		type:'get',
  		success:function(data){
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  		}
  	});
});

</script>

<?php require_once 'include/footer.php'; ?>
