<?php
require_once 'library/config.php';
require_once 'library/category-functions.php';
require_once 'library/product-functions.php';
require_once 'library/cart-functions.php';


$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

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

<div class="panel panel-info">
  <div class="panel-heading">ช่องทางชำระเงิน</div>
  <div class="panel-body">
  
		<div class="alert alert-warning">เมื่อลูกค้าหยิบสินค้าใส่ตะกร้าสินค้าและสั่งซื้อสินค้าเรียบร้อยแล้ว
หากท่านลูกค้าเลือกวิธีชำระเงินเป็นแบบโอนผ่านธนาคาร สามารถโอนเงินผ่านเคาท์เตอร์ธนาคาร, ผ่านทาง INTERNET BANKING หรือ ATM มาที่บัญชี
		</div>


		<table class="table table-striped">
		<tr>
		<td>ธนาคาร</td>
		<td>ชื่อบัญชี</td>
		<td>หมายเลขบัญชี</td>
		<td>ประเภทบัญชี</td>
		<td>สาขา</td>
		</tr>
		<tr>
		<td>กสิกรไทย</td>
		<td>บ.รีไวว่า จก.</td>
		<td>059-x-xxxxx-x</td>
		<td>ออมทรัพย์</td>
		<td>เอกมัย</td>
		</tr>
		<tr>
		<td>กรุงศรีอยุธยา</td>
		<td>บ.รีไวว่า จก.</td>
		<td>361-x-xxxxx-x</td>
		<td>ออมทรัพย์</td>
		<td>ถนนสุขุมวิท 63</td>
		</tr>
		<tr>
		<td>กรุงเทพ</td>
		<td>บ.รีไวว่า จก.</td>
		<td>063-x-xxxxx-x</td>
		<td>ออมทรัพย์</td>
		<td>เอกมัย</td>
		</tr>
		</table>
		
		<div class="alert alert-warning">เมื่อท่านชำระค่าสินค้าแล้ว กรุณาแจ้งทางเว็บไซต์ด้วยการโทรมาที่เบอร์โทรศัพท์ 081-xxx-xxxx
หรือกรอกแบบฟอร์มเพื่อส่งข้อมูลการชำระเงินมายังเรา ด้วยการเข้าไปที่เมนู <strong>แจ้งชำระเงิน</strong>
		</div>
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

<?php require_once 'include/footer.php'; ?>
