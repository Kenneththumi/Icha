<?php
$cash = new finances;

//check if the session is set otherwise check the IP
if(!isset($_SESSION['logid']))
{
    $userip = $_SERVER['REMOTE_ADDR'];
    
    //check for guests
    $guestchk = $this->runquery("SELECT * FROM guest WHERE userip='$userip' ORDER BY guestid DESC",'multiple','all');
    $guestcount = $this->getcount($guestchk);
    
    if($guestcount>='1')
    {
        //get the guest session
        $guest = $this->fetcharray($guestchk);
        
        $userdata = new user();
        
        //and get the cartid from the shopping cart
        $shopping = $this->runquery("SELECT * FROM shoppingcart WHERE shopperid='".$guest['guestid']."'",'single');
        
        $userdata->initializeSession($guest['sessionid'], $guest['guestid'], 'Guest User', 'guest', $shopping['cartid']);
    }
}

$cartgroup = $this->runquery("SELECT * FROM shoppingcart WHERE sessionid='".$_SESSION['logid']."'",'multiple','all');


$cartcount = $this->getcount($cartgroup);

echo '<h3>You have '.$cartcount.' items in your cart</h3>';

require ABSOLUTE_PATH.'modules/shoppingcart/cartform.php';
?>
