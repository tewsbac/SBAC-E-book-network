<?php
require_once 'library/config.php';
require_once 'library/category-functions.php';
require_once 'library/product-functions.php';
require_once 'library/cart-functions.php';
require_once 'library/customer-functions.php';


$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

$stringMail = '';
$name = '';
$last = '';
$phone = '';
$email = '';
if(isset($_SESSION['plaincart_customer_id'])) {
	$userProfile = getCustomerProfile(); 
	extract($userProfile);
	$name = $user_first_name;
	$last = $user_last_name;
	$phone = $user_phone;
	$email = $user_email;
} 
if(isset($_POST['txtUserFirstName'])){
	if(md5($_POST['captcha']) == $_SESSION['captchaKey']){
		$title = (isset($_POST['txtTitle']))?$_POST['txtTitle']:'';
		$name = (isset($_POST['txtUserFirstName']))?$_POST['txtUserFirstName']:'';
		$last = (isset($_POST['txtUserLastName']))?$_POST['txtUserLastName']:'';
		$phone = (isset($_POST['txtUserPhone']))?$_POST['txtUserPhone']:'';
		$email = (isset($_POST['txtUserEmail']))?$_POST['txtUserEmail']:'';
		$data = (isset($_POST['txtData']))?$_POST['txtData']:'';

		$shopEmail  = $shopConfig['email'];

		$subject = '<h3>ข้อคิดเห็น เรื่อง - '.$title.'</h3>';
		$stringMail .= '<p>ชื่อลูกค้า : '.$name.' '.$last.'</p>';
		$stringMail .= '<p>เบอร์โทรศัพท์ : '.$phone.'</p>';
		$stringMail .= '<p>อีเมล : '.$email.'</p>';
		$stringMail .= '<h4>ข้อคิดเห็น-เสนอแนะ</h4>';
		$stringMail .= '<p>เนื้อหา : '.$data.'</p>';
		$stringMail .= '<p>อีเมลร้านค้า : '.$shopEmail.'</p>';
	
		$headers = 'From: '.$email."\r\n".
				'Reply-To: '.$email."\r\n" .
				'X-Mailer: PHP/' . phpversion();
		@mail($shopEmail, $subject, $stringMail, $headers); 
		setSuccess('ขอขอบคุณสำหรับคำแนะนำติชม');
 	}else {
 		setError('คุณกรอกรหัส Captcha ไม่ถูกต้อง');
 	}
}
require_once 'include/header.php';


?>

<script language="JavaScript" type="text/javascript" src="library/contact-form.js"></script>

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
  <div class="panel-heading">ติดต่อเรา</div>
  <div class="panel-body">
  
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
//จบส่วนแสดง error และ success 
?>  

		<div class="alert alert-warning">หากท่านมีข้อสงสัย หรือมีข้อเสนอแนะเกี่ยวกับทางเว็บไซต์ สามารถเสนอความคิดความเห็นลงในแบบฟอร์มต่อไปนี้ได้
			</div>
		<table align="center" cellpadding="0" cellspacing="1" class="table table-bordered table-striped table-hover">

			<form method="post" enctype="multipart/form-data" name="frmContact" id="frmContact" onSubmit="return checkCustomerSubmitInfo();">
  				<tr> 
   					<td width="200" class="">เรื่อง  <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtTitle" type="text" class="box" id="txtTitle" size="32" maxlength="32" value=""></td>
  				</tr>
  				<tr> 
   					<td width="200" class="">ชื่อ <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtUserFirstName" type="text" class="box" id="txtUserFirstName" size="32" maxlength="32" value="<?php echo $name.' '.$last; ?>"></td>
  				</tr>
   				<tr> 
   					<td width="200" class="">โทรศัพท์</td>
   					<td class=""> <input name="txtUserPhone" type="text" class="box" id="txtUserPhone" size="32" maxlength="32" value="<?php echo $phone; ?>"></td>
  				</tr>
  				<tr> 
   					<td width="200" class="">อีเมล <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <input name="txtUserEmail" type="text" class="box" id="txtUserEmail" size="32" maxlength="32" value="<?php echo $email; ?>"></td>
  				</tr>
  				<tr> 
   					<td width="200" class="">ข้อคิดเห็น/ข้อเสนอแนะ <span class="label label-warning">ต้องการ</span></td>
   					<td class=""> <textarea cols="50" rows="10" name="txtData" type="text" class="input-xlarge" id="txtData" value=""></textarea></td>
  				</tr>
  				<tr>
  					<td width="200" class="">กรอกรหัสที่เห็นในภาพ <span class="label label-warning">ต้องการ</span></td>
  					<td>
     				<div>
        				<img src="<?php echo 'include/captcha/captcha.php'; ?>" border="0" />
        				<input type="text" name="captcha" id="captcha" class="box">
     				</div>
   					</td>
  				</tr>
  			</table>
 		<p align="center"> 
  		<input name="btnContact" type="submit" id="btnContact" value="ส่ง"  class="btn btn-primary">
  		&nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='<?php echo WEB_ROOT; ?>';" class="btn btn-primary">  
 		</p>
 		</form>
	</div>
</div>

  			<!--************************************************************-->
			<!--****************** สิ้นสุด content ตรงนี้ ***********************-->
  			<!--************************************************************-->
		</div>	
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
//echo $stringMail; ใช้ทดสอบว่า Output ส่งค่าออกไปถูกต้องหรือไม่
?>
<?php require_once 'include/footer.php'; ?>
