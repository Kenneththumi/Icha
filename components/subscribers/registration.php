<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

        $link = new navigation();

	//the variables required
	$name = $_POST['fname'].' '.$_POST['lname'];
        $email = $_POST['email'];
        $mobileno = $_POST['mobile'];
	
        //remove the subscriber without its corresponding user details
        $users = $this->runquery("SELECT * FROM users WHERE username='".$email."'",'multiple');
        $userscount = $this->getcount($users);
        
        if($userscount==0)
        {
            $this->deleterow('subscribers','email',$email);
        }
        
        $usrchk = $this->runquery("SELECT count(*),subid FROM subscribers WHERE email = '".$email."'",'single');
        
	if($usrchk['count(*)']==0)
	{
		$name = $title.' '.$name;
		
		$userDetails = array();
		
		$userDetails['name'] = $name;
		$userDetails['mobileno'] = $mobileno;
		$userDetails['email'] = $email;
		
		$this->loadextraClass('registration');
		$reg = new registration;
		
		//register the subscriber
		$loginValues = $reg->registerSubscriber($userDetails);
                
                $username = $loginValues['username'];
                $password = $loginValues['password'];
		 
	}
	else
	{//if the user has been registered get his details
		$subid = $usrchk['subid'];
		
		$user = $this->runquery("SELECT * FROM users WHERE sourceid='".$subid."'",'single');
		
		$username = $user['username'];
		$password = $user['password'];
	}
	
	//login user
	$userlogin = new user;
	$loginState = $userlogin->login($username,$password);
	
        //var_dump($loginValues);exit;
        
        /*
	if($loginState=='login_pass')
	{
		if($usrchk['count(*)']==0)
		{
			$linkadd = '<br/>Check your email for your login settings';
		}
		
		//$this->inlinemessage('You have been successfully logged in'.$linkadd,'valid','no','yes');
                redirect($link->geturl().'&msgvalid=You_have_been_successfully_logged_in_'.$linkadd);
	}
        else
        {
            $this->inlinemessage('An error has occurred logging you in.'.$linkadd,'error','no','yes');
        }
         * 
         */
?>