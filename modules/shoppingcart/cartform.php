<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$dbase = new database;

if($cartcount>='1')
{
    echo '<form action="?content=com_checkout&folder=same&file=processcheckout&step=1" method="POST">';
    
    for($e=1; $e<=$cartcount; $e++)
    {
        $cart = $dbase->fetcharray($cartgroup);

        //get the publication details
        $publication = $dbase->runquery("SELECT * FROM publications WHERE publicationid = '".$cart['productid']."'",'single');

        //get currency details
        $currency = $cash->getCurrency($cart['currencyid']);

        echo '<div class="item '.($e%2=='0' ? 'even' : 'odd').'">
             
<table width="100%" border="0" cellpadding="10">
  <tr>
    <td><p>'.$dbase->shortentxt(strip_tags($publication['title']),15).'</p></td>
    <td><p>'.$currency['currencycode'].' '.number_format($cart['price'],2).'</p></td>
    <td><a id="'.$cart['cartid'].'" class="removeitem"><img src="'.SITE_PATH.'/modules/shoppingcart/images/trash.png" ></a></td>
  </tr>
</table>
            </div>';

        $total += $cart['price'];
    }

    echo '<p><strong>Cart Total: '.$currency['currencycode'].' '.number_format($total,2).'</strong></p>';
    echo '<input type="Submit" name="checkout" value="Checkout">';
    echo '</form>';
}
?>
