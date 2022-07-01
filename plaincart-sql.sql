--
-- ฐานข้อมูล: `itbookonline`
--

CREATE DATABASE IF NOT EXISTS itbookonline;
USE itbookonline;

--
-- โครงสร้างตาราง `tbl_cart`
--

DROP TABLE IF EXISTS `tbl_cart`;
CREATE TABLE IF NOT EXISTS `tbl_cart` (
  `ct_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ct_qty` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `ct_session_id` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ct_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ct_id`),
  KEY `pd_id` (`pd_id`),
  KEY `ct_session_id` (`ct_session_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=314 ;

--
-- dump ตาราง `tbl_cart`
--

INSERT INTO `tbl_cart` VALUES(115, 41, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:16');
INSERT INTO `tbl_cart` VALUES(114, 36, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:12');
INSERT INTO `tbl_cart` VALUES(208, 23, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:45:32');
INSERT INTO `tbl_cart` VALUES(207, 41, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:45:30');
INSERT INTO `tbl_cart` VALUES(206, 43, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:45:29');
INSERT INTO `tbl_cart` VALUES(205, 40, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:45:26');
INSERT INTO `tbl_cart` VALUES(116, 42, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:18');
INSERT INTO `tbl_cart` VALUES(117, 40, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:19');
INSERT INTO `tbl_cart` VALUES(118, 37, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:22');
INSERT INTO `tbl_cart` VALUES(119, 28, 1, '43dd7d5b92cc0820c9dbf39042cf41fe', '2014-04-29 20:46:26');
INSERT INTO `tbl_cart` VALUES(204, 25, 4, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:45:17');
INSERT INTO `tbl_cart` VALUES(203, 37, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:44:59');
INSERT INTO `tbl_cart` VALUES(202, 27, 1, '15ca5767ad00d2e800e146d39784dcf1', '2014-05-01 13:42:55');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_category`
--

DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(11) NOT NULL DEFAULT '0',
  `cat_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cat_description` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cat_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`cat_id`),
  KEY `cat_parent_id` (`cat_parent_id`),
  KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

--
-- dump ตาราง `tbl_category`
--

INSERT INTO `tbl_category` VALUES(10, 0, 'เว็บไซต์/เว็บมาสเตอร์', 'หนังสือเกี่ยวกับการสร้างและพัฒนาเว็บไซต์', 'cc7bee88cbe2a78239fc371316a23d76.jpg');
INSERT INTO `tbl_category` VALUES(11, 0, 'Windows & Office', 'หนังสือเกี่ยวกับการใช้งานในสำนักงาน', '361f922d9e500868b22bef66668125c1.jpg');
INSERT INTO `tbl_category` VALUES(12, 10, 'พัฒนาเว็บไซต์', 'สร้างเว็บไซต์ด้วยการเขียนและพัฒนาโค้ด', '');
INSERT INTO `tbl_category` VALUES(13, 10, 'ระบบ CMS', 'สร้างเว็บไซต์ด้วยระบบสำเร็จรูปเช่น Joomla, Wordpress และ Drupal', '');
INSERT INTO `tbl_category` VALUES(14, 11, 'Microsoft Office', 'Microsoft Office โปรแกรมประจำสำนักงาน', '');
INSERT INTO `tbl_category` VALUES(15, 11, 'Open Office', 'โปรแกรมสำนักงานดาวน์โหลดฟรี', '');
INSERT INTO `tbl_category` VALUES(16, 0, 'CAD / Architect', 'โปรแกรมประเภท CAD/CAM', '39fadb3a4e7aa2497c44c14b4a9bb46f.jpg');
INSERT INTO `tbl_category` VALUES(18, 0, 'Mac OS / MacBook', 'คู่มือการใช้งานระบบปฏิบัติการและ Application ใน Mac', '3094d7ededdeab5efdcdfe80fa1e2eec.jpg');
INSERT INTO `tbl_category` VALUES(19, 0, 'Programming / Database', 'สร้างและพัฒนา Application และจัดการฐานข้อมูล', 'a35872b57e5ed1c9f2391ac8ac1d1149.jpg');
INSERT INTO `tbl_category` VALUES(20, 11, 'Windows', 'ระบบปฏิบัติการจาก Microsoft', '');
INSERT INTO `tbl_category` VALUES(24, 0, 'Network', 'ระบบเครือข่าย', '69b3ab3e84cea0155c5161452e83cf08.jpg');
INSERT INTO `tbl_category` VALUES(25, 0, 'Hardware / Technical', 'คอมพิวเตอร์ฮาร์ดแวร์ และอุปกรณ์ต่อพ่วงกับคอมพิวเตอร์', '770eb9a55b4c10fc67d8fdebf45153d9.jpg');
INSERT INTO `tbl_category` VALUES(26, 0, 'Mobile / SmartPhone / Tablet', 'อุปกรณ์ไร้สาย โทรศัพท์มือถือ และแท็บเล็ต', 'f0e8343a89a7e376f6a2a6a4838c0c7c.jpg');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_currency`
--

DROP TABLE IF EXISTS `tbl_currency`;
CREATE TABLE IF NOT EXISTS `tbl_currency` (
  `cy_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cy_code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cy_symbol` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`cy_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- dump ตาราง `tbl_currency`
--

INSERT INTO `tbl_currency` VALUES(1, 'EUR', '&#8364;');
INSERT INTO `tbl_currency` VALUES(2, 'GBP', '&pound;');
INSERT INTO `tbl_currency` VALUES(3, 'JPY', '&yen;');
INSERT INTO `tbl_currency` VALUES(4, 'USD', '$');
INSERT INTO `tbl_currency` VALUES(5, 'THB', '฿');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_order`
--

DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE IF NOT EXISTS `tbl_order` (
  `od_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `od_date` datetime DEFAULT NULL,
  `od_last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `od_status` enum('New','Paid','Shipped','Completed','Cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'New',
  `od_memo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_city` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_state` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_postal_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_shipping_cost` decimal(5,2) DEFAULT '0.00',
  `od_payment_first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_city` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_state` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `od_payment_postal_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`od_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1028 ;

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_order_item`
--

DROP TABLE IF EXISTS `tbl_order_item`;
CREATE TABLE IF NOT EXISTS `tbl_order_item` (
  `od_id` int(10) unsigned NOT NULL DEFAULT '0',
  `pd_id` int(10) unsigned NOT NULL DEFAULT '0',
  `od_qty` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`od_id`,`pd_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_product`
--

DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `pd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `pd_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pd_description` text COLLATE utf8_unicode_ci NOT NULL,
  `pd_price` decimal(9,2) NOT NULL DEFAULT '0.00',
  `pd_qty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pd_image` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pd_thumbnail` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pd_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pd_last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`pd_id`),
  KEY `cat_id` (`cat_id`),
  KEY `pd_name` (`pd_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- dump ตาราง `tbl_product`
--

INSERT INTO `tbl_product` VALUES(22, 11, 'Office 2010', 'คู่มือการใช้งาน Microsoft Office 2010', 279.00, 8, '5a11b46434665878525ece8f9bad103f.jpg', '00871d529fdfcbfa597ec12dbdfeec52.jpg', '2014-04-26 15:21:15', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(23, 14, 'Excel 2007', 'คู่มือการใช้งาน Excel 2007', 179.00, 0, 'accbd14325caa3041b7c96951f636595.jpg', '7bcb5d962d04909ae28cdcea874211a7.jpg', '2014-04-26 15:26:34', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(24, 14, 'Project 2010', 'บริหารจัดการโครงการด้วย Project 2010', 239.00, 294, '129a8c2fbfb47fe89ece1ba2c6b9f28d.jpg', '0cc2610f63a969ab05c333174ce0899d.jpg', '2014-04-26 15:27:15', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(25, 14, 'Excel 2013', 'คู่มือ Excel 2013 ฉบับล่าสุดปี 2013-2014', 179.00, 41, 'e3474a832c9fe840e6b3a7c36213a2c4.jpg', '3fffff0361dfdd729d0bd27cb8e5d2f9.jpg', '2014-04-26 15:28:13', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(26, 12, 'PHP กับ E-commerce', 'เรียนรู้วิธีสร้างเว็บ E-commerce โดยใช้ PHP, JavaScript, jQuery และ Ajax', 199.00, 0, 'dec18f534c0dbe62907a9cbf5d912b2d.jpg', '44f044ccdc0430ac91ced2d12fe8b290.jpg', '2014-04-27 04:17:06', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(27, 12, 'JavaScript และ DHTML', 'สร้างและพัฒนาเว็บไซต์แบบ Interactive ด้วย JavaScript และ DHTML', 199.00, 2, '709719052086e1952372d671cddb4b7c.jpg', '6748fd8e4fa234ce5afabecf8d517802.jpg', '2014-04-27 04:18:02', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(28, 12, 'Html5+JavaScript+CSS3', 'คู่มือเรียนรู้การสร้างและตกแต่งเว็บไซต์แบบ All In One ด้วย Html5+JavaScript+CSS3', 259.00, 59, '694172efa5f38b31ac28d3a47125d620.jpg', '226834c640fd7e71e29532f48f3d0646.jpg', '2014-04-27 04:19:10', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(29, 13, 'สร้างและพัฒนาเว็บไซต์กับ Joomla 2.5', 'สร้างเว็บไซต์กับ Joomla 2.5 พร้อมแนะนำวิธีวิธีใช้ Virtual Mart', 299.00, 0, '5ab613969da65dcca6223b8bef2e06ad.jpg', 'ee2a6acf11f321b7cf6dc1856ad05666.jpg', '2014-04-27 04:21:10', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(30, 16, 'AutoCad 2013', 'คู่มือ autoCad 2013 ฉบับสมบูรณ์', 299.00, 0, 'bfa7123fc51a4792fdbdddece4d99583.jpg', '797f25f75de3fd13e7513ca07371d423.jpg', '2014-04-27 04:22:04', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(31, 16, 'AutoCad ฉบับ Architecture', 'สร้างและออกแบบสถาปัตยกรรมด้วยการใช้ AutoCad', 199.00, 2, 'ed2df48784fa84a5a75e74d73a66ded4.jpg', '667f45a81f3644749f4138b81dfb1c38.jpg', '2014-04-27 04:22:53', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(32, 16, 'AutoCAD ฉบับ Mechanical', 'สร้างและออกแบบชิ้นงาน 3 มิติ โดยใช้ AutoCAD', 199.00, 1, '2e81e34f553b92b4e29e9d4edd15155e.jpg', '665513721760df589070f2e72a1d2e5a.jpg', '2014-04-27 04:23:59', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(33, 18, 'คำภีร์ Final Cut Pro X', 'เรียนรู้วิธีตัดต่อวิดีโอแบบมืออาชีพด้วย Final Cut Pro X', 319.00, 0, '66d7408e807480115c955ed3b387f9a8.jpg', 'ec41fc2fd8b633b7e573d5d3777c76aa.jpg', '2014-04-27 04:29:36', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(34, 19, 'Visual Basic 2010', 'เรียนรู้และพัฒนาโปรแกรมด้วยการใช้ Visual Basic 2010', 259.00, 5, '8ce2a00c205d82ef229cd09fb43f4498.jpg', 'f6b1da184c2e40d9ea9690cf4c3c7b2a.jpg', '2014-04-27 04:30:53', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(35, 19, 'Visual C#', 'สร้างและพัฒนา Application โดยหลักการของ OOP ด้วย Visual C#', 259.00, 5, 'c3cefccb9c0ab1e1cccab56fe75923d1.jpg', '6fd2ab5cbc3db5ac91d4211a3215157a.jpg', '2014-04-27 04:33:27', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(36, 19, 'JAVA', 'พัฒนา Application ฉบับสมบูรณ์กับ JAVA', 279.00, 8, 'dd6c67b1f36483934213e20bef17deff.jpg', 'f10174636a2c74d365043a038d5b2147.jpg', '2014-04-27 04:34:33', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(37, 19, 'MySQL', 'เรียนรู้วิธีสร้างและจัดการฐานข้อมูลด้วย MySQL', 219.00, 3, 'd44ea944d39fb25169090189e4c48b82.jpg', '43b6d3ce0034ffebf0ff01c965339089.jpg', '2014-04-27 04:35:28', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(38, 20, 'Windows 8.1', 'คู่มือการใช้งาน Windows 8.1 ฉบับสมบูรณ์', 279.00, 0, 'cb1acfe52e49b6565e27b8131715f591.jpg', '2244e31d13a5bebe983dc7f17df14119.jpg', '2014-04-27 04:38:28', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(40, 11, 'Microsoft Word 365 form Dummy', 'Microsoft Word 365 form Dummy Microsoft Word 365 form Dummy Microsoft Word 365 form Dummy', 299.00, 32, '', '', '2014-04-27 19:32:22', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(41, 11, 'Microsoft Excel 365 form Dummy', 'Microsoft Excel 365 form Dummy', 199.00, 2, '', '', '2014-04-27 19:32:45', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(42, 11, 'Microsoft PowerPoint 365 form Dummy', 'Microsoft PowerPoint 365 form Dummy Microsoft PowerPoint 365 form Dummy Microsoft PowerPoint 365 form Dummy', 199.00, 2, '', '', '2014-04-27 19:33:04', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(43, 11, 'Microsoft Access 365 form Dummy', 'Microsoft Access 365 form Dummy Microsoft Access 365 form Dummy Microsoft Access 365 form Dummy', 249.00, 0, '', '', '2014-04-27 19:33:37', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(44, 20, 'Windows 7 ฉบับสมบูรณ์', 'คู่มือการใช้งาน Windows 7 ฉบับสมบูรณ์', 259.00, 10, '8c240ba179a5ea2433f3ff514a3e6805.jpg', 'ab0d60531d22eac04b95605c907b290b.jpg', '2014-05-02 18:37:56', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(45, 24, 'ระบบ Network ในเบื้องต้น', 'เรียนรู้และใช้งานระบบเครือข่าย และการติดตั้งและให้บริการด้านเครือข่าย', 189.00, 9, 'fa06e6d3031f4766a47e6c1098b665c1.jpg', '044d4d4eabaa180fc0e717d122b649a4.jpg', '2014-05-02 18:39:19', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(46, 25, 'ล้างเครื่องและประกอบคอมฯ', 'คู่มือการเป็นช่างคอมฯ ด้วยการเรียนรู้อุปกรณ์ฮาร์ดแวร์ และการประกอบเครื่อง', 189.00, 17, 'b7f61fc7091bc4a979a01216d2eadb5f.jpg', 'd98a9c0f577cff2a2309752bf407ee3f.jpg', '2014-05-02 18:40:51', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(47, 18, 'คู่มือ Macbook/Macbook Pro', 'เรียนรู้วิธีใช้งานฮาร์ดแวร์และซอฟต์แวร์ และ Application บน Macbook และ Macbook Pro ฉบับสมบูรณ์', 239.00, 64, 'ea18a846d5c0e67bf183b46b12d9ef03.jpg', '7efd39357b47b92fc50bb2d2175ac3ba.jpg', '2014-05-02 18:42:15', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(48, 26, 'Galaxy Note', 'คู่มือการใช้งาน Galaxy Note 8', 249.00, 5, '81159b5729d193cfa7cc83f71454f531.jpg', '608308eca31f965f653db9856fc8a66c.jpg', '2014-05-02 18:50:29', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(49, 26, 'คู่มือ iPhone 5', 'เรียนรู้วิธีใช้งาน iPhone 5 และ Apps ที่ใช้งานในชีวิตประจำวัน', 259.00, 42, '055e93f463e861228cfb5749071f3130.jpg', 'b3808778c1f87014394437a808e70143.jpg', '2014-05-02 18:51:17', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(50, 26, 'คู่มือ Android Tablet', 'เรียนรู้วิธีใช้งาน และแก้ปัญหา Android Tablet ในแบบรอบด้าน', 199.00, 43, 'ecef029f54eb7b7fd63a4a34ed24483f.jpg', '80bd1eacc1602522154de4089790f61c.jpg', '2014-05-02 18:52:14', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(51, 26, 'คู่มือ Window 8 Phone', 'คู่มือการใช้งาน Window 8 Phone ฉบับสมบูรณ์', 229.00, 33, '493ec63d1e085c41aad8d8ac36f83fa4.jpg', 'e6fad74c989855d6f4baee476cfd89dd.jpg', '2014-05-02 18:53:15', '0000-00-00 00:00:00');
INSERT INTO `tbl_product` VALUES(52, 26, 'Nokia Lumia', 'คู่มือและเทคนิคการใช้งาน Nokia Lumia', 229.00, 37, '73aeda7814debf5e8c0ec61082b077c7.jpg', '52d3004cecbfe20d152d292f0c82de38.jpg', '2014-05-02 18:54:10', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_shop_config`
--

DROP TABLE IF EXISTS `tbl_shop_config`;
CREATE TABLE IF NOT EXISTS `tbl_shop_config` (
  `sc_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sc_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sc_phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sc_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sc_shipping_cost` decimal(5,2) NOT NULL DEFAULT '0.00',
  `sc_currency` int(10) unsigned NOT NULL DEFAULT '1',
  `sc_order_email` enum('y','n') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- dump ตาราง `tbl_shop_config`
--

INSERT INTO `tbl_shop_config` VALUES('PlainCart - Edit by itbookonline', '9 Bangkok Thailand', '099-999-9999', 'Thanathip.tan@sbacnon.ac.th', 100.00, 5, 'y');

-- --------------------------------------------------------

--
-- โครงสร้างตาราง `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_regdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_role` enum('customer','guest','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'guest',
  `user_first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_phone` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_city` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_state` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_postal_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- dump ตาราง `tbl_user`
--

INSERT INTO `tbl_user` VALUES(1, 'admin', '3db642be86807e195c422694d9f9094b', '2005-02-20 17:35:44', '2014-05-04 08:53:17', 'admin', 'ผู้ดูแลระบบ', 'เว็บมาสเตอร์', 'admin@itbookonline.com', '9 หมู่ 9', '099-999-999', 'เมือง', 'นนทบุรี', '11120');
INSERT INTO `tbl_user` VALUES(2, 'itbookonline', '04e0e1e2ca4d93be2d59071e2c82cfed', '2005-03-02 17:52:51', '2014-05-03 15:54:46', 'customer', 'ชื่อครับ', 'นามสกุลค่ะ', 'itbookonline@hotmail.com', '123/321 หมู่บ้าน ประดับดาว', '089-888-9999', 'เมือง', 'นนทบุรี', '10110');
