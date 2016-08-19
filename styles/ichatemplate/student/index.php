<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo SITENAME ?> Student</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
$skin->metadata('International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website, Designed by Nero Web Solutions');

$skin->loadscripts();

//the params can array(folder, filename) or a string
$skin->loadcss(array('ichatemplate/student','studenttemplate_css'));

$skin->sitemessages($_GET['msgerror']||$_GET['msgvalid'],'yes');
?>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>

<body>
<?php $skin->preloadIcons(); ?>
<div id="container">
  <div id="top">
    <div id="logo">
        <?php
        if($_SESSION['usertype']=='std')
        {
        ?>
        <img src="<?php echo STYLES_PATH ?>ichatemplate/student/images/icha_student_logo.png" />
        <?php
        }
        elseif($_SESSION['usertype']=='superstudent'){
        ?>
        <img src="<?php echo STYLES_PATH ?>ichatemplate/student/images/systemlogo.png" />
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
           $params = array(
                                'showicons' => 'yes',
                                'icopath' => STYLES_PATH.$skin->set_template.'/student/images/',
                                'icosuffix' => '_icon'
                            );
      
            $nav->loadmenu('std',$params);
      ?>
      </div>
      <div id="maincontent">
	<?php
        
            $skin->loadcontent($_GET['student'],$_GET['folder'],$_GET['file']); 
        ?>
          <div id="bottom">(C) All Rights Reserved - icha.net</div>
      </div>
  </div>
  
</div>
</body>
</html>