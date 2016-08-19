<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

$subscribersform = new form();

$subscribersform->setAttributes(array(
                                     "includesPath" => PLUGIN_PATH.'/classForm/includes',
                                     "width" => '95%',
                                     "noAutoFocus" => 1,
                                     "preventJQueryLoad" => true,
                                     "preventJQueryUILoad" => true,
                                     "preventDefaultCSS" => false,
                                     "action"=>'?content=mod_subscribers&folder=same&file=subscriberwizard&step=1',
                                     "id" => 'subscribeform'
                                     ));

$subscribersform->addHidden("step","subscribe");

$subscribersform->addHTML('<h1 class="ptitle">If not sign up today</h1><p>Start your ICHA learning experience today</p>');

$subscribersform->addTextBox('Full Names: ','fullname','',array('required'=>true));
$subscribersform->addEmail('Email Address: ','email','',array('required'=>true));

$subscribersform->addButton('Subscribe Now','submit',array("id"=>'myformbutton'));
$subscribersform->render();


?>