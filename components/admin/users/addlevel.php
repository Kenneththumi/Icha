<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

//process editing
if(isset($_GET['levelid']))
{
	$lvlid = $_GET['levelid'];
	$level = $this->runquery("SELECT * FROM accesslevels WHERE accessid='$lvlid'",'single');
}

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

//get group
$groups = $this->runquery("SELECT * FROM menugroups ORDER BY groupid ASC",'multiple','all');
$groupcount = $this->getcount($groups);

if(!isset($_GET['levelid']))
{
    $group_array = array(''=>'Select Group to Link');
}

for($i=1; $i<=$groupcount; $i++)
{
	$group = $this->fetcharray($groups);
	
	$group_array[$group['groupid']] = $group['menuname'];
}

$levelform = new form;
$levelform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
				"preventJQueryLoad" => false,
				"preventJQueryUILoad" => false,
				"action"=>''
				));

$levelform->addHidden('levelsave','levelsave');

if(!isset($_GET['levelid']))
{
	$levelform->addHTML('<h1>Add Access Level</h1>');
}
else
{
	$levelform->addHidden('levelid',$_GET['levelid']);
	$levelform->addHTML('<h1>Edit '.ucfirst($level['displayname']).'</h1>');
}

$levelform->addTextbox('Enter Access Level Name','alevel',$level['displayname'],array('required'=>true));

$levelform->addTextbox('Enter Access Level Alias','alias',$level['accesslevel']);

$levelform->addSelect('Please select the group to link to','group','',$group_array,array('required'=>true));
$levelform->addSelect('Deletion Allowed', 'deletion', $level['deletionallowed'], array('yes','no'));

$levelform->addButton('Save Access Level');

if(isset($_POST['levelsave'])&&isset($_POST['alevel']))
{
	//this is for processing alias
	$aname = str_replace(' ','',mysql_escape_string($_POST['alevel']));
	
	$saveLinks = array(
					   'displayname' => $aname,
					   'accesslevel' => ($_POST['alias']=='' ? strtolower($aname) : $_POST['alias']),
					   'deletionallowed' => $_POST['deletion']
					   );
	
	if(!isset($_POST['levelid']))
	{
		$this->dbinsert('accesslevels',$saveLinks);
		
		$accessid = mysql_insert_id();
		
		$group_details = $this->runquery("SELECT accesslevelid FROM menugroups WHERE groupid='".$_GET['group']."'",'single');
		
		if(strpos($group_details['accesslevelid'],',')!=0)
		{
			$accessids = explode(',',$group_details['accesslevelid']);
			
			//add the new value
			array_push($accessids,$accessid);
			$new_ids = implode(',',$accessids);
			
			$group_update = array('accesslevelid'=>$new_ids);
			
			$this->dbupdate('menugroups',$group_update,"groupid='".$_GET['group']."'");
		}
	}
	else
	{
		//update the accesslevels
		$this->dbupdate('accesslevels',$saveLinks,"accessid='".$_POST['levelid']."'");
		
		$accessid = mysql_insert_id();
		
		//update the menugroup with the current access level id
		$group_update = array('accesslevelid'=>$_POST['levelid']);
		$this->dbupdate('menugroups',$group_update,"groupid='".$_POST['group']."'");
	}
	//$this->print_last_error();
	
	$this->inlinemessage('The access level has been saved','valid');
	$levelform->render();
	
}
else
{
	if(isset($_POST['levelsave'])&&!isset($_POST['alevel']))
	{
		$this->inlinemessage('Please enter the level name','error');
	}
	
	$levelform->render();
}
?>