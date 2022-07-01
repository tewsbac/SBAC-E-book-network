<?php
//ตรวจสอบว่ามีการกำหนดค่าคงที่ WEB_ROOT หรือไม่  ซึ่งถูกกำหนดที่ไฟล์ library/config.php
if (!defined('WEB_ROOT')) {
	exit;
}

//ตรวจสอบค่า cat Id ซึ่งถ้าไม่มีกำหนดก็ให้ใส่ค่า 0 (ศูนย์) ลงไป
if (isset($_GET['catId']) && (int)$_GET['catId'] >= 0) {
	$catId = (int)$_GET['catId'];
	$queryString = "&catId=$catId";
} else {
	$catId = 0;
	$queryString = '';
}
	
// สำหรับการแสดงข้อมูลในหลายๆ หน้า
// กำหนดจำนวนแถวที่แสดงในแต่ละหน้า
$rowsPerPage = 5;
//คำสั่ง SQL เพื่อคิวรี่รายชื่อประเภทสินค้าออกมา 
//เงื่อนไข เลือกมาจาก cat_parent_id ซึ่งถ้าเป็น 0 แสดงว่า เป็นประเภทสินค้าหลัก
$sql = "SELECT cat_id, cat_parent_id, cat_name, cat_description, cat_image
        FROM tbl_category
		WHERE cat_parent_id = $catId
		ORDER BY cat_name";
//ใช้ฟังก์ชัน getPageingQuery() เลือกเฉพาะข้อมูลที่อยู่ในหน้าลำดับที่ต้องการ
$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
//เรียกใช้ฟังก์ชัน getPageingLink() แสดงลิงก์ไปยังหน้าลำดับต่างๆ
$pagingLink = getPagingLink($sql, $rowsPerPage);
?>
<p>&nbsp;</p>
<form action="processCategory.php?action=addCategory" method="post"  name="frmListCategory" id="frmListCategory">
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr align="center" id="listTableHeader"> 
   <td>Category Name</td>
   <td>Description</td>
   <td width="75">Image</td>
   <td width="75">Modify</td>
   <td width="75">Delete</td>
  </tr>
  <?php
$cat_parent_id = 0;		//เริ่มแรกกำหนดให้มีค่าเป็น 0 เพื่อกำหนดให้เป็นประเภทสินค้าหลัก
if (dbNumRows($result) > 0) {
	$i = 0;
	
	//วนลูปไปในทุกๆ เร็คคอร์ดเซ็ตที่คิวรี่มา
	while($row = dbFetchAssoc($result)) {
		extract($row);
		
		//แถวคู่กับแถวคี่กำหนด Class ต่างกัน เพื่อให้แสดงสีสันไม่เหมือนกัน
		if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
		
		//เพิ่มค่า $i ไปเรื่อยๆ ทุกๆ ครั้งที่วนลูป while
		$i += 1;
		
		//ถ้าเป็นประเภทสินค้าหลัก ค่าของ $cat_parent_id จะมีค่าเป็น 0
		if ($cat_parent_id == 0) {
			$cat_name = "<a href=\"index.php?catId=$cat_id\">$cat_name</a>";
		}
		//รูปภาพของประเภทสินค้า
		if ($cat_image) {
			$cat_image = WEB_ROOT . 'images/category/' . $cat_image;
		} else {
			$cat_image = WEB_ROOT . 'images/no-image-small.png';
		}		
?>
  <tr class="<?php echo $class; ?>"> 
   <td><?php echo $cat_name; ?></td>
   <td><?php echo nl2br($cat_description); ?></td>
   <td width="75" align="center"><img src="<?php echo $cat_image; ?>"></td>
   <td width="75" align="center"><a href="javascript:modifyCategory(<?php echo $cat_id; ?>);">Modify</a></td>
   <td width="75" align="center"><a href="javascript:deleteCategory(<?php echo $cat_id; ?>);">Delete</a></td>
  </tr>
  <?php
  	} 		// สิ้นสุดลูป while


?>
  <tr> 
   <td colspan="5" align="center">
   <?php 
   echo $pagingLink;	//แสดงลิงก์ไปยังหน้าต่างๆ กรณีมีประเภทสินค้าจำนวนมาก
   ?></td>
  </tr>
<?php	
} else {
?>
  <tr> 
   <td colspan="5" align="center">No Categories Yet</td>
  </tr>
  <?php
}
?>
  <tr> 
   <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
   <td colspan="5" align="right"> <input name="btnAddCategory" type="button" id="btnAddCategory" value="Add Category" class="box" onClick="addCategory(<?php echo $catId; ?>)"> 
   </td>
  </tr>
 </table>
 <p>&nbsp;</p>
</form>