<script language="JavaScript" type="text/javascript" src="../library/register.js"></script>
<script language="JavaScript" type="text/javascript" src="../library/common.js"></script>
<link type="text/css" rel="stylesheet" href="shop.css" />

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.theme.css" rel="stylesheet" type="text/css">
<!--link href="shop.css" rel="stylesheet" type="text/css"-->
<script language="JavaScript" type="text/javascript" src="bootstrap/js/jquery-1.11.0.min.js"></script>
<script language="JavaScript" type="text/javascript" src="bootstrap/js/library/bootstrap.min.js"></script>


<?php


require_once '../library/category-functions.php';

$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

require_once 'header.php';
//require_once 'nevMenu.php'; 



if(isset($_POST['txtConfirmPassword'])){
   customerChangePassword();
} 

$userProfile = getCustomerProfile(); 
extract($userProfile);

?> 

<p>&nbsp;</p>

<?php
//ตรวจสอบว่ามี error หรือไม่โดยดูค่าจาก array ใน $_SESSION['plaincart_error'] ถ้ามีก็ให้เปลี่ยนชื่อ class
if(isset($_SESSION['plaincart_error']) && count($_SESSION['plaincart_error'])>0){
?>
<div class="alert alert-danger" style="width:40%;margin-left:auto;margin-right:auto;"><?php displayError(); ?></div>
<?php
}
?>
<?php
//ถ้าเปลี่ยนรหัสผ่านเรียบร้อย
if(isset($_SESSION['plaincart_success']) && count($_SESSION['plaincart_success'])>0){
?>
<div class="alert alert-success" style="width:40%;margin-left:auto;margin-right:auto;"><?php displaySuccess(); ?></div>
<?php
}
?>
<div class="panel panel-info" style="width:40%;margin-left:auto;margin-right:auto;">
	<div class="panel-heading">เปลี่ยนรหัสผ่าน</div>
  	<div class="panel-body">
  	
<form method="post" enctype="multipart/form-data" name="frmPassword" id="frmPassword" onsubmit="return checkPassword()">
 <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
  <tr> 
   <td width="150" class="">Username</td>
   <td class=""><?php echo $user_name; ?>
    <input name="hidUserId" type="hidden" id="hidUserId" value="<?php echo $user_id; ?>"> 
    <input type="hidden" name="secretPassCode" value="<?php echo SECRET_KEY;?>"
    </td>
  </tr>
  <tr> 
   <td width="150" class="">รหัสผ่านเดิม</td>
   <td class=""> <input name="txtOldPassword" type="password" class="" id="txtOldPassword" size="32" maxlength="32"></td>
  </tr>
  <tr> 
   <td width="150" class="">ตั้งรหัสผ่านใหม่</td>
   <td class=""> <input name="txtPassword" type="password" class="" id="txtPassword" size="32" maxlength="32"></td>
  </tr>
    <tr> 
   <td width="150" class="">ยืนยันรหัสผ่านใหม่</td>
   <td class=""> <input name="txtConfirmPassword" type="password" class="" id="txtConfirmPassword" size="32" maxlength="32"></td>
  </tr>

  
 </table>
 <p align="center"> 
  <input name="btnModifyUser" type="submit" id="btnModifyUser" value="เปลี่ยนรหัส" class="btn btn-primary">
  &nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='profile.php';" class="btn btn-primary">  
  
 </p>
</form>

</div>
</div>
