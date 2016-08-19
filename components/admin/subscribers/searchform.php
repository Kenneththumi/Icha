<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//get the categories
$categoryclass = new category;

$categories = $this->runquery("SELECT * FROM categories",'multiple','all');
$categorycount = $this->getcount($categories);

$categorylist = array(''=>'Select Category');
for($t=1; $t<=$categorycount; $t++)
{
    $category = $this->fetcharray($categories);
    
    $categorylist[$category['catid']] = $category['categoryname'];
}

$this->loadplugin('classForm/class.form');
$this->loadstyles('categories');

$searchform = new form();

$searchform->setAttributes(array(
                                 "includesPath" => PLUGIN_PATH.'/classForm/includes',
                                 "preventXHTMLStrict" => 1,
                                 "noAutoFocus" => 1,
                                 "map"=>array(2,1),
                                 "preventJQueryLoad" => true,
                                 "preventJQueryUILoad" => true,
                                 "preventDefaultCSS" => false,
                                 "action"=>''
                                 ));

$searchform->addHidden("cmd","search");

$searchform->addTextBox('Name: ','name','');
$searchform->addTextBox('Email','email','');

$searchform->addSelect('Category subscribed','category','',$categorylist);

$searchform->addButton('Search','submit',array("id"=>"myformbutton"));

$searchform->render();