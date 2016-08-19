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

$student = new user();
$details = $student->returnUserSourceDetails($uid, 'student');

$stdaccess = $this->runquery("SELECT * FROM accesslevels WHERE accesslevel = 'std'",'single');
$uname = $this->runquery("SELECT * FROM users WHERE sourceid='$uid' AND usertype='student'",'multiple','all');

if($this->getcount($uname)=='0')
{
    $this->inlinemessage('The password hasn\'t been set','error');
}

$uArray = $this->fetcharray($uname);

$loginform = new form();

$loginform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'95%',
                                  "map"=>array(1,1,2,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => false,
                                  "action"=>'?admin=com_admin&folder=students&file=loginprocess&alert=yes'
                                  ));

$loginform->addHidden('usersave','usersave');
$loginform->addHidden('id',$uid);
$loginform->addHidden('utype', $stdaccess['accessid']);
$loginform->addHidden('url',$link->urlreturn('Students'));

$loginform->addHTML('<h1>'.$details['name'].' : Login Details</h1>');

$loginform->addTextbox('User Name:','username',$details['registrationid'],array('required'=>true,'readonly'=>true));

$loginform->addPassword('Password:','password',$uArray['password'],array('required'=>true,'class'=>'passcheck'));
$loginform->addPassword('Verify Password:','vpassword',$uArray['password'],array('required'=>true));

//$loginform->addSelect("Select User Type:",'utype','',$utype,array('required'=>true));
$loginform->addRadio("Enabled :", "enabled", $uArray['enabled'], array("yes", "no"),array('required'=>true));

$loginform->addButton('Save Login Details','submit',array("id"=>"myformbutton"));

$loginform->render();
?>