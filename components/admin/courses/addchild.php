<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadplugin('classForm/class.form');
$this->loadstyles('backoffice');

$childid = $_GET['childid'];
$catid = $_GET['catid'];

$child = $this->runquery("SELECT * FROM subcategories WHERE subcatid='$childid'",'single');

$catArray = array();

if(isset($_GET['catid']))
{
	$cid = $_GET['catid'];
	$cname = $this->runquery("SELECT * FROM categories WHERE catid='$cid'",'multiple','all',1,'read');
}
else
{
	$cname = $this->runquery();
}

$cCount = $this->getcount($cname);
	
//adds the first item as the selected category
if($cCount>=1)
{
	$cArray = $this->fetcharray($cname);
	$catArray[$cid] = $cArray['categoryname'];	
}

$category = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
$categoryCount = $this->getcount($category);

for($j=0; $j<=($categoryCount-1); $j++)
{
	$caArray = $this->fetcharray($category);
	$catArray[$caArray['catid']] = $caArray['categoryname'];
}

$childform = new form();

$childform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "map"=>array(1,1,1,1,1,1),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$childform->addHidden('childsave','childsave');
$childform->addHidden('cid',$childid);

$childform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Add/Edit Child Category</h1> </td></tr></table>');
$childform->addHTML('<table width="100%"><tr class="producttable"><td><h3>Parent Category: '.$cArray['categoryname'].'</h3> </td></tr></table>');

$childform->addTextbox('Child Category Name','cname',$child['subcategoryname'],array('required'=>true));

$childform->addSelect("Select Parent Category:",'acats','',$catArray);
$childform->addRadio("Enabled :", "enabled", "", array("Yes", "No"),array('required'=>true));

$childform->addButton('Save Child');

if(isset($_POST['childsave']))
{	
	$child = $_POST['cname'];
	
	$childChk = $this->runquery("SELECT count(*) FROM subcategories WHERE subcategoryname='$child'",'single');
	
	if($childChk['count(*)']==0)
	{
		$save = array(
					  'subcategoryname' => $_POST['cname'],
					  'catid' => $_POST['acats'],
					  'enabled' => 'yes'
					  );
		
		if($_POST['cid']=='')
		{		
			$this->dbinsert('subcategories',$save);
			
		}
		else
		{
			$cid = $_POST['cid'];
			$this->dbupdate('subcategories',$save,"childid='$cid'");
		}
		
		$this->inlinemessage('The child category has been saved.','valid');
	}
	else
	{
		$this->inlinemessage('The child category entered ALREADY exists.','error');
		$childform->render();
	}
}
else
{
	$childform->render();
}
?>