<?php
/*
require_once 'library/config.php';
*/
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
  			<!--************************************************************-->
			<!--******************** แสดงประเภทสินค้า ************************-->
  			<!--************************************************************-->
  		</div>
  		<div id="product-container">
  			<!--************************************************************-->
			<!--********************* content ใส่ที่นี่ *************************-->
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
