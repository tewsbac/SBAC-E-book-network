<?php
require_once 'library/config.php';
require_once 'library/cart-functions.php';
require_once 'library/category-functions.php';
$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : 'view';
if(isset($_POST['btnUpdate'])){
	$action = 'update';
}
switch ($action) {
	case 'add' :
		addToCart();
		break;
	case 'update' :
		updateCart();
		break;	
	case 'delete' :
		deleteFromCart();
		break;
	case 'view' :
}

$cartContent = getCartContent();
$numItem = count($cartContent);
$pageTitle = 'Shopping Cart';


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
			<!--******************** ตรวจสอบ error *************************-->
  			<!--************************************************************-->  		

<?php
//ตรวจสอบว่ามี error หรือไม่โดยดูค่าจาก array ใน $_SESSION['plaincart_error'] ถ้ามีก็ให้เปลี่ยนชื่อ class
if(isset($_SESSION['plaincart_error']) && $_SESSION['plaincart_error'] != null){
?>
<div class="alert alert-danger" style="text-align:center;"><?php displayError(); ?></div>
<?php 
}else{

}
?>  		
  			<!--************************************************************-->
			<!--******************** content ใส่ที่นี่ **************************-->
  			<!--************************************************************-->
  			
<?php			
  			if ($numItem > 0 ) {
?>


<div class="panel panel-info">
  <div class="panel-heading">ตะกร้าสิาค้า</div>
  <div class="panel-body">
<div class="table-responsive">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="table">
<form action="<?php echo $_SERVER['PHP_SELF'] . "?action=update"; ?>" method="post" name="frmCart" id="frmCart">

  <tr class="active"> 
   <td width="50%" colspan="2" align="center">รายการ</td>
   <td width="15%" align="right">ราคา</td>
   <td width="15%" align="center">จำนวน</td>
   <td width="15%"align="right">รวม</td>
  <td width="5%" align="center">&nbsp;</td>
 </tr>
 <?php
$subTotal = 0;
for ($i = 0; $i < $numItem; $i++) {
	extract($cartContent[$i]);
	$productUrl = "index.php?c=$cat_id&p=$pd_id";
	$subTotal += $pd_price * $ct_qty;
?>
 <tr class="content"> 
  <td align="center">
  	<span class="hidden-cat-product" id="productmini-<?php $cat_id; ?>" hidden></span>
 	<a href="#" id="product-<?php echo $pd_id; ?>" class="show-detail-product">
 	<img class="hidden-xs hidden-sm img-rounded" src="<?php echo $pd_thumbnail; ?>" border="0"></a></td>
  <td><a href="#" id="product-<?php echo $pd_id; ?>" class="show-detail-product"><?php echo $pd_name; ?></a></td>
   <td align="right"><?php echo displayAmount($pd_price); ?></td>
  <td><input name="txtQty[]" type="text" id="txtQty[]" size="5" value="<?php echo $ct_qty; ?>" class="box" onKeyUp="checkNumber(this);">
  <input name="hidCartId[]" type="hidden" value="<?php echo $ct_id; ?>">
  <input name="hidProductId[]" type="hidden" value="<?php echo $pd_id; ?>">
  </td>
  <td align="right"><?php echo displayAmount($pd_price * $ct_qty); ?></td>
  <td align="center"> <input name="btnDelete" type="button" id="btnDelete" value="ลบ" onClick="window.location.href='<?php echo $_SERVER['PHP_SELF'] . "?action=delete&cid=$ct_id"; ?>';" class="btn btn-danger"> 
  </td>
 </tr>
 <?php
}
?>
 <tr class="content"> 
  <td colspan="4" align="right">รวมย่อย</td>
  <td align="right"><?php echo displayAmount($subTotal); ?></td>
  <td  align="center">&nbsp;</td>
 </tr>
<tr class="content"> 
   <td colspan="4" align="right">ค่าขนส่ง </td>
  <td align="right"><?php echo displayAmount($shopConfig['shippingCost']); ?></td>
  <td align="center">&nbsp;</td>
 </tr>
<tr class="active"> 
   <td colspan="4" align="right">รวมสุทธิ </td>
  <td align="right"><strong style="color:#428bca;font-size:1.2em;text-decoration:underline;"><?php echo displayAmount($subTotal + $shopConfig['shippingCost']); ?></strong></td>
  <td align="center">&nbsp;</td>
 </tr>  
 <tr class="content"> 
  <td colspan="5" align="right">&nbsp;</td>
  <td align="center">
<input name="btnUpdate" type="submit" id="btnUpdate" value="อัพเดทตะกร้า" class="btn btn-success"></td>
 </tr>
 </form>
</table>
</div>
</div>
</div><!--div class="panel-body"-->
<?php
} else {
	
?>
<div class="panel panel-info">
  	<div class="panel-heading">ตะกร้าสิาค้า</div>
  	<div class="panel-body">
  
		<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
 			<tr>
  				<td><p align="center" style="color:#428bca;font-size:1.2em;">ตะกร้าสินค้าว่างเปล่า</p>
   					<p>ปล. หากคุณพบว่าไม่สามารถใส่สินค้าลงตะกร้าได้, โปรดตรวจสอบว่าบราวเซอร์ของคุณได้เปิดการใช้งาน Cookies หรือไม่ 
    หรือเป็นผลมาจากซอฟต์แวร์รักษาความปลอดภัยที่บล็อกการทำงานของเซสชั่นเอาไว้ ถ้าท่านพบปัญหาการใช้งาน กรุณาติดต่อกับทางร้าน</p><p>&nbsp;</p></td>
 			</tr>
		</table>
	</div>
</div>

<?php
}



$shoppingReturnUrl = isset($_SESSION['shop_return_url']) ? $_SESSION['shop_return_url'] : 'index.php';
?>


<div class="panel panel-info">
  <div class="panel-body"> 
    <p align="center"> 
		<table border="0" align="center" cellpadding="10" cellspacing="0">
 			<tr align="center"> 
  				<td><input name="btnContinue" type="button" id="btnContinue" value="&lt;&lt; ช็อปปิ้งสินค้าต่อ" onClick="window.location.href='<?php echo $shoppingReturnUrl; ?>';" class="btn btn-primary"></td>
				<td width="5%">&nbsp;</td>
				<?php 
				if ($numItem > 0) {
				?>  
  					<td><input name="btnCheckout" type="button" id="btnCheckout" value="ดำเนินการเช็คเอาท์ &gt;&gt;" onClick="window.location.href='checkout.php?step=1';" class="btn btn-primary"></td>
				<?php
				}
				?>  
 			</tr>
		</table>
    </p>
   </div>
</div>




	
  			<!--************************************************************-->
			<!--******************* สิ้นสุด content ตรงนี้ **********************-->
  			<!--************************************************************-->
	
		</div><!--div ปิด id="product-container"-->	
		
		
		
		<div id="lastest-link-content">
		
		  	<!--************************************************************-->
			<!--****************** paging-link ใส่ที่นี่ ************************-->
  			<!--************************************************************-->	
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
  			$("#cart-content-mini").empty().fadeIn(1000);
  		}
  	});
});

</script>

<?php require_once 'include/footer.php'; ?>
