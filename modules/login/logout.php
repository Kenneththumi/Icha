<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$dbuser = new user();

if($_GET['task']=='logout'&&isset($_GET['logid']))
{	
    $logout = $dbuser->logout($_GET['logid']);

    if($logout=='sessionout')
    {
        //$dfFetch = $dbuser->runquery("SELECT * FROM menus WHERE menus.default='yes' AND menuname='frontend'",'single');
        redirect($code->decrypt($_GET['url']));
    }
}
?>
