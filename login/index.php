<?php
error_reporting(0);

if($_SERVER['HTTP_HOST']!='localhost'&&$_SERVER['HTTP_HOST']!='192.185.115.202'){
    
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
}
elseif($_SERVER['HTTP_HOST']=='192.185.115.202'){
    
    require '/home/nerojobs/public_html/icha/config.php';
}
else{
    
    require($_SERVER['DOCUMENT_ROOT'].'/icha/config.php');
}    

require_once(ABSOLUTE_PATH.'includes/header.php');
include_once(ABSOLUTE_PATH.'classes/db.class.php');

//initialize the db object
$dbase = new database;

//loads all the core files required for the app
$dbase->loadclasses();

//initialize the template object
$skin = new template(FALSE);

//initialize the navigation object
$nav = new navigation;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ICHA System Login</title>
<?php
    //$skin->loadscripts();
    $skin->loadcss(['login','css','loginstyles']);
?>
</head>

<body>
<div id="logincontainer">
  <div id="loginbody">
    <div id="top"><img src="<?php echo SITE_PATH ?>/login/images/ichalogo.png" /></div>
    <div id="main">
        <?php
            $skin->loadmodule('frontlogin','login');
        ?>
    </div>
  </div>
</div>
</body>
</html>