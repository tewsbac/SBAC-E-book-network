<?php
//ตรวจสอบว่ามาจาก step=1 และต้องแน่ใจว่าไม่มีการเข้ามายังหน้านี้โดยตรง
if (!defined('WEB_ROOT')
    || !isset($_GET['step']) || (int)$_GET['step'] != 2
	|| $_SERVER['HTTP_REFERER'] != 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?step=1') {
	exit;
}

$errorMessage = '&nbsp;';

/* ทำให้แน่ใจว่า ข้อมูลในส่วนที่ต้องการต้องไม่เป็นค่าว่าง */
$requiredField = array('txtShippingFirstName', 'txtShippingLastName', 'txtShippingEmail', 'txtShippingAddress1', 'txtShippingPhone', 'txtShippingState',  'txtShippingCity', 'txtShippingPostalCode',
                       'txtPaymentFirstName', 'txtPaymentLastName', 'txtPaymentEmail','txtPaymentAddress1', 'txtPaymentPhone', 'txtPaymentState', 'txtPaymentCity', 'txtPaymentPostalCode');
					   
if (!checkRequiredPost($requiredField)) {
	$errorMessage = 'ผิดพลาด ข้อมูลไม่สมบูรณ์';
}
					   

$cartContent = getCartContent();	//ดึงข้อมูลจากตะกร้าสินค้ามาเก็บเข้าตัวแปร $cartContent

?>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
    <tr> 
        <td><h3>ขั้นตอนที่ 2 จาก 3 : ยืนยันการสั่งสินค้า</h3></td>
    </tr>
</table>
<div id="errorMessage" alert alert-danger><?php echo $errorMessage; ?></div>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?step=3" method="post" name="frmCheckout" id="frmCheckout">
<?php 
if ($_POST['optPayment'] == 'paypal') {
?>
    <table width="550" border="0" align="center" cellpadding="10" cellspacing="0">
        <tr> 
            <td align="center"><strong>:: IMPORTANT NOTE :: </strong></td>
        </tr>
        <tr> 
            <td><p>หลังจากที่คลิกปุ่ม &quot;ยืนยันสั่งสินค้า&quot; ตามด้านล่างนี้ 
                    จะมีการ redirect ไปยังเว็บไซต์ PayPal 
                    จะมีการให้ล็อกอินเพื่อทำการ checkout ชำระค่าสินค้า
                    ซึ่งในที่นี้จะเป็นการจำลองการใช้งานโดยใช้ Account ของนักพัฒนาจาก PayPal
                    ถ้าท่านไม่มีสามารถใช้
                    :<br>
                    Email : buyer@itbookonline.com <br>
                    Password : phpwebco </p>
            </td>
        </tr>
    </table>

<?php
}
?>

<div class="panel panel-info">
  <div class="panel-heading">รายการสินค้าที่ท่านได้สั่งไว้กับทางร้าน</div>
  <div class="panel-body"> 
    <table width="550" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
        <tr class="active"> 
            <td>รายการ</td>
            <td align="right">ราคาสินค้า</td>
            <td align="right">รวมย่อย</td>
        </tr>
        <?php
$numItem  = count($cartContent);
$subTotal = 0;
for ($i = 0; $i < $numItem; $i++) {
	extract($cartContent[$i]);
	$subTotal += $pd_price * $ct_qty;
?>
        <tr class=""> 
            <td class=""><?php echo "<span class=\"label label-danger\" style=\"font-size:0.9em;\">$ct_qty</span> x $pd_name"; ?></td>
            <td align="right"><?php echo displayAmount($pd_price); ?></td>
            <td align="right"><?php echo displayAmount($ct_qty * $pd_price); ?></td>
        </tr>
        <?php
}
?>
        <tr class=""> 
            <td colspan="2" align="right">รวม</td>
            <td align="right"><?php echo displayAmount($subTotal); ?></td>
        </tr>
        <tr class=""> 
            <td colspan="2" align="right">ค่าขนส่ง</td>
            <td align="right"><?php echo displayAmount($shopConfig['shippingCost']); ?></td>
        </tr>
        <tr class="active"> 
            <td colspan="2" align="right">รวมสุทธิ</td>
            <td align="right"><strong style="font-size:1.1em;"><?php echo displayAmount($shopConfig['shippingCost'] + $subTotal); ?></strong></td>
        </tr>
    </table>
    </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">ข้อมูลของผู้รับสินค้า</div>
  <div class="panel-body">  
  
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
        <tr> 
            <td width="150" class="">ชื่อ</td>
            <td class=""><?php echo $_POST['txtShippingFirstName']; ?>
                <input name="hidShippingFirstName" type="hidden" id="hidShippingFirstName" value="<?php echo $_POST['txtShippingFirstName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">นามสกุล</td>
            <td class=""><?php echo $_POST['txtShippingLastName']; ?>
                <input name="hidShippingLastName" type="hidden" id="hidShippingLastName" value="<?php echo $_POST['txtShippingLastName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">อีเมล</td>
            <td class=""><?php echo $_POST['txtShippingEmail']; ?>
                <input name="hidShippingEmail" type="hidden" id="hidShippingEmail" value="<?php echo $_POST['txtShippingEmail']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่</td>
            <td class=""><?php echo $_POST['txtShippingAddress1']; ?>
                <input name="hidShippingAddress1" type="hidden" id="hidShippingAddress1" value="<?php echo $_POST['txtShippingAddress1']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่เพิ่มเติม</td>
            <td class=""><?php echo $_POST['txtShippingAddress2']; ?>
                <input name="hidShippingAddress2" type="hidden" id="hidShippingAddress2" value="<?php echo $_POST['txtShippingAddress2']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">โทรศัพท์</td>
            <td class=""><?php echo $_POST['txtShippingPhone'];  ?>
                <input name="hidShippingPhone" type="hidden" id="hidShippingPhone" value="<?php echo $_POST['txtShippingPhone']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">เขต / อำเภอ</td>
            <td class=""><?php echo $_POST['txtShippingState']; ?> <input name="hidShippingState" type="hidden" id="hidShippingState" value="<?php echo $_POST['txtShippingState']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="">จังหวัด</td>
            <td class=""><?php echo $_POST['txtShippingCity']; ?>
                <input name="hidShippingCity" type="hidden" id="hidShippingCity" value="<?php echo $_POST['txtShippingCity']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="">รหัสไปรษณีย์</td>
            <td class=""><?php echo $_POST['txtShippingPostalCode']; ?>
                <input name="hidShippingPostalCode" type="hidden" id="hidShippingPostalCode" value="<?php echo $_POST['txtShippingPostalCode']; ?>"></td>
        </tr>
    </table>
    </div>
</div>


<div class="panel panel-info">
  <div class="panel-heading">ข้อมูลผู้ชำระสินค้า</div>
  <div class="panel-body"> 
  
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
        <tr> 
            <td width="150" class="">ชื่อ</td>
            <td class=""><?php echo $_POST['txtPaymentFirstName']; ?>
                <input name="hidPaymentFirstName" type="hidden" id="hidPaymentFirstName" value="<?php echo $_POST['txtPaymentFirstName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">นามสกุล</td>
            <td class=""><?php echo $_POST['txtPaymentLastName']; ?>
                <input name="hidPaymentLastName" type="hidden" id="hidPaymentLastName" value="<?php echo $_POST['txtPaymentLastName']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">อีเมล</td>
            <td class=""><?php echo $_POST['txtPaymentEmail']; ?>
                <input name="hidPaymentEmail" type="hidden" id="hidPaymentEmail" value="<?php echo $_POST['txtPaymentEmail']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่</td>
            <td class=""><?php echo $_POST['txtPaymentAddress1']; ?>
                <input name="hidPaymentAddress1" type="hidden" id="hidPaymentAddress1" value="<?php echo $_POST['txtPaymentAddress1']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">ที่อยู่เพิ่มเติม</td>
            <td class=""><?php echo $_POST['txtPaymentAddress2']; ?> <input name="hidPaymentAddress2" type="hidden" id="hidPaymentAddress2" value="<?php echo $_POST['txtPaymentAddress2']; ?>"> 
            </td>
        </tr>
        <tr> 
            <td width="150" class="">เบอร์โทรศัพท์</td>
            <td class=""><?php echo $_POST['txtPaymentPhone'];  ?> <input name="hidPaymentPhone" type="hidden" id="hidPaymentPhone" value="<?php echo $_POST['txtPaymentPhone']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">อำเภอ/เขต</td>
            <td class=""><?php echo $_POST['txtPaymentState']; ?> <input name="hidPaymentState" type="hidden" id="hidPaymentState" value="<?php echo $_POST['txtPaymentState']; ?>" ></td>
        </tr>
        <tr> 
            <td width="150" class="">จังหวัด</td>
            <td class=""><?php echo $_POST['txtPaymentCity']; ?>
                <input name="hidPaymentCity" type="hidden" id="hidPaymentCity" value="<?php echo $_POST['txtPaymentCity']; ?>"></td>
        </tr>
        <tr> 
            <td width="150" class="">รหัสไปรษณีย์</td>
            <td class=""><?php echo $_POST['txtPaymentPostalCode']; ?>
                <input name="hidPaymentPostalCode" type="hidden" id="hidPaymentPostalCode" value="<?php echo $_POST['txtPaymentPostalCode']; ?>"></td>
        </tr>
    </table>
    </div>
</div>    

<div class="panel panel-info">
  <div class="panel-heading">วิธีชำระเงิน</div>
  <div class="panel-body"> 
    <table border="0" align="center" cellpadding="5" cellspacing="1" class="table">
      <tr>
        <td class=""><?php echo $_POST['optPayment'] == 'paypal' ? 'Paypal' : 'โอนเงินผ่านธนาคาร'; ?>
          <input name="hidPaymentMethod" type="hidden" id="hidPaymentMethod" value="<?php echo $_POST['optPayment']; ?>" />
        </tr>
    </table>
   </div>
</div>


<div class="panel panel-info">
  <div class="panel-body"> 
  		<div align="center"> 
        <input name="btnBack" type="button" id="btnBack" value="&lt;&lt; แก้ไขข้อมูลผู้สั่ง/ผู้รับสินค้า" onClick="window.location.href='checkout.php?step=1';" class="btn btn-primary">
        &nbsp;&nbsp; 
        <input name="btnConfirm" type="submit" id="btnConfirm" value="ยืนยันการสั่งสินค้า &gt;&gt;" class="btn btn-primary">
   		</div>
   </div>
</div>

</form>
<p>&nbsp;</p>
