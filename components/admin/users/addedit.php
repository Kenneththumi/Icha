<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

$usrid = $_GET['userid'];

if(isset($_GET['userid']))
{
	$cname = $this->runquery("SELECT * FROM sbusers WHERE sbid='$usrid'",'multiple','all',$_GET['pageno'],'read');
	$uArray = $this->fetcharray($cname);
}

$userform = new form();

$userform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "map"=>array(1,1,1,1),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$userform->addHidden('usersave','usersave');
$userform->addHidden('uid',$usrid);

$userform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Add/Edit System Users</h1> </td></tr></table>');

$userform->addTextbox('Full Name of User:','fullname',$uArray['fullname'],array('required'=>true));
$userform->addTextbox('Email:','email',$uArray['email'],array('required'=>false));

$userform->addButton('Save User','submit',array("id"=>"myformbutton"));


if(isset($_POST['usersave']))
{
	$save = array(
				  'fullname'=>$_POST['fullname'],
				  'email'=>$_POST['email']
				  );
	
	if($_POST['uid']=='')
	{
		$username = $_POST['fullname'];
		$user = $this->runquery("SELECT count(*) FROM sbusers WHERE fullname='$username'",'single');
		
		if($user['count(*)']>=1)
		{
			$this->inlinemessage('The user already exists','error','yes');
			$userform->render();
		}
		else
		{
			$this->dbinsert('sbusers',$save);
			$uid = $this->previd('sbusers','sbid');
			
			redirect($link->urlreplace('file=addedit','file=userlogin').'&alert=yes&uid='.$uid.'&msgvalid=Enter_the_user_login_details');
		}
	}
	else
	{
		$uid = $_POST['uid'];
		$this->dbupdate('sbusers',$save,"sbid='$uid'");
		
		$this->inlinemessage('The user details has been changed','valid','no');
		//redirect($link->urlreplace('file=addedit','file=userlogin').'&alert=yes&uid='.$uid);
	}	
}
else
{
	$userform->render();
}
?>