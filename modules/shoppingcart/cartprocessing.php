<?php
if($_SERVER['HTTP_HOST']=='localhost')
{
    require($_SERVER['DOCUMENT_ROOT'].'/cdi1/config.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/cdi1/classes/db.class.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/cdi1/includes/header.php');
}
else
{
    require($_SERVER['DOCUMENT_ROOT'].'/config.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/classes/db.class.php');
    include_once($_SERVER['DOCUMENT_ROOT'].'/includes/header.php');
}

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);

//initialize the db object
$dbase = new database;

//loads all the core files required for the app
$dbase->loadclasses();

$customer = new user(); 
$cash = new finances;

//add new items to cart
$scart  = new shoppingcart();

if($_POST['task']=='add')
{
    $id_details = explode('-',$_POST['id']);

    //track your sessions
    if(!isset($_SESSION['cartid']))
    {
        $customer->createGuestSession($id_details[0], $id_details[1], $id_details[2]);
    }
    
    $scart->checkin_item($id_details, 'publication');
}
elseif($_POST['task']=='remove')
{
    $scart->checkout_item($_POST['id']);
}

$cartgroup = $dbase->runquery("SELECT * FROM shoppingcart WHERE sessionid='".$_SESSION['logid']."'",'multiple','all');
$cartcount = $dbase->getcount($cartgroup);

echo '<h3>You have '.$cartcount.' items in your cart</h3>';

if($cartcount>='1')
{
    require ABSOLUTE_PATH.'modules/shoppingcart/cartform.php';
}
?>
