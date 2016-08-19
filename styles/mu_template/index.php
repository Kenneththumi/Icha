<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
define('TEMPLATE_URL', STYLES_PATH.'ichatemplate/');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITENAME; ?></title>
<link href="css/template_css.css" rel="stylesheet" type="text/css" />
<?php
	$skin->loadscripts();
	
	//the params can array(folder, filename) or a string
	$skin->loadcss(array('ichatemplate','template_css'));
?>
<link href='http://fonts.googleapis.com/css?family=Rufina:400,700' rel='stylesheet' type='text/css'>
</head>

<body>
<div id="container">
  <div id="top">
    <div id="logo">
    <a href="<?php echo SITE_PATH ?>"><img src="<?php echo TEMPLATE_URL; ?>images/measleslogo.png" /></a>
    </div>
    <div id="search">
    	<?php $skin->loadmodule('search','search'); ?>
    </div>
  </div>
  <div id="banner">
  <?php 
	  $skin->loadmodule('leadarticle','leadarticle'); 
  ?>
  </div>
  <div id="bannershadow"><img src="<?php echo TEMPLATE_URL; ?>images/bannershadow.png" width="963" height="30" /></div>
  <div id="innercontainer">
    <div id="leftcol">
    	<?php 
		  $skin->sitemessages($_GET['msgerror']||$_GET['msgvalid']); 
		
		  //the default component is com_frontpage so make sure its there
		  $skin->loadcontent($_GET['content'],$_GET['folder'],$_GET['file']);			  
		?>
    </div>
    <div id="rightcol">
    	<div class="holder">
        <?php
			$skin->loadmodule('listpostings','specialpostings');
		?>
        </div>
        <div class="holder">
        <?php
			$skin->loadmodule('whatsnew','whatsnew');
		?>
        </div>
        <div class="holder">
        <?php
			$skin->loadmodule('subscribeusers','subscribers');
		?>
        </div>
        <div class="holder">
        <?php
			$skin->loadmodule('top10','top10');
		?>
        </div>
        <div class="holder">
        <?php
			$skin->loadmodule('showcounter','hitscounter');
		?>
        </div>
        <div class="holder">
        <?php
			$skin->loadmodule('elinks','elinks');
		?>
        </div>
    </div>
  </div>
</div>
<div id="footer">
  <div id="footercontainer">
    <div id="footerviews">
    	<img src="<?php echo TEMPLATE_URL; ?>images/footerlogo.jpg" />
    </div>
  </div>
</div>
</body>
</html>