<?php

//prevents direct access of these files

defined('LOAD_POINT')or die ('RESTRICTED ACCESS');



$mname = $_GET['mname'];



$menu = $this->runquery("SELECT linkname FROM menus WHERE menugroup='$mname' ORDER BY linkorder ASC",'multiple','all');

$menucount = $this->getcount($menu);



if(isset($_POST['menusave']))

{

	$mname = $_POST['mname'];

	

	foreach($_POST['menusort'] as $key=>$value)

	{

		$mSave = array('linkorder' => $key,

					   'linkname'=> $value);

		

		$this->dbupdate('menus',$mSave,"linkname='".$value."' AND menugroup='$mname'");

	}

	

	$this->inlinemessage('The New Order has been saved.','valid','yes');

}



$this->loadstyles('categories');

$this->loadplugin('classForm/class.form');



$this->loadscripts();



$sortlist = array();



for($i=1; $i<=$menucount; $i++)

{

	$menugroup = $this->fetcharray($menu);

	array_push($sortlist,$menugroup['linkname']);

}



$orderform = new form;



$orderform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',

								  "width"=>'580',

								  "preventJQueryLoad" => true,

								  "preventJQueryUILoad" => true,

								  "action"=>''

								  ));



$orderform->addHidden('menusave','menusave');

$orderform->addHidden('mname',$mname);



$orderform->addHTML('<h1>Change Menu Order</h1>');



$orderform->addSort('Rearrange Menu Items:','menusort',$sortlist);

$orderform->addButton('Save Order');



$orderform->render();

?>