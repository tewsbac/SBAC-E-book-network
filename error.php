<?php
require_once 'library/config.php';

$pageTitle   = 'Checkout Error';
require_once 'include/header.php';
?>

<p>&nbsp;</p><table width="500" border="0" align="center" cellpadding="1" cellspacing="0">
   <tr> 
      <td align="left" valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
               <td align="center"> 
               <div class="alert alert-warning">
               		<p>&nbsp;</p>
                    <p>ขออภัยในความไม่สะดวก เนื่องจากเกิดข้อผิดพลาดในขั้นตอนชำระค่าสินค้า 
                        รบกวนติดต่อผู้ดูแลเว็บไซต์ หากต้องการกลับไปยังร้านค้าโปรด 
                            <a href="index.php">คลิกที่นี่</a></p>
                  	<p>&nbsp;</p>
                </div>
               </td>
            </tr>
         </table></td>
   </tr>
</table>

<br>
<br>
<?php
require_once 'include/footer.php';
?>