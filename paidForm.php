<?php
require_once 'library/config.php';
require_once 'library/category-functions.php';
require_once 'library/product-functions.php';
require_once 'library/cart-functions.php';


$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;
$customerStringMail = '';

if(isset($_POST['txtUserPrice'])){

	if(md5($_POST['captcha']) == $_SESSION['captchaKey']){

		$name = (isset($_POST['txtUserFirstName']))?$_POST['txtUserFirstName']:'';

		$phone = (isset($_POST['txtUserPhone']))?$_POST['txtUserPhone']:'';
		$email = (isset($_POST['txtUserEmail']))?$_POST['txtUserEmail']:'';
		$bank = (isset($_POST['txtShopBank']))?$_POST['txtShopBank']:'';
		$time = (isset($_POST['txtUserTransfer']))?$_POST['txtUserTransfer']:'';
		$amount = (isset($_POST['txtUserPrice']))?$_POST['txtUserPrice']:'';
	
	
		$shopEmail  = $shopConfig['email'];

		$subject = '<h3>ลูกค้าโอนเงิน</h3>';
		$customerStringMail = '<p>ชื่อลูกค้า : '.$name.'</p>';
		$customerStringMail .= '<p>เบอร์โทรศัพท์ : '.$phone.'</p>';
		$customerStringMail .= '<p>อีเมล : '.$email.'</p>';
		$customerStringMail .= '<p>โอนมาที่ธนาคาร : '.$bank.'</p>';
		$customerStringMail .= '<p>เวลาโอน : '.$time.'</p>';
		$customerStringMail .= '<p>จำนวนเงิน : '.$amount.'</p>';
	
		$headers = 'From: '.$email."\r\n".
				'Reply-To: '.$email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		@mail($shopEmail, $subject, $stringMail, $headers); 

 	}else {
 		setError('คุณกรอกรหัส Captcha ไม่ถูกต้อง');
 	}
}

require_once 'include/header.php';


?>

<script language="JavaScript" type="text/javascript" src="library/paid-form.js"></script>

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

<div class="panel panel-info">
  <div class="panel-heading">แจ้งชำระเงิน</div>
  <div class="panel-body">
  		
  		 <!--ส่วนของการตรวจสอบข้อผิดพลาด-->
  
  <?php
//ตรวจสอบว่ามี error หรือ success หรือไม่โดยดูค่าจาก array ใน $_SESSION ถ้ามีก็ให้เปลี่ยนชื่อ class
if(isset($_SESSION['plaincart_error']) && $_SESSION['plaincart_error']!=null){
?>

		<div>
			<div class="alert alert-danger" style="text-align:center;"><?php displayError(); ?></div>
		</div>

<?php
}
if(isset($_SESSION['plaincart_success']) && $_SESSION['plaincart_success']!=null){
?>

		<div>
			<div class="alert alert-success" style="text-align:center;"><?php displaySuccess(); ?></div>
		</div>

<?php
}
?>  
  			
  		<!--ส่วนของเนื้อหาหลัก-->
		<div class="alert alert-warning">เมื่อลูกค้าได้โอนเงินผ่านทางธนาคารเพื่อชำระค่าสินค้าไปแล้ว รบกวนลูกค้าแจ้งการชำระเงินทางโทรศัพท์หมายเลข 081-999-xxx
		หรือกรอกข้อมูลลงในแบบฟอร์มข้างล่างนี้ ทางร้านจะจัดส่งสินค้าไปยังลูกค้าโดยเร็วที่สุด 
		</div>
		<table align="center" cellpadding="0" cellspacing="1" class="table table-bordered table-striped table-hover">

			<form method="post" enctype="multipart/form-data" name="frmPaid" id="frmPaid" onSubmit="return checkCustomerSubmitInfo();">
  				<tr> 
   					<td width="200" class="">ชื่อ <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtUserFirstName" type="text" class="box" id="txtUserFirstName" size="32" maxlength="32"></td>
  				</tr>
   				<tr> 
   					<td width="150" class="">โทรศัพท์</td>
   					<td class=""> <input name="txtUserPhone" type="tel" class="box" id="txtUserPhone" size="32" maxlength="32" value=""></td>
  				</tr>
  				<tr> 
   					<td width="150" class="">อีเมล <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtUserEmail" type="email" class="box" id="txtUserEmail" size="32" maxlength="32" value=""></td>
  				</tr>
  				<tr>
  					<td width="150" class="">โอนไปยังบัญชีธนาคาร <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtShopBank" type="text" class="box" id="txtShopBank" value="" size="32" maxlength="32"></td>
  					</tr>
  				<tr> 
   					<td width="150" class="">เวลาที่โอน </td>
   					<td class=""> <input name="txtUserTransfer" type="date" class="box" id="txtUserTransfer" size="32" maxlength="32"></td>
  				</tr>
    			<tr> 
   					<td width="150" class="">จำนวนเงิน <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtUserPrice" type="text" class="box" id="txtUserPrice" size="32" maxlength="32" onKeyUp="checkNumber(this);"></td>
  				</tr>
  				<tr>
  					<td width="150" class="">กรอกรหัสที่เห็นในภาพ <span class="label label-warning">ต้องการ</span></td>
  					<td>
     				<div>
        				<img src="<?php echo 'include/captcha/captcha.php'; ?>" border="0" />
        				<input type="text" name="captcha" id="captcha" class="box">
     				</div>
   					</td>
  				</tr>
  		</table>
 		<p align="center"> 
  		<input name="btnAddUser" type="submit" id="btnAddUser" value="ส่ง"  class="btn btn-primary">
  		&nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='<?php echo WEB_ROOT; ?>';" class="btn btn-primary">  
 		</p>
 		</form>
	</div>
</div>

  			<!--************************************************************-->
			<!--****************** สิ้นสุด content ตรงนี้ ***********************-->
  			<!--************************************************************-->

		</div><!--div id="product-container"-->
		<div id="lastest-link-content">
		</div>
	</div><!--div class="col-md-7"-->

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

<?php
//echo $customerStringMail;
?>
<?php require_once 'include/footer.php'; ?>
