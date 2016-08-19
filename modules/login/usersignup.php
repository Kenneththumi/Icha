<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$link = new navigation();
$this->loadplugin('classForm/class.form');



//from shopping cart
if(isset($params['from']))
{
    $step1action = $params['step1action'];
    $url = $params['url'];
}//from events
elseif($_GET['from']=='events')
{
    $step1action = '?content=com_events&folder=same&file=eventbooking&from=newregistration&id='.$_GET['id'];
    
    $params['title'] = 'Events Booking - Enter details below';
    $map = array(1,1,2,1,1,1);
    
    $title = '<h1 class="fullarticleTitle">'.$params['title'].'</h1>';
    $url = $_GET['url'];
}//from publications - free download
elseif(isset($params['step1action']))
{
    $step1action = $params['step1action'];
    
    $map = array(1,2,1,1,1);
    
    $title = '<h2>'.$params['title'].'</h2>';
    $url = $link->geturl();
}

$this->loadplugin('encryption/encrypt');
$cipher = new encryption();

$url = $cipher->encrypt($url);

$step1action = $params['step1action'];
$userform = new form;
$userform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'97%',
                                  "map" => $map,
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action"=>$step1action
                                  ));

$userform->addHidden('cmdsave', 'cmdsave');
$userform->addHidden('url',$url);

$userform->addHTML($title);
//$userform->addHTML('<p>If already a member, <a href="?content=mod_login&folder=same&file=frontlogin&url='.$url.'&alert=yes" class="launchlogin">click to login</a></p>');

if($_GET['from']=='publications')
{
    $userform->addHTML('<p>Please register to process ordered items</p>');
}
elseif($_GET['from']=='events')
{
    $userform->addHTML('<p>Please register for the upcoming event</p>');
}

$userform->addTextbox('First Name', 'fname', '',array('required'=>true));
$userform->addTextbox('Last Name', 'lname', '',array('required'=>true));

$userform->addEmail('Email Address', 'email', '',array('required'=>true));
$userform->addTextbox('Mobile No', 'mobileno','',array('required'=>true));

$userform->addButton('Register', 'submit',array('id'=>'myformbutton'));

$userform->renderHead();
$userform->render();

