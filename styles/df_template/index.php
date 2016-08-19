<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Child Survival</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20493688-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<?php 
$skin->metadata('International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website, Designed by Nero Web Solutions');

$skin->loadscripts();
$skin->loadstyles('preview');

//needs to load a different css for IE
$browser = new browser;

if($browser->Name=='MSIE'&&$browser->Version=='8.0')
{
	//for IE version 8.0
?>
<link href="<?php echo STYLES_PATH; ?>ichatemplate/css/ie8only.css" rel="stylesheet" type="text/css" />
<?php
}
else if($browser->Name=='MSIE'&&$browser->Version=='7.0')
{
	//for IE version 7.0
?>
<link href="<?php echo STYLES_PATH; ?>ichatemplate/css/ie7only.css" rel="stylesheet" type="text/css" />
<?php
}
?>
</head>
<body>

<?php 
$skin->preloadIcons(); 
?>
<div id="container">
  <div id="top">
    <div id="logo"><img src="<?php echo STYLES_PATH; ?>ichatemplate/images/measlesupdateogo.png" width="287" height="127" /></div>
    <div id="search">
    <?php $skin->loadmodule('search','search'); ?>
    </div>
  </div>
<div id="innercontainer">
	<?php $skin->sitemessages($_GET['msgerror']||$_GET['msgvalid']); ?>
<div id="contentholder">
      <div id="banner">
	  <?php 
	  $skin->loadmodule('leadarticle','leadarticle'); 
	  ?>
      </div>
      
      <?php
	  //load the main menu
	  $nav->loadmenu('category',array('type'=>'file'));
	  
	  $skin->loadpath();	  
		  if($_GET['content']=='com_frontpage'||$_GET['content']=='mod_subscribers')
		  {
			  ?>
			  <div id="contentbody">
			  <?php 
		  }
		  else
		  {
			  ?>
			  <div id="mainbody">
			  <?php 
		  }
		  //the default component is com_frontpage so make sure its there
		   $skin->loadcontent($_GET['content'],$_GET['folder'],$_GET['file']);			  
		?>
		  </div>
		  <?php
	  if($_GET['content']=='com_frontpage'||$_GET['content']=='mod_subscribers')
	  {
	  ?>
      <div id="modules">
        <div id="moduleholder">
        <?php
		$skin->loadmodule('whatsnew','whatsnew');
		?>
        </div>
        <div id="modulebottom"></div>
        <div id="whiteholder">
        <?php
		$skin->loadmodule('showcounter','hitscounter');
		?>
        </div>
        <div id="whitebottom"></div>
        <div id="moduleholder">
        <?php
		$skin->loadmodule('top10','top10');
		?>
        </div>
        <div id="modulebottom"></div>
        <div id="moduleholder">
        <?php
		$skin->loadmodule('elinks','elinks');
		?>
        </div>
        <div id="modulebottom"></div>
        <?php
		$skin->loadmodule('accordion','accordion');
		?>
      </div>
      <?php
	  }
	  ?>
      <div id="sponsor"><img src="<?php echo STYLES_PATH; ?>ichatemplate/images/redcross.png" width="763" height="106" /></div>
    </div>
    </div>  
	<div id="bottom">(C) All Rights Reserved - icha.net</div>
</div>
</body>
</html>
