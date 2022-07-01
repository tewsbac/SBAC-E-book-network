// JavaScript Document
function checkAddUserForm()
{
	with (window.document.frmAddUser) {
		if (isEmpty(txtUserName, 'Enter user name')) {
			return;
		} else if (isEmpty(txtPassword, 'Enter password')) {
			return;
		} else if (isEmpty(txtConfirmPassword, 'Reenter password')){
			return;
		} else if (txtPassword.value != txtConfirmPassword.value){
			alert('password miss match');
			return;
		} else {
			submit();
		}
	}
}

function checkPassword()
{
	theForm = window.document.frmPassword;
	
	if (theForm.txtPassword.value == '') {
		alert('Enter new password');
		theForm.txtPassword.focus();
		return false;
	} else if (theForm.txtConfirmPassword.value == '') {
		alert('Repeat new password');
		theForm.txtConfirmPassword.focus();
		return false;
	} else if (theForm.txtPassword.value != theForm.txtConfirmPassword.value) {
		alert('New password don\'t match');
		theForm.txtConfirmPassword.focus();
		return false;
	} else {
		return true;
	}
}

function addUser()
{
	window.location.href = 'index.php?view=add';
}

function changePassword(userId)
{
	window.location.href = 'index.php?view=modify&userId=' + userId;
}

function deleteUser(userId)
{
	if (confirm('Delete this user?')) {
		window.location.href = 'processUser.php?action=delete&userId=' + userId;
	}
}


