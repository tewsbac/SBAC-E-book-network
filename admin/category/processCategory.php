<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

checkAdminUser();	//ตรวจสอบว่าล็อคอินอยู่หรือไม่

$action = isset($_GET['action']) ? $_GET['action'] : '';
//ตรวจสอบ action ว่าต้องการให้ทำอะไร
switch ($action) {
	
    case 'add' :					//เพิ่มประเภทสินค้า
        addCategory();
        break;
      
    case 'modify' :				//แก้ไขประเภทสินค้า
        modifyCategory();
        break;
        
    case 'delete' :				//ลบประเภทสินค้า
        deleteCategory();
        break;
    
    case 'deleteImage' :				//ลบรูปภาพ
        deleteImage();
        break;
    
	   
    default :
        // if action is not defined or unknown
        // move to main category page
        header('Location: index.php');
}


/*
    เพิ่มประเภทสินค้า
*/
function addCategory()
{
    $name        = $_POST['txtName'];
    $description = $_POST['mtxDescription'];
    $image       = $_FILES['fleImage'];
    $parentId    = $_POST['hidParentId'];
    
    $catImage = uploadImage('fleImage', SRV_ROOT . 'images/category/');
    
    $sql   = "INSERT INTO tbl_category (cat_parent_id, cat_name, cat_description, cat_image) 
              VALUES ($parentId, '$name', '$description', '$catImage')";
    $result = dbQuery($sql) or die('Cannot add category' . mysql_error());
    
    header('Location: index.php?catId=' . $parentId);              
}

/*
    อัพโหลดไฟล์ภาพ และได้ชื่อของไฟล์ภาพที่อัพโหลดกลับมา 
*/
function uploadImage($inputName, $uploadDir)
{
    $image     = $_FILES[$inputName];
    $imagePath = '';
    
    // ถ้าไฟล์ถูกอัพโหลด
    if (trim($image['tmp_name']) != '') {
        // ตรวจดูนามสกุลไฟล์
        $ext = substr(strrchr($image['name'], "."), 1); 

        // กำหนดชื่อไฟล์ขึ้นมาใหม่ เพื่อป้องกันไม่ให้ซ้ำกับของเดิม
        $imagePath = md5(rand() * time()) . ".$ext";
        
		// ตรวจสอบขนาดความกว้างของภาพไม่ให้เกินที่กำหนด
		// ทำการปรับขนาดภาพ
		$size = getimagesize($image['tmp_name']);
		
		if ($size[0] > MAX_CATEGORY_IMAGE_WIDTH) {
			$imagePath = createThumbnail($image['tmp_name'], $uploadDir . $imagePath, MAX_CATEGORY_IMAGE_WIDTH);
		} else {
			// move the image to category image directory
			// if fail set $imagePath to empty string
			if (!move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath)) {
				$imagePath = '';
			}
		}	
    }

    
    return $imagePath;
}

/*
    แก้ไขประเภทสินค้า
*/
function modifyCategory()
{
    $catId       = (int)$_GET['catId'];
    $name        = $_POST['txtName'];
    $description = $_POST['mtxDescription'];
    $image       = $_FILES['fleImage'];
    
    $catImage = uploadImage('fleImage', SRV_ROOT . 'images/category/');
    
    // ถ้าอัพโหลดไฟล์ภาพใหม่ ให้ลบไฟล์ภาพเดิมออก
    if ($catImage != '') {
        _deleteImage($catId);
		$catImage = "'$catImage'";
    } else {
		// กำหนดรูปภาพให้กับประเภทสินค้า หากได้กำหนดรูปภาพเอาไว้แล้ว
		$catImage = 'cat_image';
	}
     
    $sql    = "UPDATE tbl_category 
               SET cat_name = '$name', cat_description = '$description', cat_image = $catImage
               WHERE cat_id = $catId";
           
    $result = dbQuery($sql) or die('Cannot update category. ' . mysql_error());
    header('Location: index.php');              
}

/*
    ลบประเภทสินค้า
*/
function deleteCategory()
{
    if (isset($_GET['catId']) && (int)$_GET['catId'] > 0) {
        $catId = (int)$_GET['catId'];
    } else {
        header('Location: index.php');
    }
    
	// ค้นหาประเภทสินค้าย่อยที่อยู่ภายใต้ประเภทสินค้านี้
	$children = getChildren($catId);
	
	//สร้าง array() ขึ้นมาเก็บประเภทสินค้า และประเภทสินค้าย่อยๆ ที่อยู่ภายใต้ประเภทสินค้านี้ 
	$categories  = array_merge($children, array($catId));
	$numCategory = count($categories);

	// ลบรูปทั้งหมดตลอดจนลบ Thumbnail ด้วย ถ้าพบว่ามีประเภทสินค้าในตัวแปร $categories
	$sql = "SELECT pd_id, pd_image, pd_thumbnail
	        FROM tbl_product
			WHERE cat_id IN (" . implode(',', $categories) . ")";
	$result = dbQuery($sql);
	
	while ($row = dbFetchAssoc($result)) {
		@unlink(SRV_ROOT . PRODUCT_IMAGE_DIR . $row['pd_image']);	
		@unlink(SRV_ROOT . PRODUCT_IMAGE_DIR . $row['pd_thumbnail']);
	}
	
	// ลบสินค้าที่อยู่ภายในประเภทสินค้า
	$sql = "DELETE FROM tbl_product
			WHERE cat_id IN (" . implode(',', $categories) . ")";
	dbQuery($sql);
	
	// ลบรูปภาพที่ใช้กับประเภทสินค้า
	_deleteImage($categories);

  	// สุดท้ายลบประเภทสินค้าออกจากฐานข้อมูล
    $sql = "DELETE FROM tbl_category 
            WHERE cat_id IN (" . implode(',', $categories) . ")";
    dbQuery($sql);
    
    header('Location: index.php');
}


/*
	ค้นหาประเภทสินค้าย่อยๆ ที่อยู่ภายใต้ประเภทสินค้าที่มีรหัสเดียวกับตัวแปร  $catId
*/
function getChildren($catId)
{
    $sql = "SELECT cat_id ".
           "FROM tbl_category ".
           "WHERE cat_parent_id = $catId ";
    $result = dbQuery($sql);
    
	$cat = array();
	if (dbNumRows($result) > 0) {
		while ($row = dbFetchRow($result)) {
			$cat[] = $row[0];
			
			// เรียกฟังก์ชันตัวเองซ้ำเพื่อหาประเภทสินค้าย่อยๆ ลงไปอีก
			$cat  = array_merge($cat, getChildren($row[0]));
		}
    }

    return $cat;
}


/*
    ลบรูปประเภทสินค้า
*/
function deleteImage()
{
    if (isset($_GET['catId']) && (int)$_GET['catId'] > 0) {
        $catId = (int)$_GET['catId'];
    } else {
        header('Location: index.php');
    }
    
	_deleteImage($catId);
	
	// อัพเดทรูปภาพในฐานข้อมูล
	$sql = "UPDATE tbl_category
			SET cat_image = ''
			WHERE cat_id = $catId";
	dbQuery($sql);        

    header("Location: index.php?view=modify&catId=$catId");
}

/*
	ลบรูปของประเภทสินค้า เมื่อ category = $catId
*/
function _deleteImage($catId)
{
	// จะแสดงสถานะ เมื่อลบรูปสำเร็จแล้ว
    $deleted = false;

	// คิวรี่รูปจากฐานข้อมูล
    $sql = "SELECT cat_image 
            FROM tbl_category
            WHERE cat_id ";
	
	if (is_array($catId)) {
		$sql .= " IN (" . implode(',', $catId) . ")";
	} else {
		$sql .= " = $catId";
	}	

    $result = dbQuery($sql);
    
    if (dbNumRows($result)) {
        while ($row = dbFetchAssoc($result)) {
	        // ลบไฟล์
    	    $deleted = @unlink(SRV_ROOT . CATEGORY_IMAGE_DIR . $row['cat_image']);
		}	
    }
    return $deleted;
}
?>