<?php
/*
	Contain the common functions 
	required in shop and admin pages
*/
require_once 'config.php';
require_once 'database.php';

/*
	Make sure each key name in $requiredField exist
	in $_POST and the value is not empty
*/
function checkRequiredPost($requiredField) {
	$numRequired = count($requiredField);
	$keys        = array_keys($_POST);
	
	$allFieldExist  = true;
	for ($i = 0; $i < $numRequired && $allFieldExist; $i++) {
		if (!in_array($requiredField[$i], $keys) || $_POST[$requiredField[$i]] == '') {
			$allFieldExist = false;
		}
	}
	
	return $allFieldExist;
}

function getShopConfig()
{
	// get current configuration
	$sql = "SELECT sc_name, sc_address, sc_phone, sc_email, sc_shipping_cost, sc_order_email, cy_symbol 
			FROM tbl_shop_config sc, tbl_currency cy
			WHERE sc_currency = cy_id";
	$result = dbQuery($sql);
	$row    = dbFetchAssoc($result);

    if ($row) {
        extract($row);
	
        $shopConfig = array('name'           => $sc_name,
                            'address'        => $sc_address,
                            'phone'          => $sc_phone,
                            'email'          => $sc_email,
				    'sendOrderEmail' => $sc_order_email,
                            'shippingCost'   => $sc_shipping_cost,
                            'currency'       => $cy_symbol);
    } else {
        $shopConfig = array('name'           => '',
                            'address'        => '',
                            'phone'          => '',
                            'email'          => '',
				    'sendOrderEmail' => '',
                            'shippingCost'   => '',
                            'currency'       => '');    
    }

	return $shopConfig;						
}

function displayAmount($amount)
{
	global $shopConfig;
	return $shopConfig['currency'] . number_format($amount);
}

/*
	Join up the key value pairs in $_GET
	into a single query string
*/
function queryString()
{
	$qString = array();
	
	foreach($_GET as $key => $value) {
		if (trim($value) != '') {
			$qString[] = $key. '=' . trim($value);
		} else {
			$qString[] = $key;
		}
	}
	
	$qString = implode('&', $qString);
	
	return $qString;
}

/*
	Put an error message on session 
*/
function setError($errorMessage)
{
	if (!isset($_SESSION['plaincart_error'])) {
		$_SESSION['plaincart_error'] = array();
	}
	
	$_SESSION['plaincart_error'][] = $errorMessage;

}

/*
	print the error message
*/
function displayError()
{
	if (isset($_SESSION['plaincart_error']) && count($_SESSION['plaincart_error'])) {
		$numError = count($_SESSION['plaincart_error']);
		
		echo '<table id="errorMessage" width="100%" align="center" cellpadding="20" cellspacing="0"><tr><td>';
		for ($i = 0; $i < $numError; $i++) {
			echo $_SESSION['plaincart_error'][$i] . "<br>\r\n";
		}
		echo '</td></tr></table>';
		
		// remove all error messages from session
		$_SESSION['plaincart_error'] = array();
	}
}


/*
	Put an error message on session 
*/
function setSuccess($successMessage)
{
	if (!isset($_SESSION['plaincart_success'])) {
		$_SESSION['plaincart_success'] = array();
	}
	
	$_SESSION['plaincart_success'][] = $successMessage;

}

/*
	print the success message
*/
function displaySuccess()
{
	if (isset($_SESSION['plaincart_success']) && count($_SESSION['plaincart_success'])) {
		$numSuccess = count($_SESSION['plaincart_success']);
		
		echo '<table id="successMessage" width="100%" align="center" cellpadding="20" cellspacing="0"><tr><td>';
		for ($i = 0; $i < $numSuccess; $i++) {
			echo $_SESSION['plaincart_success'][$i] . "<br>\r\n";
		}
		echo '</td></tr></table>';
		
		// remove all error messages from session
		$_SESSION['plaincart_success'] = array();
	}
}

/**************************
	Paging Functions
***************************/

function getPagingQuery($sql, $itemPerPage = 10)
{
	//ตรวจสอบค่า $_GET['page'] ว่าเป็นหน้าที่เท่าไหร่
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];		//กำหนดเลขหน้าให้กับตัวแปร $page
	} else {
		$page = 1;				//ถ้าไม่มีการกำหนด ก็กำหนดให้เป็นหน้าแรก
	}
	
	//เริ่มต้น fetching จากแถวที่เลือก
	$offset = ($page - 1) * $itemPerPage;	//ใช้กับ SQL เพื่อบอกว่าจะให้
											//เริ่มคิวรี่จากลำดับที่เท่าใด
	//กำหนด SQL โดยมีเงื่อนไขว่า ต้องการคิวรี่ให้จากลำดับที่เท่าใด แล้วเลือกมากี่เร็กคอร์ด
	
	return $sql . " LIMIT $offset, $itemPerPage";
}

/*
	Get the links to navigate between one result page to another.
	Supply a value for $strGet if the page url already contain some
	GET values for example if the original page url is like this :
	
	http://www.phpwebcommerce.com/plaincart/index.php?c=12
	
	use "c=12" as the value for $strGet. But if the url is like this :
	
	http://www.phpwebcommerce.com/plaincart/index.php
	
	then there's no need to set a value for $strGet
	
	
*/
function getPagingLink($sql, $itemPerPage = 10, $strGet = '')
{
	$result        = dbQuery($sql);
	$pagingLink    = '';
	$totalResults  = dbNumRows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
	//จำนวนลิงก์ที่ต้องการแสดงที่ Paging ด้านล่าง ซึ่งถ้ามีจำนวนหน้ามากๆ 
	//จะแสดงลิงก์เท่าที่กำหนดเท่านั้น
	$numLinks      = 10;

		
	//แสดงลิงก์ ในกรณีที่มีรายการมากกว่า 1 หน้า
	if ($totalPages > 1) {
	
		//ใส่ค่าหน้าเว็บเพจปัจจุบันให้กับตัวแปร $self
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		//กำหนดค่าให้กับตัวแปร $pageNumber เพื่อดูว่าหน้าที่ต้องการแสดงคือหน้าที่เท่าใด
		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;   //ถ้าไม่มีการส่งหน้ามา ก็ให้กำหนดเป็นหน้าแรก
		}
		
		//แสดงลิงก์ 'หน้าก่อน' ถ้าไม่ได้อยู่ที่หน้าแรก
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <a href=\"$self?page=$page&$strGet/\">[หน้าก่อน]</a> ";
			} else {
				$prev = " <a href=\"$self?$strGet\">[หน้าก่อน]</a> ";
			}	
				
			//แสดงหน้าแรก
			$first = " <a href=\"$self?$strGet\">[หน้าแรก]</a> ";
		} else {
			$prev  = ''; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		//แสดงลิงก์ 'ถัดไป' ถ้าไม่ได้อยู่ที่หน้าสุดท้าย
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <a href=\"$self?page=$page&$strGet\">[ถัดไป]</a> ";
			$last = " <a href=\"$self?page=$totalPages&$strGet\">[สุดท้าย]</a> ";
		} else {
			$next = ''; 	//อยู่ที่หน้าสุดท้ายแล้ว ไม่ต้องแสดงลิงก์ หน้าถัดไป
			$last = ''; 	//และต้องไม่แสดงลิงก์สำหรับหน้าสุดท้าย
		}

		//กรณีมีจำนวนหน้ามากๆ จะแสดงแค่ 10 หน้า (ตามค่าจาก $numLinks)
		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		//คำนวณหน้าเริ่มต้น
		$end   = $start + $numLinks - 1;		
		
		$end   = min($totalPages, $end);	//คำนวณเลขหน้าสำหรับลิงก์สุดท้าย
		
		$pagingLink = array();		//กำหนด array() สำหรับเก็บผลลัพธ์
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = " $page ";   //ไม่ต้องสร้างลิงก์ให้กับเพจปัจจุบัน
			} else {
				if ($page == 1) {
					$pagingLink[] = " <a href=\"$self?$strGet\">$page</a> ";
				} else {	
					$pagingLink[] = " <a href=\"$self?page=$page&$strGet\">$page</a> ";
				}	
			}
	
		}
		
		$pagingLink = implode(' | ', $pagingLink);
		
		// นำลิงก์ทั้งหมดเก็บเข้าตัวแปร $pagingLink
		$pagingLink = $first . $prev . $pagingLink . $next . $last;
	}
	
	return $pagingLink;
}

function getPagingLinkForBootStrap($sql, $itemPerPage = 10, $strGet = '')
{
	
		$result        = dbQuery($sql);
	$pagingLink    = '';
	$totalResults  = dbNumRows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
		
	// create the paging links only if we have more than one page of results
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		
		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <li><a href=\"$self?page=$page&$strGet\">&laquo;</a></li> ";
			} else {
				$prev = " <li><a href=\"$self?$strGet\">&laquo;</a></li> ";
			}	
				
			$first = " <li><a href=\"$self?$strGet\">หน้าแรก</a></li> ";
		} else {
			$prev  = ''; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <li><a href=\"$self?page=$page&$strGet\">&raquo;</a></li> ";
			$last = " <li><a href=\"$self?page=$totalPages&$strGet\">สุดท้าย</a></li> ";
		} else {
			$next = ''; // we're on the last page, don't show 'next' link
			$last = '';
		}
		
		$pagingLink = array();
		for($page = 1; $page <= $totalPages; $page++)	{
		
			if ($page == $pageNumber) {
					$pagingLink[] = " <li class=\"active\"><a href=\"$self?page=$page&$strGet\">$page</a></li> ";
			} else {
	
					$pagingLink[] = " <li><a href=\"$self?page=$page&$strGet\">$page</a></li> ";
			}
	
		}
		
		$pagingLink = implode('', $pagingLink);
		
		
		
		$pagClassBegin= '<div style="text-align:center;"><ul class="pagination pagination-sm pagination-centered">';
		$pagClassEnd= '</ul></div>';
		$pagingLink = $pagClassBegin. $first.$prev . $pagingLink . $next .$last.$pagClassEnd;

	}
	
	return $pagingLink;
}

function getPagingLinkForHomeAjax($sql, $itemPerPage = 8, $catId = 0, $target = '')
{
	
	$result        = dbQuery($sql);			//คิวรี่สินค้าออกมา
	$pagingLink    = '';
	$totalResults  = dbNumRows($result);	//นับจำนวนสินค้าทั้งหมด
	$totalPages    = ceil($totalResults / $itemPerPage);	//คำนวณจำนวนหน้าทั้งหมด
	//กำหนดรหัสประเภทสินค้าให้กับคลาส
	$hiddenCatId = "<span class=\"hidden-cat-product\" id=\"cat-$catId\" hidden></span>";
	$prev  = '';
	$next = '';
	$first = '';
	$last = '';
	$numLinks = 10;	//จำนวนลิงก์ที่จะแสดง ถ้ามีจำนวนหน้ามากๆ ก็ให้แสดงแค่ 10 ลิงก์
	//ถ้ามีจำนวนหน้ามากกว่า 1 หน้า ให้สร้างลิงก์
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];	//หมายเลขหน้าที่ส่งมา
		} else {
			$pageNumber = 1;		//ถ้าไม่มีก็คือหน้าแรก
		}
		
		//แสดงลิงก์ ก่อน ถ้าหน้าปัจจุบันมีค่ามากกว่า 1
		//แสดงลิงก์ หน้าแรก ถ้าหน้าปัจจุบันมากกว่า 1
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <li><a class=\"ajax-page-link$target\" id=\"pagelinkprev-$page\">&laquo;ก่อน</a></li> ";
			} else {
				$prev = " <li><a class=\"ajax-page-link$target\" id=\"pagelinkprev-$page\">&laquo;ก่อน</a></li> ";
			}
				
			$first = " <li><a class=\"ajax-page-link$target\" id=\"pagelinkfirst-1\">หน้าแรก</a></li> ";
		} else {
			$prev  = ''; 	//อยู่หน้าแรก ไม่จำเป็นต้องแสดงลิงก์ก่อน
			$first = ''; 	//อยู่หน้าแรกไม่ต้องแสดงลิงก์หน้าแรก
		}
	
		//ถ้าอยู่หน้าสุดท้าย ไม่ต้องแสดงลิงก์ หน้าถัดไป และลิงก์ หน้าสุดท้าย
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			
			$next = " <li><a class=\"ajax-page-link$target\" id=\"pagelinknext-$page\">ถัดไป&raquo;</a></li> ";
			$last = " <li><a class=\"ajax-page-link$target\" id=\"pagelinklast-$totalPages\">สุดท้าย</a></li> ";
		} else {
			$next = ''; // we're on the last page, don't show 'next' link
			$last = '';
		}
		
		//ถ้ามีจำนวนหน้ามากๆ เกินค่า $numLink ก็ให้แสดงจำนวนลิงก์เท่าจำนวน $numLink
		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;		
		$end   = min($totalPages, $end);
		
		//กำหนดตัวแปรแบบ array มาเก็บผลลัพธ์
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
		
			if ($page == $pageNumber) {
					$pagingLink[] = " <li class=\"active\">$hiddenCatId<a class=\"ajax-page-link$target\" id=\"pagelink-$page\">$page</a></li> ";
			} else {
	
					$pagingLink[] = " <li>$hiddenCatId<a class=\"ajax-page-link$target\" id=\"pagelink-$page\">$page</a></li> ";
			}
	
		}
		$pagingLink = implode('', $pagingLink);
		$pagClassBegin= '<div style="text-align:center;" id="paging-link"><ul class="pagination pagination-sm pagination-centered">';
		$pagClassEnd= "</ul>$hiddenCatId</div>";
		$pagingLink = $pagClassBegin. $first.$prev . $pagingLink . $next .$last.$pagClassEnd;

	}
	
	return $pagingLink;		//รีเทิร์นค่าลิงก์กลับไป
}
?>