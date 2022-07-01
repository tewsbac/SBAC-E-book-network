<?php
require_once '../library/config.php';
require_once '../library/product-functions.php';
require_once '../library/cart-functions.php';

$num = getCartNumItems();
echo $num;

?>