function checkCustomerSubmitInfo()
{
	with (window.document.frmPaid) {
		if (isEmpty(txtUserFirstName, 'กรุณากรอกชื่อ')) {
			txtUserFirstName.focus();
			return false;
		} else if (isEmpty(txtUserEmail, 'กรุณากรอกอีเมล Email')) {
			txtUserEmail.focus();
			return false;
		} else if (validateEmail(txtUserEmail.value, 'คุณกรอกอีเมล์ไม่ถูกต้อง')==false){
			txtUserEmail.focus();
			return false;
		} else if (isEmpty(txtShopBank, 'กรุณากรอกธนาคารที่ท่านโอนเงินเข้ามา')) {
			txtShopBank.focus();
			return false;
		} else if (isEmpty(txtUserPrice, 'กรุณากรอกจำนวนเงินที่โอนเข้ามา')) {
			txtUserPrice.focus();
			return false;
		} else if (isEmpty(captcha, 'กรุณาใส่รหัสที่เห็นในภาพ')) {
			captcha.focus();
			return false;
		} else {
			return true;
		}
	}
}
