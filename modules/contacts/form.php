<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('classForm/class.form');

$link = new navigation();
$contactform = new form();

$contactform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                      "width"=>'95%',
                                      "map"=>array(2,1),
                                      "noAutoFocus" => 1,
                                      "preventJQueryLoad" => true,
                                      "preventJQueryUILoad" => true,
                                      "action"=>$link->returnByAlias('contacts-us'),
                                      "captchaTheme"=>'clean',
                                      "id"=>'contactform'
                                      ));

$contactform->renderHead();
$contactform->addHidden('smallform', 'smallform');

$contactform->addTextbox('Names', 'names', '',array('required'=>true));
$contactform->addTextbox('Email', 'email', '',array('required'=>true));

$contactform->addTextbox('Message or Enquiry','message');

$contactform->addButton('Send Feedback','submit');

$contactform->render();