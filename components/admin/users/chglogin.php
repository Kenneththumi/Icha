<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

$uid = $_GET['uid'];

$utype = array();

if(isset($_GET['lid']))
{
	$lid = $_GET['lid'];

	$uname = $this->runquery("SELECT * FROM users WHERE userid='$lid'",'multiple','all');
	$uArray = $this->fetcharray($uname);

	if($uArray['usertype']=='admin')
	{
		$utype[$uArray['usertype']] = 'Administrator';
	}
	elseif($uArray['usertype']=='superadmin')
	{
		$utype[$uArray['usertype']] = 'Super Administrator';
	}
}
else
{
	$utype[''] = 'None Selected';
}

$utype['user'] = 'Public User';
$utype['admin'] = 'Administrator';
$utype['superadmin'] = 'Super Administrator';

$loginform = new form();

$loginform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'550',
								  "map"=>array(1,1,1,1,2),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$loginform->addHidden('usersave','usersave');
$loginform->addHidden('uid',$uid);
$loginform->addHidden('lid',$_GET['lid']);

$loginform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Site Management: User Login</h1> </td></tr></table>');

$loginform->addTextbox('User Name:','username',$uArray['username'],array('required'=>true));

$loginform->addPassword('Password:','password',$uArray['password'],array('required'=>true));

$loginform->addRadio("Enabled :", "enabled", $uArray['enabled'], array("yes", "no"),array('required'=>true));

$loginform->addButton('Save Login Details','submit',array("id"=>"myformbutton"));

if(isset($_POST['usersave']))
{
	$loginSave = array(
					   'username' => $_POST['username'],
					   'password' => $_POST['password'],
					   'sourceid' => $_POST['uid'],
					   'enabled' => $_POST['enabled']
					   );
	
	if($_POST['lid']!='')
	{
		$this->dbupdate('users',$loginSave,"userid='".$_POST['lid']."'");
		$this->inlinemessage('The login details have been updated','valid','no');
	}
	else
	{
		$this->dbinsert('users',$loginSave);
		$this->inlinemessage('The login details has been saved','valid','no');
	}
	
	$loginform->render();
}
else
{
	$loginform->render();
}
?>