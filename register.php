<?php
require_once 'library/config.php';
require_once 'library/customer-functions.php';
//ถ้าล็อคอินอยู่ ไม่อนุญาตให้ลงทะเบียนได้ กรณีเข้าเว็บเพจโดยตรงไม่ผ่านเมนู Register
if (isset($_SESSION['plaincart_customer_id'])) {
	header("Location: ".WEB_ROOT);
}

require_once 'library/category-functions.php';
require_once 'library/common.php';
require_once 'include/header.php';
$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

?>

<script language="JavaScript" type="text/javascript" src="library/register.js"></script>
<script language="JavaScript" type="text/javascript" src="library/common.js"></script>

<?php
//ตรวจสอบว่ามี error หรือไม่ ถ้ามีให้เก็บเข้าตัวแปร errorMessage
$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '';
//การลงทะเบียน จะมีการเก็บข้อมูลบางอย่างไว้ชั่วคราว เพราะถ้าผู้ใช้กรอกบางอย่างผิด 
//จะไม่ต้องกรอกข้อมูลเดิมซ้ำอีก
$tempName = (isset($_SESSION['txtUserName']))?$_SESSION['txtUserName']:'';    
$tempEmail = (isset($_SESSION['txtUserEmail']))?$_SESSION['txtUserEmail']:'';  
$tempFirstName = (isset($_SESSION['txtUserFirstName']))?$_SESSION['txtUserFirstName']:'';    
$tempLastName = (isset($_SESSION['txtUserLastName']))?$_SESSION['txtUserLastName']:''; 
$tempAddress = (isset($_SESSION['txtUserAddress']))?$_SESSION['txtUserAddress']:'';    
$tempPhone = (isset($_SESSION['txtUserPhone']))?$_SESSION['txtUserPhone']:''; 
$tempCity = (isset($_SESSION['txtUserCity']))?$_SESSION['txtUserCity']:'';    
$tempState = (isset($_SESSION['txtUserState']))?$_SESSION['txtUserState']:''; 
$tempPostalCode = (isset($_SESSION['txtUserPostalCode']))?$_SESSION['txtUserPostalCode']:''; 
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
  			<!--************************************************************-->
			<!--******************** แสดงประเภทสินค้า ************************-->
  			<!--************************************************************-->
  		</div>
  		<div id="product-container">
  			<!--************************************************************-->
			<!--********************* content ใส่ที่นี่ *************************-->
  			<!--************************************************************-->
  			
<?php
//ตรวจสอบว่ามี error ส่งมาหรือไม่ ถ้ามีแสดง error ดังกล่าวออกมา
if(isset($_GET['error']) && $_GET['error'] != ''){
?>
<div class="alert alert-danger" align="center"><?php echo $errorMessage; ?></div>
<?php
}
?>

<div class="panel panel-info" style="width:100%;margin-left:auto;margin-right:auto;">
	<div class="panel-heading">ลงทะเบียนสมาชิก</div>
  	<div class="panel-body">

<table align="center" cellpadding="0" cellspacing="1" class="table table-hover">

<form method="post" action="login.php" enctype="multipart/form-data" name="frmAddUser" id="frmAddUser" onSubmit="return checkRegisterInfo();">
 
  <tr> 
   <td width="200" class="">Username <span class="label label-warning">ต้องการ</span></td>
   <td class=""> <input name="txtUserName" type="text" class="box" id="txtUserName" size="32" maxlength="32" value="<?php echo $tempName; ?>"></td>
  	<input type="hidden" name="secretCode" value="<?php echo SECRET_KEY;?>"
  </tr>
  <tr> 
   <td width="150" class="">รหัสผ่าน <span class="label label-warning">ต้องการ</span></td>
   <td class=""> 
   	<input name="txtUserPassword" type="password" class="box" id="txtUserPassword" value="" size="32" maxlength="32">
    <label class="label label-default" style="font-size:0.9em;">* อักษรผสมกับตัวเลข 6 ตัวอักษรขึ้นไป</label>
   </td>
  </tr>
    <tr> 
   <td width="150" class="">ยืนยันรหัสผ่าน <span class="label label-warning">ต้องการ</span></td>
   <td class=""> <input name="txtUserConfirmPassword" type="password" class="box" id="txtConfirmPassword" value="" size="32" maxlength="32"></td>
  </tr>
  
  <tr> 
   <td width="150" class="">อีเมล <span class="label label-warning">ต้องการ</span></td>
   <td class=""> <input name="txtUserEmail" type="text" class="box" id="txtUserEmail" size="32" maxlength="32" value="<?php echo $tempEmail; ?>"></td>
  </tr>
    <tr> 
   <td width="150" class="">ชื่อ</td>
   <td class=""> <input name="txtUserFirstName" type="text" class="box" id="txtUserFirstName" size="32" maxlength="32" value="<?php echo $tempFirstName; ?>"></td>
  </tr>
  <tr> 
   <td width="150" class="">นามสกุล</td>
   <td class=""> <input name="txtUserLastName" type="text" class="box" id="txtUserLastName" size="32" maxlength="32" value="<?php echo $tempLastName; ?>"></td>
  </tr>
  <tr> 
   <td width="150" class="">โทรศัพท์</td>
   <td class=""> <input name="txtUserPhone" type="text" class="box" id="txtUserPhone" size="32" maxlength="32" value="<?php echo $tempPhone; ?>"></td>
  </tr>
  <tr> 
   <td width="150" class="">ที่อยู่</td>
   <td class=""> <input name="txtUserAddress" type="text" class="box" id="txtUserAddress" size="32" maxlength="50" value="<?php echo $tempAddress; ?>"></td>
  </tr>
  </tr>
    <tr> 
   <td width="150" class="">ที่อยู่เพิ่มเติม</td>
   <td class=""> <input name="txtUserAddress2" type="text" class="box" id="txtUserAddress2" size="32" maxlength="50"></td>
  </tr>
      <tr> 
   <td width="150" class="">เขต/อำเภอ</td>
   <td class=""> <input name="txtUserCity" type="text" class="box" id="txtUserCity" size="32" maxlength="32" value="<?php echo $tempCity; ?>"></td>
  </tr>
    <tr> 
   <td width="150" class="">จังหวัด</td>
   <td class=""> <input name="txtUserState" type="text" class="box" id="txtUserState" size="32" maxlength="32" value="<?php echo $tempState; ?>"></td>
  </tr>
  <tr> 
   <td width="150" class="">รหัสไปรษณีย์</td>
   <td class=""> <input name="txtUserPostalCode" type="text" class="box" id="txtUserPostalCode" size="10" maxlength="10" value="<?php echo $tempPostalCode; ?>"></td>
  </tr>
  <tr>
  <td width="150" class="">กรอกรหัสที่เห็นในภาพ <span class="label label-warning">ต้องการ</span></td>
  <td>
     <div>
        <img src="<?php echo 'include/captcha/captcha.php'; ?>" border="0" />
        <input type="text" name="captcha" id="captcha" class="">
     </div>
   </td>
  </tr>
  </table>
  
 <p align="center"> 
  <input name="btnAddUser" type="submit" id="btnAddUser" value="ลงทะเบียน"  class="btn btn-primary">
  &nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='index.php?clearTemp=true';" class="btn btn-primary">  
 </p>
 </form>
</td>
</tr>
</table>

	</div>
</div>
  			
		  	<!--************************************************************-->
			<!--******************* สิ้นสุดส่วน Content ************************-->
  			<!--************************************************************--> 			
  			
		</div>	
		<div id="lastest-link-content">
		  	<!--************************************************************-->
			<!--******************* แสดง Paging Link ***********************-->
  			<!--************************************************************-->
		</div>
	</div>
  	<div class="col-md-3">
  		<div id="cart-content-mini">
  		  	<!--************************************************************-->
			<!--******************** แสดงตะกร้าสินค้า *************************-->
  			<!--************************************************************-->
  		</div>
  		<div><?php require_once 'include/widgets/otherWidget.php';?></div>
  		<div><?php require_once 'include/widgets/widget2.php';?></div>
  	</div>
</div>

<script>

//เมื่อโหลดหน้าเว็บเพจนี้ขึ้นมา ให้แสดงตะกร้าสินค้าทางขวามือทันที
$(function(){
	$.ajax({
  		url:'include/miniCartAjax.php',
  		type:'get',
  		success:function(data){
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  		}
  	});
});

</script>
<?php require_once 'include/footer.php'; ?>
