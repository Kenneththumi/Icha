<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

echo '<h1>New Menu Group</h1>';

//process the form
if(isset($_POST['menusave']))
{
	$menusave = array('menuname'=>$_POST['menuname']);
	
	$this->dbinsert('menugroups',$menusave);
	
	$this->inlinemessage('The menu group has been saved','valid');
}

$menuform = new form;

$menuform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "preventJQueryLoad" => true,
								  "preventJQueryUILoad" => true,
								  "action"=>''
								  ));

$menuform->addHidden('menusave','menusave');

$menuform->addTextbox('Menu Name','menuname','',array("required"=>true));

$menuform->addButton('Save Menu');

$menuform->render();
?>