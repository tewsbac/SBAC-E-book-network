<?php
if (!defined('WEB_ROOT')) {
	exit;
}

require_once '../../library/category-functions.php';

if (isset($_GET['catId']) && (int)$_GET['catId'] > 0) {
//SQL กรณีมีการเลือกประเภทสินค้า
	$catId = (int)$_GET['catId'];
	$children = array_merge(array($catId), getChildCategories(NULL, $catId));
	$children = ' (' . implode(', ', $children) . ')';
	$sql2 = "WHERE c.cat_id = $catId AND pd.cat_id IN $children";
	$queryString = "catId=$catId";
} else {	//SQL กรณีไม่มีการเลือกประเภทสินค้า
	$catId = 0;
	$sql2  = 'WHERE c.cat_id = pd.cat_id';
	$queryString = "catId=$catId";
}

//สำหรับแบ่งประเภทสินค้าออกเป็นหลายๆ หน้า
//กำหนดจำนวนแถวต่อหนึ่งหน้า
$rowsPerPage = 5;

		
$sql = "SELECT pd_id, pd_name, pd_price, pd_thumbnail, pd_qty, c.cat_id, c.cat_name
		FROM tbl_product pd, tbl_category c
		$sql2 
		ORDER BY pd_name";
		
//คิวรี่ข้อมูลจากตาราง โดยจะแสดงสินค้าเฉพาะหน้าซึ่งได้คลิกเลือก แต่ถ้าไม่มีการกำหนดเลขหน้า 
//จะไปยังหน้าแรก
$result     = dbQuery(getPagingQuery($sql, $rowsPerPage));
//สร้างลิงก์สำหรับไปยังหน้าต่างๆ  
$pagingLink = getPagingLink($sql, $rowsPerPage, $queryString);

//เก็บรายชื่อประเภทสินค้าทั้งหมด 
$categoryList = buildCategoryOptions($catId);

?> 
<p>&nbsp;</p>
<form action="processProduct.php?action=addProduct" method="post"  name="frmListProduct" id="frmListProduct">
 <table width="100%" border="0" cellspacing="0" cellpadding="2" class="text">
  <tr>
   <td align="right">View products in : 
    <select name="cboCategory" class="box" id="cboCategory" onChange="viewProduct();">
     <option selected>All Category</option>
	<?php echo $categoryList; ?>
   </select>
 </td>
 </tr>
</table>
<br>
 <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="text">
  <tr align="center" id="listTableHeader"> 
   <td>Product Name</td>
   <td width="75">Thumbnail</td>
   <td width="75">Category</td>
   <td width="70">Modify</td>
   <td width="70">Delete</td>
  </tr>
  <?php
$parentId = 0;
if (dbNumRows($result) > 0) {
	$i = 0;
	
	while($row = dbFetchAssoc($result)) {
		extract($row);
		
		//สินค้ามีรูปภาพ หรือไม่ ถ้าไม่มี ก็ให้ใช้รูปอื่นแทน
		if ($pd_thumbnail) {
			$pd_thumbnail = WEB_ROOT . 'images/product/' . $pd_thumbnail;
		} else {
			$pd_thumbnail = WEB_ROOT . 'images/no-image-small.png';
		}	
		
		
		
		if ($i%2) {
			$class = 'row1';
		} else {
			$class = 'row2';
		}
		
		$i += 1;
?>
  <tr class="<?php echo $class; ?>"> 
   <td><a href="index.php?view=detail&productId=<?php echo $pd_id; ?>"><?php echo $pd_name; ?></a></td>
   <td width="75" align="center"><img src="<?php echo $pd_thumbnail; ?>"></td>
   <td width="75" align="center"><a href="?catId=<?php echo $cat_id; ?>"><?php echo $cat_name; ?></a></td>
   <td width="70" align="center"><a href="javascript:modifyProduct(<?php echo $pd_id; ?>);">Modify</a></td>
   <td width="70" align="center"><a href="javascript:deleteProduct(<?php echo $pd_id; ?>, <?php echo $catId; ?>);">Delete</a></td>
  </tr>
  <?php
	} //สิ้นสุดลูป while
?>
  <tr> 
   <td colspan="5" align="center">
   <?php 
echo $pagingLink;	//แสดงลิงก์ เพื่อเปลี่ยนหน้า
   ?></td>
  </tr>
<?php	
} else {
?>
  <tr> 
   <td colspan="5" align="center">No Products Yet</td>
  </tr>
  <?php
}
?>
  <tr> 
   <td colspan="5">&nbsp;</td>
  </tr>
  <tr> 
   <td colspan="5" align="right"><input name="btnAddProduct" type="button" id="btnAddProduct" value="Add Product" class="box" onClick="addProduct(<?php echo $catId; ?>)"></td>
  </tr>
 </table>
 <p>&nbsp;</p>
</form>