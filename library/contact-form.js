function checkCustomerSubmitInfo()
{
	with (window.document.frmContact) {
		if (isEmpty(txtTitle, 'กรุณากรอกหัวข้อ')) {
			txtTitle.focus();
			return false;
		} else if (isEmpty(txtUserFirstName, 'กรุณากรอกชื่อ')) {
			txtUserFirstName.focus();
			return false;
		} else if (isEmpty(txtUserEmail, 'กรุณากรอกอีเมล Email')) {
			txtUserEmail.focus();
			return false;
		} else if (validateEmail(txtUserEmail.value, 'คุณกรอกอีเมล์ไม่ถูกต้อง')==false){
			txtUserEmail.focus();
			return false;
		} else if (isEmpty(txtData, 'กรุณากรอกข้อคิดเห็น หรือข้อเสนอแนะ')) {
			txtData.focus();
			return false;
		} else if (isEmpty(captcha, 'กรุณาใส่รหัสที่เห็นในภาพ')) {
			captcha.focus();
			return false;
		} else {
			return true;
		}
	}
}
