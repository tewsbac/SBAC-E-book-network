<?php
if (!defined('WEB_ROOT')) {
	exit;
}

//ตรวจสอบว่ามีการส่งค่า userId มาหรือไม่ ถ้าไม่มีให้กับไปยัง index.php
if (isset($_GET['userId']) && (int)$_GET['userId'] > 0) {
	$userId = (int)$_GET['userId'];
} else {
	header('Location: index.php');
}
//ถ้ามีการส่ง error มา ให้เก็บค่า error นั้นเอาไว้
$errorMessage = (isset($_GET['error']) && $_GET['error'] != '') ? $_GET['error'] : '&nbsp;';
//SQL สำหรับการเลือก User ออกมาจากฐานข้อมูล
$sql = "SELECT user_name, user_role, user_password
        FROM tbl_user
        WHERE user_id = $userId";
$result = dbQuery($sql);		
extract(dbFetchAssoc($result));


//กำหนดระดับของ User
$userRole = array('customer', 'guest', 'admin');
$userOption = '';		//นำเอาระดับของ User มาใส่ใน drop-down
foreach ($userRole as $role) {
	$userOption .= '<option value="'.$role.'"';
	
	if ($role == $user_role) {
		$userOption .= ' selected';
	}
	
	$userOption .= '>'.$role.'</option>\r\n';
}

?> 
<p class="errorMessage"><?php echo $errorMessage; ?></p>
<form action="processUser.php?action=modify" method="post" enctype="multipart/form-data" name="frmPassword" id="frmPassword" onsubmit="return checkPassword()">
 <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="entryTable">
  <tr> 
   <td width="150" class="label">User Name</td>
   <td class="content"><input name="txtUserName" type="text" class="box" id="txtUserName" value="<?php echo $user_name; ?>" size="20" maxlength="20" disabled="true">
    <input name="hidUserId" type="hidden" id="hidUserId" value="<?php echo $userId; ?>"> </td>
  </tr>
  <tr> 
   <td width="150" class="label">Password</td>
   <td class="content"> <input name="txtPassword" type="password" class="box" id="txtPassword" size="20" maxlength="20" value="<?php echo $user_password; ?>"></td>
  </tr>
    <tr> 
   <td width="150" class="label">Confirm Password</td>
   <td class="content"> <input name="txtConfirmPassword" type="password" class="box" id="txtConfirmPassword" size="20" maxlength="20" value="<?php echo $user_password; ?>"></td>
  </tr>
  <tr align="center"> 
  	<td width="150" class="label">User Role</td>
  	<td class="content">
  		<select name="cboUserRole" class="box" id="cboUserRole">
    		<?php echo $userOption; ?>
  		</select>
  	</td>
  </tr>
  
 </table>
 <p align="center"> 
  <input name="btnModifyUser" type="submit" id="btnModifyUser" value="แก้ไข" class="box">
  &nbsp;&nbsp;<input name="btnCancel" type="button" id="btnCancel" value="Cancel" onClick="window.location.href='index.php';" class="box">  
 </p>
</form>