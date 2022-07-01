<?php
require_once '../../library/config.php';
require_once '../library/functions.php';

checkAdminUser();			//ตรวจสอบว่า admin ได้ล็อคอิน

$action = isset($_GET['action']) ? $_GET['action'] : '';

//ตรวจสอบค่า action ที่ได้รับเข้ามา
switch ($action) {
	
	case 'add' :		//กรณีเพิ่ม User
		addUser();
		break;
		
	case 'modify' :		//กรณีแก้ไข User
		modifyUser();
		break;
		
	case 'delete' :		//กรณีลบ User
		deleteUser();
		break;
    

	default :
		//ถ้าไม่ตรงเงื่อนไขข้างบน ให้ไปยังหน้า index ของ admin
		header('Location: index.php');
}


function addUser()
{
    $userName = $_POST['txtUserName'];
	$password = $_POST['txtPassword'];
	$hashPassword = md5($password.SECRET_KEY);
	$role = $_POST['cboUserRole'];

	// ตรวจสอบว่า User นี้มีอยู่แล้วหรือไม่
		$sql = "SELECT user_name
	        FROM tbl_user
			WHERE user_name = '$userName'";
		$result = dbQuery($sql);
	
		//ถ้าพบ Username ชื่อเดียวกัน แสดงว่ามี user นี้อยู่แล้ว
		if (dbNumRows($result) == 1) {
			header('Location: index.php?view=add&error=' . urlencode('Username มีอยู่แล้ว กรุณาเลือกชื่ออื่น'));	
		} else if(checkPassword($password)==false) {
		    header('Location: index.php?view=add&error=' . urlencode('!ผิดพลาด รหัสผ่านต้องมีทั้งอักษรและตัวเลข และยาวอย่างน้อย 6 ตัวอักษร'));
	    } else {		
			$sql   = "INSERT INTO tbl_user (user_name, user_password, user_regdate, user_role)
		          VALUES ('$userName', '$hashPassword', NOW(), '$role')";
	
			dbQuery($sql);
			header('Location: index.php');	
	    
	    }
 
}

/*
	แก้ไข User
*/
function modifyUser()
{
	$userId   = (int)$_POST['hidUserId'];	
	$password = $_POST['txtPassword'];
	$hashPassword = md5($password.SECRET_KEY);
	$role = $_POST['cboUserRole'];
	
	//ตรวจสอบรหัสผ่าน ว่าถูกต้องตามเงื่อนไขหรือไม่ ซึ่งต้องเป็นอักษรผสมตัวเลข 
	//และมีความยาวอย่างน้อย 6 ตัวอักษร
	if(checkPassword($password)) {
	
	$sql   = "UPDATE tbl_user 
	          SET user_password = '$hashPassword', user_role = '$role'
			  WHERE user_id = $userId";

	dbQuery($sql);
	header('Location: index.php');
	} else {
		header('Location: index.php?error=' . urlencode('!ผิดพลาด รหัสผ่านต้องมีทั้งอักษรและตัวเลขและยาวอย่างน้อย 6 ตัวอักษร'));
	}	

}

/*
	ลบ  User 
*/
function deleteUser()
{
	if (isset($_GET['userId']) && (int)$_GET['userId'] > 0) {
		$userId = (int)$_GET['userId'];
	} else {
		header('Location: index.php');
	}
	
	//ลบ User ออกจากตารางโดยอ้างอิงจาก user_id
	$sql = "DELETE FROM tbl_user 
	        WHERE user_id = $userId";
	dbQuery($sql);
	
	header('Location: index.php');
}


//ตรวจสอบรหัสผ่าน  โดยจะต้องมีความยาวมากกว่า 6 ตัวอักษร และต้องมีทั้งอักษรและตัวเลขผสมกัน
function checkPassword($password)
{
	if(strlen($password) < 6 || !preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/i', $password)) {
	   return false;
	} else {
	   return true;
	}
}
?>