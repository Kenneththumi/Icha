<?php
echo '<h1>Forgot Credentials</h1>';

$link = new navigation();

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$forgotform = '<form action="" method="post" name="forgotform" id="forgotform">
    <input name="url" type="hidden" value="'.SITE_PATH.'?content=mod_login&folder=same&file=frontlogin">
  <table width="95%" border="0" cellpadding="0">
  <tr>
  <td>Email address, you used for registration</td>
    </tr>
    <tr>
      <td><input name="email" type="text" id="email" style="width: 98% !important" size="30"></td>
    </tr>
    <tr>
    <td><p></p></td>
    </tr>
    <tr>
      <td><input type="submit" name="pwdsubmit" id="pwdsubmit" value="Send Credentials"></td>
    </tr>
  </table>
</form>';

if(isset($_POST['pwdsubmit']))
{
	if($_POST['usertype']!='none'||$_POST['user']!=''||$_POST['email']!='')
	{
		$member = new user;
                
		$email = mysql_real_escape_string($_POST['email']);
                
                //check subscriber
                $subchk = $this->runquery("SELECT * FROM subscribers WHERE email='$email'",'multiple');
                $subcount = $this->getcount($subchk);
                
                if($subcount >= '1')
                {
                    $sub = $this->fetcharray($subchk);
                    
                    $sid = $sub['subid'];
                    $utype = 'subscriber';
                }
                
                //check students
                $stdchk = $this->runquery("SELECT * FROM students WHERE emailaddress ='$email'",'multiple');
                $stdcount = $this->getcount($stdchk);
                
                if($stdcount >= '1')
                {
                    $std = $this->fetcharray($stdchk);
                    
                    $sid = $std['students_id'];
                    $utype = 'student';
                }
                
                //check contributors
                $conchk = $this->runquery("SELECT * FROM contributors WHERE emailaddress ='$email'",'multiple');
                $concount = $this->getcount($conchk);
                
                if($concount >= '1')
                {
                    $con = $this->fetcharray($stdchk);
                    
                    $con = $std['contributorid'];
                    $utype = 'instructor';
                }
				
		$source = $member->runquery("SELECT *, count(*) FROM users WHERE sourceid='$sid' AND usertype='$utype' ",'single');
         
                if($source['count(*)']=='0')
                {
                    $this->inlinemessage('The email has not been registred','error');
                }
                
		$pwd_html = '<html><body><b>Dear Member</b>
				<p>Your login details are shown below: <br/>
				Username: '.$source['username'].'<br/><br/>
                                Password: '.$source['password'].'<br/><br/>
				Please login into the system to enjoy our services. <a href="'.$_POST['url'].'">Click here to login into system</a></p>
				<p><b>Thanks</b></p>
				</body></html>';
				
		$pwd_text = '
Dear Member

Your login details are shown below:

Username: '.$source['username'].'
Password: '.$source['password'].'
    
To login into system, click the link below:
'.$_POST['url'].'

Please login into the system to enjoy our services. 

Thanks';

            $mail = $this->multipart_mail($_POST['email'],'ICHA Member Login Details',$pwd_html,$pwd_text,MAILFROM,SITENAME);
				
            $this->inlinemessage('Your credentials have been sent','valid');
	}
	else
	{
		redirect('?frontend=forgotpwd&msgerror=Please_fill_in_all_the_fields');
	}
}
else
{
	echo $forgotform;
}
?>