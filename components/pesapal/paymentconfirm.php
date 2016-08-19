<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1>Payment Confirmation</h1>';
//$this->inlinemessage('Thanks for the payment','valid');

if($_GET['from']=='publication')
{
    $membertype = 'subscriber';
    $paiditem = 'publication';
    
    //get the price 
    $price = $this->runquery("SELECT * FROM prices WHERE publicationid='".$_GET['itemid']."'",'single');
}
elseif($_GET['from']=='course')
{
    $membertype = 'student';
    $paiditem = 'course';
    
    //get the price 
    $price = $this->runquery("SELECT * FROM prices WHERE courseid='".$_GET['itemid']."'",'single');
}

$paymentsave = array(
        'tracking_id' => $_GET['pesapal_transaction_tracking_id'],
        'merchant_reference' => $_GET['pesapal_merchant_reference'],
        'paymentdate' => time(),
        'amount' => $price['price'],
        'curid' => $price['curid'],
        'paiditem' => $paiditem,
        'sourceid' => $_GET['sourceid'],
        'membertype' => $membertype,
        'itemid' => $_GET['itemid']
    );

$this->dbinsert('paymentconfirmation',$paymentsave);
//$pid = mysql_insert_id();

//notify the accountant
include (ABSOLUTE_PATH.'components/pesapal/notifyaccountant.php');

//send receipt to the student
include (ABSOLUTE_PATH.'components/pesapal/emailreceipt.php');

if($_GET['from']=='publication')
{    
    redirect('?content=com_checkout&folder=same&file=processcheckout&step=2&pid='.$_GET['itemid']);
}
elseif($_GET['from']=='course')
{    
    $this->inlinemessage('Your payment has been confirmed, please login to start your course','valid');
}
?>
