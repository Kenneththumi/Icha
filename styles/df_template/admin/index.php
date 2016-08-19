<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITENAME ?> Administration</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
$skin->metadata('International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website, Designed by Nero Web Solutions');

$skin->loadscripts();
$skin->loadstyles('preview');
?>
<link href="<?php echo STYLES_PATH; ?>ichatemplate/css/template_css.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php $skin->preloadIcons(); ?>
<div id="container">
  <div id="top">
    <div id="logo"><img src="<?php echo STYLES_PATH; ?>ichatemplate/images/measlesupdatelogo.png" width="287" height="127" /></div>
    <div id="logout"><?php echo '<a href="'.SITE_PATH.'?content=mod_login&task=logout&logid='.$_SESSION['logid'].'" class="accountlink">[ '.$_SESSION['username'].' - Logout ]</a>' ?></div>
  </div>
  <div id="innercontainer">
    <div id="contentholder">
      <div id="menu">
      <?php
	  $nav->loadmenu('admin');
	  
	  if($_SESSION['usertype']=='superadmin')
	  {
		  $nav->loadmenu('superadmin');
	  }
	  ?>
      </div>
      <div id="adminbody">
			  <?php
                  $skin->sitemessages($_GET['msgerror']||$_GET['msgvalid']);
				  $skin->loadcontent($_GET['admin'],$_GET['folder'],$_GET['file']); 
              ?>
</div>
    </div>
    <div id="adminbottom">(C) 2010 - <?php echo SITENAME ?> - All Rights Reserved</div>
  </div>
  <div id="bottom">(C) All Rights Reserved - icha.net</div>
</div>
</body>
</html>