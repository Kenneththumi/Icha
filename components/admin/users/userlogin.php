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
	
	/*
	if($uArray['usertype']=='admin')
	{
		$utype[$uArray['usertype']] = 'Product Administrator';
	}
	elseif($uArray['usertype']=='superadmin')
	{
		$utype[$uArray['usertype']] = 'System Administrator';
	}
	*/
	
	//get the access level form the user id
	$accessid = $uArray['accesslevelid'];
	$alevel = $this->runquery("SELECT * FROM accesslevels WHERE accessid='$accessid'",'single');
	
	$utype[$alevel['accessid']] = $alevel['displayname'];
}
else
{
	$utype[''] = 'None Selected';
}

//$utype['admin'] = 'Product Administrator';
//$utype['superadmin'] = 'System Administrator';

//get the other access levels
$levels = $this->runquery("SELECT * FROM accesslevels ORDER BY accessid ASC",'multiple','all');
$levelcount = $this->getcount($levels);

for($t=1; $t<=$levelcount; $t++)
{
	$level = $this->fetcharray($levels);
	
	$utype[$level['accessid']] = $level['displayname'];
}

$loginform = new form();

$loginform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'550',
								  "map"=>array(1,1,2,1,2),
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
$loginform->addPassword('Verify Password:','vpassword',$uArray['password'],array('required'=>true));

$loginform->addSelect("Select User Type:",'utype','',$utype,array('required'=>true));
$loginform->addRadio("Enabled :", "enabled", $uArray['enabled'], array("yes", "no"),array('required'=>true));

$loginform->addButton('Save Login Details','submit',array("id"=>"myformbutton"));

if(isset($_POST['usersave'])&&($_POST['password']==$_POST['vpassword']))
{
	$loginSave = array(
					   'username' => $_POST['username'],
					   'password' => $_POST['password'],
					   'accesslevelid' => $_POST['utype'],
					   'sourceid' => $_POST['uid'],
					   'enabled' => $_POST['enabled']
					   );
	
	if($_POST['lid']!='')
	{
		$this->dbupdate('users',$loginSave,"userid='".$_POST['lid']."'");
		$this->inlinemessage('The login details have been updated and email sent','valid','no');
	}
	else
	{
		$this->dbinsert('users',$loginSave);
		$this->inlinemessage('The login details has been saved and email sent','valid','no');
	}
	
	$this->loadextraClass('registration');
		
		$register = new registration;
		
		//get the user details
		$user = $this->runquery("SELECT * FROM sbusers WHERE sbid='".$_POST['uid']."'",'single');
		
		$register->sendRegistration($user['email'],$_POST['username'],$_POST['password'],$user['fullname']);
}
else
{
	if(isset($_POST['usersave'])&&$_POST['password']!=$_POST['vpassword'])
	{
		$this->inlinemessage('Please verify your password','error');
	}
	
	$loginform->render();
}
?>