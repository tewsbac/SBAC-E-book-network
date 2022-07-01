<?php
require_once 'library/config.php';
require_once 'library/category-functions.php';
require_once 'library/product-functions.php';
require_once 'library/cart-functions.php';

//เก็บค่า URL ก่อนที่จะมาหน้านี้เอาไว้ เพื่อที่จะได้ย้อนกลับไปได้
$_SESSION['shop_return_url'] = $_SERVER['REQUEST_URI'];
//ถ้ามีการส่งข้อความค้นหามา ก็จะใช้ค้นหาสินค้าจากข้อความนี้
$searchTerm = (isset($_GET['plainCartSearch']))?mysql_real_escape_string($_GET['plainCartSearch']):'';
$catId  = (isset($_GET['c']) && $_GET['c'] != '1') ? $_GET['c'] : 0;

//หากมีการส่งรหัสสินค้ามา ก็จะนำมาใช้แสดงสินค้านั้นๆ
function valid_pdId($get)
{
	$x = isset($_GET[$get])&&$_GET[$get]!='1' ? $_GET[$get] : '';
	if ( !ctype_digit($x) ) {
		$x = '';
	}
	return $x;
}
$pdId = valid_pdId('p');

//ฟังก์ชันเคลียร์ข้อมูลตอนลงทะเบียน
function clearTempRegister() {
    if(isset($_SESSION['textUserName']))unset($_SESSION['txtUserName']);
    if(isset($_SESSION['txtUserEmail']))unset($_SESSION['txtUserEmail']);
    if(isset($_SESSION['txtUserFirstName']))unset($_SESSION['txtUserFirstName']);
    if(isset($_SESSION['txtUserLastName']))unset($_SESSION['txtUserLastName']);
    if(isset($_SESSION['txtUserAddress']))unset($_SESSION['txtUserAddress']);
    if(isset($_SESSION['txtUserPhone']))unset($_SESSION['txtUserPhone']);
    if(isset($_SESSION['txtUserCity']))unset($_SESSION['txtUserCity']);
    if(isset($_SESSION['txtUserState']))unset($_SESSION['txtUserState']);
    if(isset($_SESSION['txtUserPostalCode']))unset($_SESSION['txtUserPostalCode']);
}
//ล้างค่าข้อมูลจากการลงทะเบียน หากผู้ใช้คลิก ยกเลิก
if(isset($_GET['clearTemp'])){
	clearTempRegister();
}

//นำข้อมูลในส่วนของ header มาแสดง
require_once 'include/header.php';

?>

<!-- นำข้อมูลในส่วนของเมนูหลักมาแสดง -->
<div class="row">
  	<div class="col-md-12"> 
  		<?php require_once 'include/nevMenu.php'; ?>
  	</div>
</div>
<?php
//ตรวจสอบว่ามีข้อความ error หรือ success หรือไม่
//เปลี่ยนชื่อคลาส เพื่อที่เวลาแสดงข้อความเตือนจะเป็นสีแดง  ข้อความที่ต้องการแจ้งเตือนเป็นสีเขียว
if(isset($_SESSION['plaincart_error']) && $_SESSION['plaincart_error']!=null){
?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger" style="text-align:center;"><?php displayError(); ?></div>
	</div>
</div>
<?php
}
if(isset($_SESSION['plaincart_success']) && $_SESSION['plaincart_success']!=null){
?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-success" style="text-align:center;"><?php displaySuccess(); ?></div>
	</div>
</div>
<?php
}
?>  
<!-- แสดงข้อมูลในส่วนของ Top -->


<div class="row">
<!-- แสดงข้อมูลในส่วนของ leftNav -->
  	<div class="col-md-2"><?php require_once 'include/leftNav.php'; ?></div>
  	<div class="col-md-7" id="resultProduct">
  		<div id="category-container"> <!-- แสดงประเภทสินค้า -->
  			<div align="center" style="margin-bottom:20px;"></div>
  			<?php require_once 'include/categoryList.php'; ?>
  			<hr>
  		</div>
  		<div id="product-container"> <!-- แสดงสินค้า -->
		</div>	
		<div id="lastest-link-content"> <!-- แสดง Page Link เพื่อไปยังหน้าต่างๆ -->
		</div>
	
	</div>
  
  	<div class="col-md-3">
  		<div id="cart-content-mini"></div> <!-- แสดงตะกร้าสินค้า -->
		<!-- แสดง Widget อื่นๆ -->
  		<div><?php require_once 'include/widgets/otherWidget.php';?></div>
  		<div><?php require_once 'include/widgets/widget2.php';?></div>
  	</div>
  
</div>

<script>

$(function(){
	//แสดงสินค้าทั้งหมดเมื่อมีการโหลดเว็บเพจขึ้นมา
	if($('#product-container').length){
		myProductAjax(1);
	} 
});

</script>

<!-- แสดงส่วนของ footer -->
<?php require_once 'include/footer.php'; ?>