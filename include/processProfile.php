<?php
require_once '../library/config.php';
require_once '../library/customer-functions.php';
require_once '../library/common.php';
require_once 'header.php';


if(!isset($_SESSION['plaincart_customer_id'])){
	header('Location: ' . WEB_ROOT.'login.php');
	exit();
}

$currentKey = md5($_SESSION['plaincart_customer_id'].SECRET_KEY);
$hashKey = (isset($_GET['profileKey']) && $_GET['profileKey'] != '') ? $_GET['profileKey'] : '';

if($currentKey!=$hashKey){
	header('Location: ' . WEB_ROOT);
	exit();
}

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';
switch ($action) {

	case 'edit' :
		editProfile();
		break;
	case 'pass' :
		changePassword();
		break;
	default :
		header("Location: ".WEB_ROOT);	
}

?>

<?php
require_once 'footer.php';
?>

<?php

function editProfile()
{
	include_once 'editProfile.php';
}

function changePassword()
{
	include_once 'changePassword.php';
}

?>