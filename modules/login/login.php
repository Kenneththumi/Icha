<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//the params can array(folder, filename) or a string
//$this->loadcss(array('ichatemplate/login','loginstyles'));

//load scripts
$this->loadscripts();

$dbuser = new user;
$link = new navigation;

if($_GET['task']=='logout'&&isset($_GET['logid']))
{	
    $logout = $dbuser->logout($_GET['logid']);

    if($logout=='sessionout')
    {
        redirect('?content=com_frontpage');
    }
}

$this->sitemessages();

echo '<div id="loginpage">
  <div id="loginlogo"><img src="'.STYLES_PATH.'ichatemplate/login/images/loginlogo.png" width="388" height="221" /></div>
  <div id="logincontent">';

$this->loadplugin('classForm/class.form');

$loginform = new form();

$loginform->setAttributes(array(
                                "includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                "width"=>'90%',
                                "map"=>array(1,1,1,1),
                                "preventJQueryLoad" => false,
                                "preventJQueryUILoad" => false,
                                "preventDefaultCSS" => false,
                                "action"=>''
                                ));

$loginform->addHidden('cmd','submit');
$loginform->addHidden('url',$link->geturl());

$loginform->addTextbox('Username','username','', array("required"=>true));
$loginform->addPassword('Password','password','',array("required"=>true));

$loginform->addButton('Login','submit',array("id"=>"myformbutton"));

if(isset($_POST['cmd'])){
    
    include (ABSOLUTE_PATH.'modules/login/userprocessing.php');
}
else{
    
    $loginform->render();
}

echo '</div>
</div>';
