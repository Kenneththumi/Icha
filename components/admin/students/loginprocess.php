<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if(isset($_POST['usersave'])&&($_POST['password']==$_POST['vpassword']))
{
	$loginsave = array(
                           'username' => $_POST['username'],
                           'password' => $_POST['password'],
                           'accesslevelid' => $_POST['utype'],
                           'sourceid' => $_POST['id'],
                           'enabled' => $_POST['enabled'],
                           'usertype' => 'student'
                           );
        
	if($_POST['id']!='')
	{
            $usrchk = $this->runquery("SELECT * FROM users WHERE sourceid='".$_POST['id']."'",'multiple','all');
            $usrcount = $this->getcount($usrchk);
            
            if($usrcount>='1')
            {
		$this->dbupdate('users',$loginsave,"sourceid='".$_POST['id']."' AND usertype='student'");
		
            }
            else {
                $this->dbinsert('users',$loginsave);
            }
            
            $this->inlinemessage('The login details have been updated and email sent','valid','no');
	}
	else
	{
		$this->dbinsert('users',$loginsave);
		$this->inlinemessage('The login details has been saved and email sent','valid','no');
	}
	//$this->print_last_error();
        
	$this->loadextraClass('registration');
        $register = new registration;

        //get the user details
        $user = $this->runquery("SELECT * FROM students WHERE students_id='".$_POST['id']."'",'single');

        $register->sendRegistration($user['emailaddress'],$_POST['username'],$_POST['password'],$user['name']);
        
        redirect($_POST['url'].'&msgvalid=The_login_details_have_been_saved');
}
else
{
	if(isset($_POST['usersave'])&&$_POST['password']!=$_POST['vpassword'])
	{
            redirect($_POST['url'].'&msgerror=Please_make_sure_the_passords_match');
	}
}
?>