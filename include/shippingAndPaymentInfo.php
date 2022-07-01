<?php
//ตรวจสอบว่าเป็น step=1 หรือไม่ 
//และต้องมีการกำหนด WEB_ROOT ด้วย เนื่องจากไม่ต้องการให้เข้ามายังหน้านี้โดยตรง 
if (!defined('WEB_ROOT')
    || !isset($_GET['step']) || (int)$_GET['step'] != 1) {
	exit;
}
checkStockBeforeCheckout();	//ต้องตรวจสอบสต๊อกสินค้าก่อน ว่ายังมีอยู่หรือไม่
?>

<script language="JavaScript" type="text/javascript" src="library/checkout.js"></script>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
    <tr> 
        <td><h3>ขั้นตอนที่ 1 จาก 3 : กรุณากรอกข้อมูลของผู้รับสินค้า และ ผู้สั่งสินค้า</h3></td>
    </tr>
</table>
<?php
//ตรวจสอบว่ามี error หรือไม่โดยดูค่าจาก array ใน $_SESSION['plaincart_error'] 
if(isset($_SESSION['plaincart_error']) && $_SESSION['plaincart_error']!=null){
	?>
<div class="alert alert-danger" style="text-align:center;"><?php displayError(); ?></div>
	<?php
}
//ตรวจสอบว่าได้ล็อกอินในฐานะสมาชิกหรือไม่
if(!isset($_SESSION['plaincart_customer_id'])) {
	?>
<div class="panel panel-info">
	<div class="panel-heading">สมาชิก</div>
  	<div class="panel-body">
            ถ้าท่านเป็นสมาชิก <a href="<?php echo WEB_ROOT.'login.php?cartURL=step1';?>">คลิกที่นี่</a> ข้อมูลของท่านจะถูกนำมาใส่ลงแบบฟอร์มข้างล่างโดยอัตโนมัติ 
  	</div>
</div>

	<?php
} else {			//ถ้าเป็นสมาชิก ดึงข้อมูลสมาชิกมาเก็บไว้ที่ตัวแปร $userProfile
    $userProfile = getCustomerProfile(); 
	extract($userProfile);
}
   //นำข้อมูลของสมาชิกเก็บเข้าตัวแปร
    $customerFirstName = (isset($user_first_name))?$user_first_name:'';
    $customerLastName = (isset($user_last_name))?$user_last_name:'';
    $customerEmail = (isset($user_email))?$user_email:'';
    $customerAddress = (isset($user_address))?$user_address:'';
    $customerPhone = (isset($user_phone))?$user_phone:'';
    $customerCity = (isset($user_city))?$user_city:'';
    $customerState = (isset($user_state))?$user_state:'';
    $customerPostalCode = (isset($user_postal_code))?$user_postal_code:'';
?>
<!-- แบบฟอร์มสำหรับผู้ซื้อสินค้า -->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=2" method="post" name="frmCheckout" id="frmCheckout" onSubmit="return checkShippingAndPaymentInfo();">
<div class="panel panel-info">
  <div class="panel-heading">ข้อมูลของผู้รับสินค้า</div>
  <div class="panel-body">

    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="table">

        <tr> 
            <td width="200" class="">ชื่อ&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtShippingFirstName" type="text" class="box" id="txtShippingFirstName" size="32" maxlength="50" value="<?php echo $customerFirstName; ?>"></td>
        </tr>
        <tr> 
            <td width="200" class="">นามสกุล&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtShippingLastName" type="text" class="box" id="txtShippingLastName" size="32" maxlength="50" value="<?php echo $customerLastName; ?>"></td>
        </tr>
        <tr> 
            <td width="200" class="">Email&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtShippingEmail" type="text" class="box" id="txtShippingEmail" size="32" maxlength="50" value="<?php echo $customerEmail; ?>"></td>
        </tr>
        <tr> 
            <td width="" class="">ที่อยู่&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtShippingAddress1" type="text" class="box" id="txtShippingAddress1" size="32" maxlength="100" value="<?php echo $customerAddress; ?>"></td>
        </tr>
        <tr> 
            <td width="" class="">ที่อยู่เพิ่มเติม</td>
            <td class=""><input name="txtShippingAddress2" type="text" class="box" id="txtShippingAddress2" size="32" maxlength="100"></td>
        </tr>
        <tr> 
            <td width="" class="">เบอร์โทรศัพท์&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class="content"><input name="txtShippingPhone" type="text" class="box" id="txtShippingPhone" size="32" maxlength="32" value="<?php echo $customerPhone; ?>"></td>
        </tr>

        <tr> 
            <td width="" class="">เขต/อำเภอ&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class="content"><input name="txtShippingCity" type="text" class="box" id="txtShippingCity" size="32" maxlength="32" value="<?php echo $customerCity; ?>"></td>
        </tr>
        <tr> 
            <td width="" class="">จังหวัด&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class="content"><input name="txtShippingState" type="text" class="box" id="txtShippingState" size="32" maxlength="32" value="<?php echo $customerState; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">รหัสไปรษณีย์&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtShippingPostalCode" type="text" class="box" id="txtShippingPostalCode" size="10" maxlength="10" value="<?php echo $customerPostalCode; ?>"></td>
        </tr>
    </table>
	</div>
</div>    
    

<div class="panel panel-info">
  <div class="panel-heading">ข้อมูลของผู้ชำระค่าสินค้า</div>
  <div class="panel-body">
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
        <tr class=""> 
        	<td>&nbsp;</td>
            <td><input type="checkbox" name="chkSame" id="chkSame" value="checkbox" onClick="setPaymentInfo(this.checked);"> 
                <label for="chkSame" style="cursor:pointer">ใช้ข้อมูลเดียวกับผู้รับสินค้าด้านบน</label></td>
        </tr>
        <tr> 
            <td width="200" class="">ชื่อ&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentFirstName" type="text" class="box" id="txtPaymentFirstName" size="32" maxlength="50"></td>
        </tr>
        <tr> 
            <td width="200" class="">นามสกุล&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentLastName" type="text" class="box" id="txtPaymentLastName" size="32" maxlength="50"></td>
        </tr>
        <tr> 
            <td width="150" class="">Email&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentEmail" type="text" class="box" id="txtPaymentEmail" size="32" maxlength="50"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentAddress1" type="text" class="box" id="txtPaymentAddress1" size="32" maxlength="100"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่เพิ่มเติม</td>
            <td class=""><input name="txtPaymentAddress2" type="text" class="box" id="txtPaymentAddress2" size="32" maxlength="100"></td>
        </tr>
        <tr> 
            <td width="150" class="">เบอร์โทรศัพท์&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentPhone" type="text" class="box" id="txtPaymentPhone" size="32" maxlength="32"></td>
        </tr>
        <tr> 
            <td width="150" class="">เขต/อำเภอ&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentCity" type="text" class="box" id="txtPaymentCity" size="32" maxlength="32"></td>
        </tr>
        <tr> 
            <td width="150" class="">จังหวัด&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentState" type="text" class="box" id="txtPaymentState" size="32" maxlength="32"></td>
        </tr>
        <tr> 
            <td width="150" class="">รหัสไปรษณีย์&nbsp;<span class="label label-warning">ต้องการ</span></td>
            <td class=""><input name="txtPaymentPostalCode" type="text" class="box" id="txtPaymentPostalCode" size="10" maxlength="10"></td>
        </tr>
    </table>
  </div>
</div>
  
  
  
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
      <tr>
        <td width="150" class="">วิธีชำระเงิน </td>
        <td class="">
        <input name="optPayment" type="radio" value="cod" id="optCod" checked="checked"/>
        <label for="optCod" style="cursor:pointer">โอนเงินผ่านธนาคาร</label>
        <input name="optPayment" type="radio" id="optPaypal" value="paypal" />
        <label for="optPaypal" style="cursor:pointer">Paypal</label>
        </td>
      </tr>
    </table>
    
<div class="panel panel-info">
  <div class="panel-body"> 
    <p align="center"> 
        <input class="btn btn-primary" name="btnStep1" type="submit" id="btnStep1" value="ดำเนินการ &gt;&gt;">
    </p>
   </div>
</div>
</form>