<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$this->loadextraClass('registration');
$register = new registration;

$person = new user();

$subscriber = $register->registerSubscriber($_POST);

if($person->login($subscriber['username'], $subscriber['password'])=='login_pass')
{
    redirect($code->decrypt($_POST['url']).'&msgvalid=You_have_been_registered_and_logged_Check_your_email');
}
?>
