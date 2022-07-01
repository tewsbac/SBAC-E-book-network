function checkRegisterInfo()
{
	with (window.document.frmAddUser) {
		if (isEmpty(txtUserName, 'กรุณากรอก userName')) {
			txtUserName.focus();
			return false;
		} else if (isEmpty(txtUserPassword, 'กรุณากรอกรหัสผ่าน')) {
			txtUserPassword.focus();
			return false;
		} else if (isEmpty(txtUserConfirmPassword, 'กรุณายืนยันรหัสผ่าน')) {
			txtUserConfirmPassword.focus();
			return false;
		} else if (txtUserPassword.value != txtUserConfirmPassword.value) {
			alert('รหัสผ่านไม่ตรงกัน');
			txtUserConfirmPassword.focus();
			return false;
		} else if (isEmpty(txtUserEmail, 'กรุณากรอกอีเมล Email')) {
			txtUserEmail.focus();
			return false;
		} else if (validateEmail(txtUserEmail.value, 'คุณกรอกอีเมล์ไม่ถูกต้อง')==false){
			txtUserEmail.focus();
			return false;
		} else if (isEmpty(captcha, 'กรุณาใส่รหัสที่เห็นในภาพ')) {
			captcha.focus();
			return false;
		} else {
			return true;
		}
	}
}
