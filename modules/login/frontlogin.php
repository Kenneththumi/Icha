<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();
$url = 'UDJOdmJuUmxiblE5WTI5dFgyWnliMjUwY0dGblpRPT0=';

if(!isset($_SESSION['login_trials'])){
    
    $_SESSION['login_trials'] = 0;
}

$this->wrapscript("$(document).ready(function(){
                        $('a.launchlogin').fancybox({
                                'width': 400,
                                'height': 350,
                                'autoDimensions': false,
                                'autoScale': false,
                                'transitionIn': 'elastic',
                                'transitionOut': 'elastic',
                                'enableEscapeButton' : true,
                                'overlayShow' : true,
                                'overlayColor' : '#FFFFFF',
                                'overlayOpacity' : 0.9,
                                'scrolling': 'auto',
                                'hideOnOverlayClick': false
                                });
                         });");

$this->loadplugin('classForm/class.form');

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$loginform = new form();

$loginform->setAttributes(array(
                "includesPath"=> PLUGIN_PATH.'/classForm/includes',
                "width"=>'350px',
                "preventJQueryLoad" => true,
                "preventJQueryUILoad" => true,
                "map" => array(1,1,1),
                "action"=> SITE_PATH.'?content=mod_login&folder=same&file=userprocessing',
                "id" => 'userlogin'
            ));

$loginform->addHidden('cmd','submit');
$loginform->addHidden('url',$code->decrypt($url));

$loginform->addTextbox('Username or Registration Number','username','',array("required"=>true));
$loginform->addPassword('Password','password','',array("required"=>true));
$loginform->addHTML('<a href="'.SITE_PATH.'?content=mod_login&folder=same&file=forgotpassword&url='.$url.'&alert=yes" class="launchlogin">forgot password</a>');

$loginform->addButton('Login','submit',array("id"=>"myformbutton"));

$loginform->render();
