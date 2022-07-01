<?php

/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkAdminUser()
{
	// ถ้าไม่มีการกำหนดค่า session id ก็จะ Redirect ไปยังหน้า Login อีกครั้ง
	if (!isset($_SESSION['plaincart_user_id'])) {
		header('Location: ' . WEB_ROOT . 'admin/login.php');
		exit;
	}
	
	// ถ้าผู้ใช้ต้องการ logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

/*
   admin login
*/
function doLogin()
{
	// ถ้าพบ error ก็จะถูกเซฟลงใน array() ที่ชื่อว่า $errorMessage
	$errorMessage = '';
	
	//รับค่า Username และ Password มาจากแบบฟอร์มล็อกอิน
	$userName = $_POST['txtUserName'];
	$password = $_POST['txtPassword'];
	//เข้ารหัส password ด้วยฟังก์ชัน md5() 
	$hashPassword = md5($password.SECRET_KEY);
	
	// ประการแรกตรวจสอบให้แน่ใจว่า username & password ไม่เป็นอะไรที่ว่างๆ
	if ($userName == '') {
		$errorMessage = 'You must enter your username';
	} else if ($password == '') {
		$errorMessage = 'You must enter the password';
	} else {
		//ตรวจสอบฐานข้อมูลดูว่า username และ password ถูกต้องตรงกันหรือไม่ 
		//และต้องมีฐานะเป็น admin โดยดูจาก user_role= 'admin'
		$sql = "SELECT user_id
		        FROM tbl_user 
				WHERE user_name = '$userName' AND user_password = '$hashPassword' AND user_role = 'admin' ";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$_SESSION['plaincart_user_id'] = $row['user_id'];
			
			// อัพเดท เวลาล็อคอิน ว่าได้มีการล็อคอินครั้งสุดท้ายเมื่อใด
			$sql = "UPDATE tbl_user 
			        SET user_last_login = NOW() 
					WHERE user_id = '{$row['user_id']}'";
			dbQuery($sql);

			// ถ้าผู้ใช้ที่ล็อคอินในปัจจุปัน ถูกยืนยันชื่อและรหัสผ่านถูกต้อง ก็จะไปยังหน้าถัดไป
			// ถ้าเคยเข้ามามายังส่วนของ Admin แล้ว
			// ให้ไปยังเว็บเพจหน้าสุดท้ายที่เคยเยี่ยมชม
			if (isset($_SESSION['login_return_url'])) {
				header('Location: ' . $_SESSION['login_return_url']);
				exit;
			} else {
				header('Location: index.php');
				exit;
			}
		} else {
			$errorMessage = 'Wrong username or password or don\'t have permission';
		}		
			
	}
	
	return $errorMessage;
}

/*
	Logout a user admin
*/
function doLogout()
{
	if (isset($_SESSION['plaincart_user_id'])) {
		unset($_SESSION['plaincart_user_id']);	//ล้างค่าออกจากตัวแปร $_SESSION
	}
		
	//กลับไปยังหน้าล็อกอินอีกครั้ง	
	header('Location: login.php');
	exit;
}


/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/

function buildCategoryOptions($catId = 0)
{
	//เลือกประเภทสินค้ามาจากฐานข้อมูล
	$sql = "SELECT cat_id, cat_parent_id, cat_name
			FROM tbl_category
			ORDER BY cat_id";
	$result = dbQuery($sql) or die('Cannot get Product. ' . mysql_error());
	
	$categories = array();		//สร้างตัวแปรแบบ array เก็บผลลัพธ์
	while($row = dbFetchArray($result)) {
		list($id, $parentId, $name) = $row;
		
		if ($parentId == 0) {
			//สร้าง array สำหรับกำหนดประเภทสินค้าหลัก
			$categories[$id] = array('name' => $name, 'children' => array());
		} else {
			//ประเภทสินค้าย่อย จะถูกกำหนดลงใน array ของประเภทสินค้าหลัก 
			$categories[$parentId]['children'][] = array('id' => $id, 'name' => $name);	
		}
	}	
	
	//สร้าง Dropdown list เพื่อแสดงรายการประเภทสินค้า
	$list = '';
	foreach ($categories as $key => $value) {	//วนลูปเข้าไปดูในประเภทสินค้าหลัก
		$name     = $value['name'];
		$children = $value['children'];
		
		$list .= "<option value=\"$key\"";
		if ($key == $catId) {
			$list.= " selected";
		}
			
		$list .= ">$name</option>\r\n";

		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}
			
			$list .= ">&nbsp;&nbsp;{$child['name']}</option>\r\n";
		}
	}
	
	return $list;
}


/*
	Create a thumbnail of $srcFile and save it to $destFile.
	The thumbnail will be $width pixels.
*/
function createThumbnail($srcFile, $destFile, $width, $quality = 75)
{
	$thumbnail = '';
	
	if (file_exists($srcFile)  && isset($destFile))
	{
		$size        = getimagesize($srcFile);
		$w           = number_format($width, 0, ',', '');
		$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');
		
		$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
	}
	
	//หากสร้าง thumbnail สำหรับ ก็จะ return ไฟล์ thumbnail แต่ถ้าไม่สำเร็จ ก็จะ Return ค่าว่าง 
	return basename($thumbnail);
}

/*
	Copy an image to a destination file. The destination
	image size will be $w X $h pixels
*/
function copyImage($srcFile, $destFile, $w, $h, $quality = 75)
{
    $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);

    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
    {
       $destFile  = substr_replace($destFile, 'jpg', -3);
       $dest      = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
       $dest = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } else {
      return false;
    }

    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
    return $destFile;

}

/*
	Create the paging links
*/
function getPagingNav($sql, $pageNum, $rowsPerPage, $queryString = '')
{
	$result  = mysql_query($sql) or die('Error, query failed. ' . mysql_error());
	$row     = mysql_fetch_array($result, MYSQL_ASSOC);
	$numrows = $row['numrows'];
	
	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);
	
	$self = $_SERVER['PHP_SELF'];
	
	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link
	
	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?page=$page{$queryString}\">[Prev]</a> ";
	
		$first = " <a href=\"$self?page=1{$queryString}\">[First Page]</a> ";
	}
	else
	{
		$prev  = ' [Prev] ';       // we're on page one, don't enable 'previous' link
		$first = ' [First Page] '; // nor 'first page' link
	}
	
	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?page=$page{$queryString}\">[Next]</a> ";
	
		$last = " <a href=\"$self?page=$maxPage{$queryString}{$queryString}\">[Last Page]</a> ";
	}
	else
	{
		$next = ' [Next] ';      // we're on the last page, don't enable 'next' link
		$last = ' [Last Page] '; // nor 'last page' link
	}
	
	// return the page navigation link
	return $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last; 
}
?>