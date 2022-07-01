<?php
require_once '../library/config.php';	//เชื่อมไปยังไฟล์ config.php สำหรับเชื่อมต่อฐานข้อมูล
require_once './library/functions.php';	//เชื่อมไปยังไฟล์ functions.php ซึ่งมีฟังก์ชันที่จำเป็นต้องใช้

checkAdminUser();	//ตรวจสอบว่าล็อคอินแล้วหรือไม่

$content = 'main.php';	//กำหนดค่าให้กับตัวแปร $content ซึ่งเป็นเนื้อหาจะไปแสดงบน ไฟล์ template.php

$pageTitle = 'Shop Admin';	//กำหนด Title ซึ่งแสดงบนแท็บของบราวเซอร์
$script = array();		//กำหนดตัวแปร $script ซึ่งเป็น array สำหรับเก็บ JavaScript

require_once 'include/template.php';	//เชื่อมไปยังไฟล์ template.php 
?>
