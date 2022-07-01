<?php
if (!defined('WEB_ROOT')) {
	exit;
}

$categoryList    = getCategoryList();
$categoriesPerRow = 3;
$numCategory     = count($categoryList);
$columnWidth    = (int)(100 / $categoriesPerRow);
?>
<div>

	<div id="category-content-switch">
		<table width="100%" border="0" cellspacing="0" cellpadding="20">
	<?php 
	if ($numCategory > 0) {
		$i = 0;
		for ($i; $i < $numCategory; $i++) {
			if ($i % $categoriesPerRow == 0) {
				echo '<tr>';
			}
		
		// we have $url, $image, $name, $price
			extract ($categoryList[$i]);
		
			echo "<td width=\"$columnWidth%\" align=\"center\"><a href=\"#product-container\" class=\"category-group-item\" id=\"catname-$id\"><img class=\"img-rounded\" src=\"$image\" border=\"0\"><br>$name</a></td>\r\n";
		
	
			if ($i % $categoriesPerRow == $categoriesPerRow - 1) {
				echo '</tr>';
			}
		
		}
		if ($i % $categoriesPerRow > 0) {
			echo '<td colspan="' . ($categoriesPerRow - ($i % $categoriesPerRow)) . '">&nbsp;</td>';
		}
	} else {
	?>
		<tr><td width="100%" align="center" valign="center">ยังไม่มีหมวดหมู่สินค้าใดๆ</td></tr>
	<?php	
	}	
	?>
		</table>
	</div>
	
	<div style="text-align:right;">
		<button type="button" class="btn btn-default btn-sm category-switch-up" id="category-button-switch">
  		<span id="switch-up-down-name">&nbsp;&nbsp;&nbsp;ซ่อนประเภทสินค้า&nbsp;&nbsp;&nbsp;</span><span class="glyphicon glyphicon-chevron-up" id="category-direction-icon"></span>
		</button>&nbsp;&nbsp;&nbsp;
	</div>
	
</div>
