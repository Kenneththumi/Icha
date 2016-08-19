<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

//the password strength script
$this->loadscripts('passwordstrength','yes');

$this->wrapscript("$(document).ready(function(){
                            $('.passcheck').passStrengthify();
                         });");

$uid = $_GET['id'];

$instructor = new user();
$details = $instructor->returnUserSourceDetails($uid, 'instructor');

$uname = $this->runquery("SELECT * FROM users WHERE sourceid='$uid' AND usertype='instructor'",'multiple','all');
$uArray = $this->fetcharray($uname);

$loginform = new form();

$loginform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'95%',
                                  "map"=>array(1,1,2,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => false,
                                  "action"=>'?admin=com_admin&folder=contributors&file=loginprocessing&alert=yes'
                                  ));

$loginform->addHidden('usersave','usersave');
$loginform->addHidden('id',$uid);
$loginform->addHidden('utype', '15');
$loginform->addHidden('url',$link->urlreturn('Instructors'));

$loginform->addHTML('<h1>'.$details['name'].' : Login Details</h1>');

$loginform->addTextbox('User Name:','username',$uArray['username'],array('required'=>true));

$loginform->addPassword('Password:','password',$uArray['password'],array('required'=>true,'class'=>'passcheck'));
$loginform->addPassword('Verify Password:','vpassword',$uArray['password'],array('required'=>true));

//$loginform->addSelect("Select User Type:",'utype','',$utype,array('required'=>true));
$loginform->addRadio("Enabled :", "enabled", $uArray['enabled'], array("yes", "no"),array('required'=>true));

$loginform->addButton('Save Login Details','submit',array("id"=>"myformbutton"));

$loginform->render();
