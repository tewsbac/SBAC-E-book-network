<?php
require_once 'library/config.php';
require_once 'library/customer-functions.php';


$errorMessage = '&nbsp;';

if(isset($_SESSION['plaincart_customer_id'])){
	header('Location: ' . WEB_ROOT);
}
//ล็อคอินตามปกติ
if (isset($_POST['txtUserName'])) {
	$result = doCustomerLogin();
	
	if ($result != '') {
		$errorMessage = $result;
	}
}

//ถ้าล็อคอินจากหน้าเช็คเอาท์ ให้กลับไปหน้าเดิม
if(isset($_GET['cartURL']) && isset($_POST['txtUserName'])){
	$result = doCustomerLogin();
	if ($result != '') {
		setError($result);
	}
	header('Location: '. WEB_ROOT.'checkout.php?step=1');
}

//ตรวจสอบ captcha
if(isset($_POST['txtUserName']) && isset($_POST['secretCode'])){
    if(md5($_POST['captcha']) == $_SESSION['captchaKey']){
    	//กรอกรหัสถูกต้อง	
    	doCustomerAddToDatabase();			//เพิ่มลงฐานข้อมูล
    	/*
    	if(isset($_SESSION['txtUserName'])){
    	 	unset($_SESSION['txtUserName']);
    	 	unset($_SESSION['txtUserEmail']);
    	}
    	*/
    }else {
    	//กรอกรหัสไม่ถูกต้องกรอกใหม่
    	//เก็บค่าที่เคยกรอกเอาไว้ครั้งก่อนเอาไว้ด้วย
    	$_SESSION['txtUserName'] = $_POST['txtUserName'];
		$_SESSION['txtUserEmail'] = $_POST['txtUserEmail'];
		$_SESSION['txtUserFirstName'] = $_POST['txtUserFirstName'];
		$_SESSION['txtUserLastName'] = $_POST['txtUserLastName'];
		$_SESSION['txtUserAddress'] = $_POST['txtUserAddress'].' '.$_POST['txtUserAddress2'];
		$_SESSION['txtUserPhone'] = $_POST['txtUserPhone'];
		$_SESSION['txtUserCity'] = $_POST['txtUserCity'];
		$_SESSION['txtUserState'] = $_POST['txtUserState'];
		$_SESSION['txtUserPostalCode'] = $_POST['txtUserPostalCode'];
		
		header('Location: register.php?error=' . urlencode('!ผิดพลาด กรอกรหัส Captcha ไม่ถูกต้อง'));
    }
    unset($_SESSION['key']);
} 
$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';
switch ($action) {
	case 'register' :
		$pageTitle 	= 'Customer - register';
		customerWantRegister();
		break;
	case 'logout' :
		$pageTitle 	= 'Customer - logout';
		doCustomerLogout();
		break;
	default :		
		$pageTitle 	= 'Customer - Login';
}


require_once 'include/header.php';
?>
<p>&nbsp;</p>
<?php
if($errorMessage != '&nbsp;'){
?>

<div style="text-align:center;">
	<div id="errorMessage" class="alert alert-danger" style="width:30%;margin-left:auto;margin-right:auto;"><?php echo $errorMessage; ?></div>
</div>
<?php
}
?>
<div class="panel panel-info" style="width:30%;margin-left:auto;margin-right:auto;">
  <div class="panel-heading">ล็อกอินเข้าสู่ระบบ</div>
  <div class="panel-body">


<form method="post" name="frmLogin" id="frmLogin">
<table width="350" border="0" align="center" bgcolor="#336699" class="table">
     

        <tr class=""> 
            <td width="100" align="right">Username</td>
            <td width="10" align="center">:</td>
            <td><input name="txtUserName" type="text" class="box" id="txtUserName" value="admin" size="10" maxlength="20"></td>
        </tr>
        <tr> 
            <td width="100" align="right">รหัสผ่าน</td>
            <td width="10" align="center">:</td>
            <td><input name="txtUserPassword" type="password" class="box" id="txtUserPassword" value="admin" size="10"></td>
        </tr>
        <tr> 
            <td colspan="2">&nbsp;</td>
            <td><input name="btnLogin" type="submit" class="btn btn-primary btn-sm" id="btnLogin" value="เข้าสู่ระบบ" onClick="login.php?action=login';">
            &nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="ยกเลิก" onClick="window.location.href='index.php';" class="btn btn-primary btn-sm"></td>
         </tr>
      
 </table>
 </form>
 
 </div>
</div>


<?php
require_once 'include/footer.php';
?>
