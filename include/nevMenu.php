<?php
if (!defined('WEB_ROOT')) {
	exit;
}
?>


<?php
if(isset($_SESSION['plaincart_customer_id'])){
		$userName='';
		$userId = $_SESSION['plaincart_customer_id'];
		$sql = "SELECT user_name
		        FROM tbl_user 
				WHERE user_id = $userId";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$userName = $row['user_name']; 
		} 
	
?>	
	<p align="right">สวัสดี : <a href="<?php echo WEB_ROOT; ?>include/profile.php" class="leftnav"><?php echo $userName; ?></a>&nbsp; | &nbsp;
	<a href="<?php echo WEB_ROOT; ?>login.php?action=logout" class="leftnav">ล็อกเอาท์</a>&nbsp;&nbsp;</p>
<?php
} else {
?>

<p align="right"><a href="<?php echo WEB_ROOT; ?>login.php" class="leftnav">ล็อคอิน</a> &nbsp; | &nbsp;
<a href="<?php echo WEB_ROOT; ?>login.php?action=register" class="leftnav">ลงทะเบียน</a>&nbsp;&nbsp;</p>
<?php

}


// get all categories
$categories = fetchCategories();

// format the categories for display
$categories = formatCategories($categories, $catId);

?>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">

    
    
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo WEB_ROOT; ?>"></a>
    </div>
    

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo WEB_ROOT; ?>">หน้าแรก</a></li>
        <!-- Collect the nav links, สำหรับประเภทสินค้า -->
        <!--
        <li class="dropdown">
          	<a href="#" class="dropdown-toggle" data-toggle="dropdown">ประเภทสินค้า<b class="caret"></b></a>


		  
			<ul class = dropdown-menu>
				<li><a href="<?php echo WEB_ROOT; ?>">All Category</a></li>
				<li class="divider">

<?php echo fetchCategoriesForNev(); ?>
				<li class="divider">
			</ul>
        </li-->



        
        <li><a href="<?php echo WEB_ROOT.'paymentChanel.php'; ?>">วิธีชำระเงิน</a></li>
        <li><a href="<?php echo WEB_ROOT.'paidForm.php'; ?>">แจ้งชำระเงิน</a></li>
        <li><a href="<?php echo WEB_ROOT.'contactUs.php'; ?>">ติดต่อเรา</a></li>
        <li><a href="<?php echo WEB_ROOT.'checkout.php?step=1'; ?>">คิดเงิน</a></li>
		
		
		
		<li>
			<a href="<?php echo WEB_ROOT.'cart.php'; ?>" id="nav-cart">
      			<span id="nav-cart-number" class="badge pull-right"></span>
      			<span class="glyphicon glyphicon-shopping-cart">
    		</a>
  		</li>
		
		
      </ul>
      <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="search" class="form-control" name="plainCartSearch" placeholder="กรอกสินค้าที่จะค้นหา" id="search-box" tabindex="1" onKeypress="return submitSearchByEnter(event)">
        </div>
        <button type="button" class="btn btn-default search-product" id="search-product-button" tabindex="2">ค้น</button>
      </form>
      

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



