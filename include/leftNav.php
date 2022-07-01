<?php
if (!defined('WEB_ROOT')) {
	exit;
}

// ข้อมูลเกี่ยวกับประเภทสินค้าทั้งหมด
$categories = fetchCategories();
?>

<div class="list-group"><!-- กำหนดคลาสให้กับแท็ก div ซึ่งอ้างอิงจาก Bootstrap -->

<?php
$active = '';
foreach ($categories as $category) {
	extract($category);
	// ในตอนนี้เราจะได้  $cat_id, $cat_parent_id, $cat_name
	$level = ($cat_parent_id == 0) ? 1 : 2;		//กำหนดค่าว่าเป็นประเภทสินค้าระดับที่ 1 หรือ 2
	$active = '';
	if ($level == 1) {	//ต้องการแสดงเฉพาะสินค้าระดับที่ 1 เท่านั้น
?>
<a href="#product-container" class="list-group-item" id="catname-<?php echo $cat_id; ?>"><?php echo $cat_name; ?></a>
<?php
	}
}
?>

</div>


