<?php
require_once '../library/config.php';
require_once '../library/common.php';
require_once '../library/customer-functions.php';
require_once '../library/category-functions.php';
require_once 'header.php';
$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;
checkCustomerUser();
?> 
<script language="JavaScript" type="text/javascript" src="../library/register.js"></script>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="bootstrap/css/bootstrap.theme.css" rel="stylesheet" type="text/css">
<link href="shop.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="bootstrap/js/jquery-1.11.0.min.js"></script>
<script language="JavaScript" type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

<?php
//ตรวจดูว่ามี error ส่งมาหรือไม่
$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;'; 
$userProfile = getCustomerProfile(); 
extract($userProfile);
$secretHash = md5($user_id.SECRET_KEY);
?>
<p class="errorMessage"><?php echo $errorMessage; ?></p>
<?php $secretHash; ?>

<div class="panel panel-info" style="width:60%;margin-left:auto;margin-right:auto;">
	<div class="panel-heading">ข้อมูลส่วนตัว : <?php echo $user_name;?></div>
  	<div class="panel-body">
<!-- ส่งแบบฟอร์มไปยังไฟล์ processProfile.php -->
<form method="post" action="processProfile.php?profileKey=<?php echo $secretHash; ?>&action=edit" enctype="multipart/form-data" name="frmAddUser" id="frmAddUser" onSubmit="return checkRegisterInfo();">
 <table width="500" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
  <tr> 
   <td width="150" class="">Username</td>
   <td class=""> <input name="txtUserName" type="text" class="box" id="txtUserName" size="32" maxlength="32" value="<?php echo $user_name; ?>" disabled="true"></td>
  	<input type="hidden" name="secretCode" value="<?php echo SECRET_KEY;?>"
  </tr>
  <tr> 
   <td width="150" class="">อีเมล</td>
   <td class=""> <input name="txtUserEmail" type="text" class="box" id="txtUserEmail" size="32" maxlength="32" value="<?php echo $user_email; ?>" disabled="true"></td>
  </tr>
    <tr> 
   <td width="150" class="">ชื่อ</td>
   <td class=""> <input name="txtUserFirstName" type="text" class="box" id="txtUserFirstName" size="32" maxlength="32" value="<?php echo $user_first_name; ?>" disabled="true"></td>
  </tr>
  <tr> 
   <td width="150" class="">นามสกุล</td>
   <td class=""> <input name="txtUserLastName" type="text" class="box" id="txtUserLastName" size="32" maxlength="32" value="<?php echo $user_last_name;?>" disabled="true"></td>
  </tr>
  <tr> 
   <td width="150" class="">โทรศัพท์</td>
   <td class=""> <input name="txtUserPhone" type="text" class="box" id="txtUserPhone" size="32" maxlength="32" value="<?php echo $user_phone;?>" disabled="true"></td>
  </tr>
  <tr> 
   <td width="150" class="">ที่อยู่</td>
   <td class=""> <input name="txtUserAddress" type="text" class="box" id="txtUserAddress" size="50" maxlength="50" value="<?php echo $user_address;?>" disabled="true"></td>
  </tr>
  </tr>
  <tr> 
   <td width="150" class="">เขต/อำเภอ</td>
   <td class=""> <input name="txtUserCity" type="text" class="box" id="txtUserCity" size="32" maxlength="32" value="<?php echo $user_city;?>" disabled="true"></td>
  </tr>
    <tr> 
   <td width="150" class="">จังหวัด</td>
   <td class=""> <input name="txtUserState" type="text" class="box" id="txtUserState" size="32" maxlength="32" value="<?php echo $user_state;?>" disabled="true"></td>
  </tr>
  <tr> 
   <td width="150" class="">รหัสไปรษณีย์</td>
   <td class=""> <input name="txtUserPostalCode" type="text" class="box" id="txtUserPostalCode" size="10" maxlength="10" value="<?php echo $user_postal_code;?>" disabled="true"></td>
  </tr>
  </table>
  
 <p align="center"> 
   	<input name="btnChangePass" type="button" id="btnChangePass" value="เปลี่ยนรหัสผ่าน" onClick="window.location.href='processProfile.php?action=pass&profileKey=<?php echo $secretHash; ?>'" class="btn btn-primary">
  	&nbsp;&nbsp;<input name="btnAddUser" type="submit" id="btnAddUser" value="แก้ไขข้อมูล"  class="btn btn-danger">
  	&nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="กลับไปยังร้านค้า" onClick="window.location.href='<?php echo WEB_ROOT; ?>';" class="btn btn-success">  
 </p>
 </form>
</div>
</div>
<?php
require_once 'footer.php';
?>