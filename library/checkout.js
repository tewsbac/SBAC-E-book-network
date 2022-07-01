function setPaymentInfo(isChecked)
{
	with (window.document.frmCheckout) {
		if (isChecked) {
			txtPaymentFirstName.value  = txtShippingFirstName.value;
			txtPaymentLastName.value   = txtShippingLastName.value;
			txtPaymentEmail.value      = txtShippingEmail.value;
			txtPaymentAddress1.value   = txtShippingAddress1.value;
			txtPaymentAddress2.value   = txtShippingAddress2.value;
			txtPaymentPhone.value      = txtShippingPhone.value;
			txtPaymentState.value      = txtShippingState.value;			
			txtPaymentCity.value       = txtShippingCity.value;
			txtPaymentPostalCode.value = txtShippingPostalCode.value;
			
			txtPaymentFirstName.readOnly  = true;
			txtPaymentLastName.readOnly   = true;
			txtPaymentEmail.readOnly      = true;
			txtPaymentAddress1.readOnly   = true;
			txtPaymentAddress2.readOnly   = true;
			txtPaymentPhone.readOnly      = true;
			txtPaymentState.readOnly      = true;			
			txtPaymentCity.readOnly       = true;
			txtPaymentPostalCode.readOnly = true;			
		} else {
			txtPaymentFirstName.readOnly  = false;
			txtPaymentLastName.readOnly   = false;
			txtPaymentEmail.readOnly      = false;
			txtPaymentAddress1.readOnly   = false;
			txtPaymentAddress2.readOnly   = false;
			txtPaymentPhone.readOnly      = false;
			txtPaymentState.readOnly      = false;			
			txtPaymentCity.readOnly       = false;
			txtPaymentPostalCode.readOnly = false;			
		}
	}
}


function checkShippingAndPaymentInfo()
{
	with (window.document.frmCheckout) {
		if (isEmpty(txtShippingFirstName, 'กรุณากรอกชื่อ')) {
			txtShippingFirstName.focus();
			return false;
		} else if (isEmpty(txtShippingLastName, 'กรุณากรอกนามสกุล')) {
			txtShippingLastName.focus();
			return false;
		} else if (isEmpty(txtShippingEmail, 'กรุณากรอกอีเมล Email ของผู้รับสินค้า')) {
			txtShippingEmail.focus();
			return false;
		} else if (validateEmail(txtShippingEmail.value, 'คุณกรอกอีเมล์ของผู้รับสินค้าไม่ถูกต้อง')==false){
			txtShippingEmail.focus();
			return false;
		} else if (isEmpty(txtShippingAddress1, 'กรุณากรอกที่อยู่ผู้รับสินค้า')) {
			txtShippingAddress1.focus();
			return false;
		} else if (isEmpty(txtShippingPhone, 'กรุณากรอกกรอกเบอร์ศัพท์')) {
			txtShippingPhone.focus();
			return false;
		} else if(validatePhone(txtShippingPhone.value, 'คุณกรอกเบอร์โทรศัพท์ไม่ถูกต้อง')==false){
			txtShippingPhone.focus();
			return false;
		} else if (isEmpty(txtShippingState, 'กรุณากรอก เขต/อำเภอ')) {
			txtShippingState.focus();
			return false;
		} else if (isEmpty(txtShippingCity, 'กรุณากรอกจังหวัด')) {
			txtShippingCity.focus();
			return false;
		} else if (isEmpty(txtShippingPostalCode, 'กรุณากรอกรหัสไปรษณีย์')) {
			txtShippingPostalCode.focus();
			return false;
		} else if (validateZipCode(txtShippingPostalCode.value, 'คุณกรอกรหัสไปรษณีย์ไม่ถูกต้อง')==false) {
			txtShippingPostalCode.focus();
			return false;
		} else if (isEmpty(txtPaymentFirstName, 'กรุณากรอกชื่อ')) {
			txtPaymentFirstName.focus();
			return false;
		} else if (isEmpty(txtPaymentLastName, 'กรุณากรอกนามสกุล')) {
			txtPaymentLastName.focus();
			return false;
		} else if (isEmpty(txtPaymentEmail, 'กรุณากรอก Email')) {
			txtPaymentEmail.focus();
			return false;
		} else if (validateEmail(txtPaymentEmail.value, 'คุณกรอกอีเมล์ของผู้ชำระค่าสินค้าไม่ถูกต้อง')==false){
			txtPaymentEmail.focus();
			return false;
		} else if (isEmpty(txtPaymentAddress1, 'กรุณากรอกที่อยู่ของผู้สั่งสินค้า')) {
			txtPaymentAddress1.focus();
			return false;
		} else if (isEmpty(txtPaymentPhone, 'กรุณากรอกเบอร์โทรศัพท์ของผู้สั่งสินค้า')) {
			txtPaymentPhone.focus();
			return false;
		} else if(validatePhone(txtPaymentPhone.value, 'คุณกรอกเบอร์โทรศัพท์ไม่ถูกต้อง')==false){
			txtPaymentPhone.focus();
			return false;
		} else if (isEmpty(txtPaymentState, 'กรุณากรอก เขต/อำเภอ')) {
			txtPaymentState.focus();
			return false;
		} else if (isEmpty(txtPaymentCity, 'กรุณากรอกจังหวัด')) {
			txtPaymentCity.focus();
			return false;
		} else if (isEmpty(txtPaymentPostalCode, 'กรุณากรอกรหัสไปรษณีย์ของผู้สั่งสินค้า')) {
			txtPaymentPostalCode.focus();
			return false;
		} else if (validateZipCode(txtPaymentPostalCode.value, 'คุณกรอกรหัสไปรษณีย์ไม่ถูกต้อง')==false) {
			txtPaymentPostalCode.focus();
			return false;
		} else {
			return true;
		}
	}
}
