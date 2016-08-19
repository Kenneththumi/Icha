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

$queryurl = $_SERVER['REQUEST_URI'];

$addurl = '/?admin=com_admin&folder=courses&file=addcourse&type=course';
$editurl = '/?admin=com_admin&folder=courses&file=addcourse&task=edit';
$puburl = '/?admin=com_admin&folder=publications&file=addpublication';

if(substr_count($queryurl,$editurl)==0 && substr_count($queryurl,$addurl)==0 && substr_count($queryurl,$puburl)==0){
    $skin->loadscripts();
}
else{
    echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
          <script src="//code.jquery.com/jquery-1.10.2.js"></script>
          <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';
}

//the params can array(folder, filename) or a string
$skin->loadcss(array('ichatemplate/admin','admintemplate_css'));
$skin->sitemessages($_GET['msgerror']||$_GET['msgvalid'],'yes');
?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>

<body>
<?php $skin->preloadIcons(); ?>
<div id="ichacontainer">
  <div id="top">
    <div id="logo">
        <?php
        if($_SESSION['usertype']=='adm')
        {
        ?>
        <img src="<?php echo STYLES_PATH ?>ichatemplate/admin/images/adminlogo.png" />
        <?php
        }
        elseif($_SESSION['usertype']=='superadmin'){
        ?>
        <img src="<?php echo STYLES_PATH ?>ichatemplate/admin/images/systemlogo.png" />
        <?php
        }
        ?>
    </div>
  </div>
  <div id="menu">
  <div id="logout"><?php echo '<a href="'.SITE_PATH.'?content=mod_login&task=logout&logid='.$_SESSION['logid'].'&alert=yes" class="accountlink"> '.$_SESSION['username'].' - Logout </a>' ?></div>
  </div>
  <div id="innercontainer">
  <div id="leftmenu">
      <?php
          if($_SESSION['usertype']=='adm')
          {
              $params = array(
                                'showicons' => 'yes',
                                'icopath' => STYLES_PATH.$skin->set_template.'/admin/images/',
                                'icosuffix' => '_icon'
                            );

              $nav->loadmenu('adm',$params);
	  }
	  elseif($_SESSION['usertype']=='superadmin')
	  {
                $nav->loadmenu('superadmin');
	  }
	  ?>
      </div>
      <div id="maincontent">
	<?php
        
            $skin->loadcontent($_GET['admin'],$_GET['folder'],$_GET['file']); 
        ?>
          <div id="bottom">(C) All Rights Reserved - icha.net</div>
      </div>
  </div>
  
</div>
</body>
</html>