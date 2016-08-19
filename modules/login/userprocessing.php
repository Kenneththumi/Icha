<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$dbuser = new user;

$username = $_POST['username'];
$password = $_POST['password'];

$login = $dbuser->login($username,$password);

if($login=='no_such_user')
{		
    if(!isset($_SESSION['login_trials']))
    {
        $_SESSION['login_trials'] = 1;
    }
    else
    {
        $_SESSION['login_trials']++;
    }
    
    redirect($_POST['url'].'&msgerror=Invalid_Username_or_Password');
}
else if($login=='login_pass'){
    
        if($_SESSION['usertype']=='admin'||$_SESSION['usertype']=='superadmin'){
            $type = 'admin';
        }
        else{
            $type = $_SESSION['usertype'];
        }
        
        $menuquery = $dbuser->runquery("SELECT menulink FROM menus WHERE menugroup='".navigation::getgroupid($_SESSION['usertype'])."' AND menus.home='yes' AND parentid='0' ORDER BY linkorder ASC");
        $menurow = $dbuser->fetchrow($menuquery);

        if($menuquery)
        {
            if($_SESSION['usertype']=='superadmin'
                    ||$_SESSION['usertype']=='adm'
                    ||$_SESSION['usertype']=='std'
                    ||$_SESSION['usertype']=='ins')
            {
                redirect($menurow[0]);
            }
            else
            {
                redirect($_POST['url']);
            }
        }
}
elseif($login=='userdisabled')
{
    redirect($_POST['url'].'&msgerror=Your_account_has_been_disabled');
}
elseif($login == 'student_expired'){
     redirect($_POST['url'].'&msgerror=Your_session_is_expired');
}
