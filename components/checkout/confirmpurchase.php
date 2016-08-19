<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();
$cash = new finances;

/*
$cartgroup = $this->runquery("SELECT * FROM shoppingcart WHERE sessionid='".$_SESSION['logid']."'",'multiple','all');
$cartcount = $this->getcount($cartgroup);

if($cartcount>='1')
{
    echo '<form action="'.$step2action.'" method="POST">';
    
    echo '<p><strong>Please confirm the items below:</strong></p>';
    
    for($e=1; $e<=$cartcount; $e++)
    {
        $cart = $this->fetcharray($cartgroup);

        //get the publication details
        $publication = $this->runquery("SELECT * FROM publications WHERE publicationid = '".$cart['productid']."'",'single');

        //get currency details
        $currency = $cash->getCurrency($cart['currencyid']);

        echo '<div class="item '.($e%2=='0' ? 'even' : 'odd').'">
             
<table width="100%" border="0" cellpadding="10">
  <tr>
    <td width="50%"><p>'.$publication['title'].'</p></td>
    <td width="25%"><p>'.$currency['currencycode'].' '.number_format($cart['price'],2).'</p></td>
    <td width="25%"><a href="">Remove</a></td>
  </tr>
</table>
            </div>';

        $total += $cart['price'];
    }

    echo '<p> Cart Total: '.$currency['currencycode'].' '.$total.'</p>';
    echo '<input type="Submit" name="checkout" value="Checkout">';
    echo '</form>';
}
 * 
 */

//get the publication details
$publication = $this->runquery("SELECT publications.title AS title, prices.curid AS currencyid, prices.price AS price FROM publications INNER JOIN prices ON publications.publicationid = prices.publicationid WHERE publications.publicationid = '".$_POST['pid']."'",'single');

//get currency details
$currency = $cash->getCurrency($publication['currencyid']);

//echo '<form action="'.$step2action.'" method="POST">';

$buyer = new user();
$buyerinfo = $buyer->returnUserSourceDetails($_SESSION['sourceid'], 'subscriber');

require_once ABSOLUTE_PATH.'components/pesapal/paymentform2.php';
//echo '<input type="Submit" name="checkout" value="Checkout">';

echo '<div class="item '.($e%2=='0' ? 'even' : 'odd').'">

<table width="100%" border="0" cellpadding="10" cellspacing="1" class="tablelist" >
<tr>
<td width="10%"><p><strong>Ref No.</strong></p></td>
<td width="70%"><p><strong>Item Name</strong></p></td>
<td width="20%"><p><strong>Price</strong></p></td>
</tr>
<tr>
<td width="10%"><p>'.$_POST['pid'].'</p></td>
<td width="70%"><p>'.$publication['title'].'</p></td>
<td width="20%"><p>'.$currency['currencycode'].' '.number_format($publication['price'],2).'</p></td>
</tr>
<tr>
<td colspan="3" align="right" class="last"><p>Total: '.$currency['currencycode'].' '.$publication['price'].'</p></td>
</tr>
</table>
    </div>';
?>
