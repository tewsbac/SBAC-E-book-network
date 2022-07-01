<script language="JavaScript" type="text/javascript" src="../library/register.js"></script>
<script language="JavaScript" type="text/javascript" src="../library/common.js"></script>
<link type="text/css" rel="stylesheet" href="shop.css" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.theme.css" rel="stylesheet" type="text/css">
<link href="shop.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="bootstrap/css/jquery-1.11.0.min.js"></script>
<script language="JavaScript" type="text/javascript" src="bootstrap/css/library/bootstrap.min.js"></script>

<?php
require_once '../library/category-functions.php';
$userProfile = getCustomerProfile(); 
extract($userProfile);

if(isset($_POST['secretEditCode'])){

	//ตรวจสอบ captcha
    if(md5($_POST['captcha']) == $_SESSION['captchaKey']){
    	
    	//อัพเดทโปรไฟล์
    	doCustomerProfileUpdate();

		//อัพเดทข้อมูลที่จะแสดงใหม่หลังจากอัพเดทฐานข้อมูลแล้ว
		$userProfile = getCustomerProfile(); 
		extract($userProfile);
    	
    }else {

		setError('คุณกรอกรหัส Captcha ไม่ถูกต้อง');
    }
    unset($_SESSION['captchaKey']);

}

$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;
require_once 'header.php';
//require_once 'nevMenu.php';

?>
<p>&nbsp;</p>
<?php
//ตรวจสอบว่ามี error หรือไม่โดยดูค่าจาก array ใน $_SESSION['plaincart_error'] ถ้ามีก็ให้เปลี่ยนชื่อ class
if(isset($_SESSION['plaincart_error']) && $_SESSION['plaincart_error']!=null){
	?>
<div class="alert alert-danger" style="width:60%;margin-left:auto;margin-right:auto;"><?php displayError(); ?></div>
	<?php
}
//ตรวจสอบว่ามี error หรือไม่โดยดูค่าจาก array ใน $_SESSION['plaincart_error'] ถ้ามีก็ให้เปลี่ยนชื่อ class
if(isset($_SESSION['plaincart_success']) && $_SESSION['plaincart_success']!=null){
	?>
<div class="alert alert-success" style="width:60%;margin-left:auto;margin-right:auto;"><?php displaySuccess(); ?></div>
	<?php
}
?>

<div class="panel panel-info" style="width:60%;margin-left:auto;margin-right:auto;">
	<div class="panel-heading">แก้ไขข้อมูลส่วนตัว : <?php echo $user_name; ?></div>
  	<div class="panel-body">
  	
<form method="post" enctype="multipart/form-data" name="frmEditUser" id="frmEditUser">
 <table width="70%" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
  <tr> 
   <td width="150" class="">Username</td>
   <td class=""> <input name="txtUserName" type="text" class="box" id="txtUserName" size="32" maxlength="32" value="<?php echo $user_name; ?>" disabled="true"></td>
  	<input type="hidden" name="secretEditCode" value="<?php echo SECRET_KEY;?>"
  </tr>
  <tr> 
   <td width="150" class="">อีเมล</td>
   <td class=""> <input name="txtUserEmail" type="text" class="box" id="txtUserEmail" size="32" maxlength="32" value="<?php echo $user_email; ?>"></td>
  </tr>
    <tr> 
   <td width="150" class="">ชื่อ</td>
   <td class=""> <input name="txtUserFirstName" type="text" class="box" id="txtUserFirstName" size="32" maxlength="32" value="<?php echo $user_first_name; ?>" ></td>
  </tr>
  <tr> 
   <td width="150" class="">นามสกุล</td>
   <td class=""> <input name="txtUserLastName" type="text" class="box" id="txtUserLastName" size="32" maxlength="32" value="<?php echo $user_last_name;?>" ></td>
  </tr>
  <tr> 
   <td width="150" class="">โทรศัพท์</td>
   <td class=""> <input name="txtUserPhone" type="text" class="box" id="txtUserPhone" size="32" maxlength="32" value="<?php echo $user_phone;?>" ></td>
  </tr>
  <tr> 
   <td width="150" class="">ที่อยู่</td>
   <td class=""> <input name="txtUserAddress" type="text" class="box" id="txtUserAddress" size="50" maxlength="50" value="<?php echo $user_address;?>" ></td>
  </tr>
  </tr>
  <tr> 
   <td width="150" class="">เขต/อำเภอ</td>
   <td class=""> <input name="txtUserCity" type="text" class="box" id="txtUserCity" size="32" maxlength="32" value="<?php echo $user_city;?>" ></td>
  </tr>
    <tr> 
   <td width="150" class="">จังหวัด</td>
   <td class=""> <input name="txtUserState" type="text" class="box" id="txtUserState" size="32" maxlength="32" value="<?php echo $user_state;?>" ></td>
  </tr>
  <tr> 
   <td width="150" class="">รหัสไปรษณีย์</td>
   <td class=""> <input name="txtUserPostalCode" type="text" class="box" id="txtUserPostalCode" size="10" maxlength="10" value="<?php echo $user_postal_code;?>"></td>
  </tr>
  <tr>
  	<td width="150" class="">กรอกรหัสที่เห็นในภาพ</td>
  	<td>
     <div>
        <img src="<?php echo 'captcha/captcha.php'; ?>" border="0" />
        &nbsp;<input class="box" type="text" name="captcha" id="captcha">
     </div>
   	</td>
  </tr>
  </table>
  
 <p align="center"> 
	<input name="btnAddUser" type="submit" id="btnAddUser" value="อัพเดท"  class="btn btn-primary">
  	&nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='profile.php';" class="btn btn-primary">  
 </p>
 </form>
 
 </div>
</div>
