<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');
define('TEMPLATE_URL', STYLES_PATH.'ichatemplate/');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo SITENAME; ?></title>
<?php
    $nav = new navigation();
        
    $skin->metadata('International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website','International Center for Humanitarian Affairs ICHA website, Designed by Nero Web Solutions');
    $skin->loadscripts();
    
    $skin->loadextraClass('Browser');
    $browser = new Browser();
    
    if( $browser->getBrowser() == Browser::BROWSER_FIREFOX ) {
        
        //$skin->loadcss(array('ichatemplate','mozila_styles'));
    }
        
    $skin->sitemessages($_GET['msgerror']||$_GET['msgvalid'],'yes');
?>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script>
 jQuery(window).load(function(){
         jQuery('#social').animate({'right':'190px'}, 'slow');
         jQuery('#social .socialtag').mouseenter(function(){ 
             jQuery(this).stop();
             jQuery(this).animate({'right':'0px'}, 'slow');  
        });
         jQuery('#social .socialtag').mouseleave(function(){
             jQuery(this).stop();
             jQuery(this).animate({'right':'-140px'}, 'slow');
        });
});
</script>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,400italic,600' rel='stylesheet' type='text/css'>
<?php
    //the params can array(folder, filename) or a string
    $skin->loadcss(array('ichatemplate','template_css2'));
    $skin->sitemessages($_GET['msgerror']||$_GET['msgvalid'],'yes');
?>
</head>

<body>
<div id="social">
    <?php
        $skin->loadmodule('sign_in_up','login');
    ?>
    <div class="facebook socialtag">
    	<img src="<?php echo DEFAULT_TEMPLATE_PATH ?>/images/facebook-tag.png" width="188" height="43" />
    </div>
    <div class="twitter socialtag">
    	<img src="<?php echo DEFAULT_TEMPLATE_PATH ?>/images/twitter-tag.png" width="188" height="42" />
    </div>
</div>
    <div id="ichacontainer" class="container-fluid">
        <div id="<?php echo $nav->checkURL('Home')==true ? 'banner' : 'inner_banner' ?>" class="row">
        <div id="top" class="row">
        <div id="ichalogo" class="col-md-5 col-sm-8">
            <img src="<?php echo DEFAULT_TEMPLATE_PATH ?>/images/ichalogo.png" class="img-responsive" />
        </div>
        <div id="ichamenu" class="col-md-7 col-sm-4">
          <div class="search">
              <?php
                $skin->loadmodule('search','search');
              ?>
          </div>
      <?php
           //load the main menu
           $nav->loadmenu('toplinks',array('type'=>'file'));           
      ?>
      </div>
    </div>
    <?php 
    //only display the banner on the homepage
    if($nav->checkURL('Home')==true){
        
        $skin->loadmodule('leadarticle','leadarticle'); 
    }
    ?>
  </div>
    <?php
    if($nav->checkURL('Home')==true){
    ?>
    <div id="highlights">
        <?php
            $skin->loadmodule('articles','articles',
                    ['category' => 'Introduction',
                        'number' => 3,
                        'links' => [
                            'training-education' => 'training-and-education',
                            'research' => 'research',
                            'policy-and-advocacy' => 'policy-and-advocacy'
                        ],
                        'class' => 'col-md-3 col-xs-12',
                        'allow_read_more' => TRUE]);
        ?>
        <div class="article col-md-3 col-xs-12">
            <?php
                $skin->loadmodule('small-video-player','videos');
            ?>
        </div>
    </div>
    <div id="testimonials">
    	<div class="header">
            <h1>Testimonials</h1>
        </div>
        <?php
            $skin->loadmodule('articles','articles',['category'=>'Testimonials','number'=>2,'class'=>"col-xs-12"]);
        ?>
    </div>
    <div id="coursehighlights">
        <?php
            $skin->loadmodule('shortlist','courses');
        ?>
    </div>
  <div id="main_courses">
      <?php
            $skin->loadmodule('articles','articles',
                                ['category'=>'About ICHA',
                                    'number'=>2,
                                    'links' => [
                                        'courses-offered' => 'training-and-education',
                                        'about-icha' => 'about-the-institute'
                                    ],
                                    'class'=>'col-xs-12']);
            
            //the default component is com_frontpage so make sure its there
            $skin->loadcontent($_GET['content'],$_GET['folder'],$_GET['file']);
        ?>
  </div>
  <?php
  }
  else{
  ?>
  <div id="inner_container">
      <div id="maincol">
          <?php
            //the default component is com_frontpage so make sure its there
            $skin->loadcontent($_GET['content'],$_GET['folder'],$_GET['file']);
          ?>
      </div>
  </div>
  <?php
  }
  ?>
  <div id="footer">
      <?php
      if(!$nav->checkURL('contacts-us')){
      ?>
      <div class="quarter col-xs-12">
          <h2>Feedback Form</h2>
          <?php
            $skin->loadmodule('form','contacts');
          ?>
      </div>
      <?php
      }
      ?>
      <div class="quarter col-xs-12">
          <h2>Navigation</h2>
          <?php
            $nav->loadParents('ftd');
          ?>
      </div>
      <div class="quarter col-xs-12">
          <h2>Our Programs</h2>
          <?php
            $skin->loadmodule('programs','menus');
          ?>
      </div>
      <div class="quarter col-xs-12">
          <?php
            $skin->loadmodule('articles','articles',['title'=>'Contacts','number'=>1]);
          ?>
      </div>
      
  </div>
</div>
</body>
</html>