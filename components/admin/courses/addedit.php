<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadstyles('backoffice');
$this->loadplugin('classForm/class.form');

$catid = $_GET['catid'];

if(isset($_GET['catid']))
{
	$cid = $category['categoryid'];
	$cname = $this->runquery("SELECT * FROM categories WHERE catid='$catid'",'multiple','all',$_GET['pageno'],'read');
	$cArray = $this->fetcharray($cname);
}

$categoryform = new form();

$categoryform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "map"=>array(1,1,1,1,2),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$categoryform->addHidden('articlesave','articlesave');
$categoryform->addHidden('aid',$catid);

$categoryform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Site Information: Add/Edit Article Categories</h1> </td></tr></table>');

$categoryform->addTextbox('Category Name:','catname',$cArray['categoryname'],array('required'=>true));
$categoryform->addRadio("Enabled :", "enabled", $cArray['enabled'], array("yes", "no"),array('required'=>true));

$categoryform->addButton('Save Category','submit',array("id"=>"myformbutton"));


if(isset($_POST['articlesave']))
{
	$save = array(
				  'categoryname'=>$_POST['catname'],
				  'enabled'=>$_POST['enabled']
				  );
	
	if($_POST['aid']=='')
	{
		$ncatname = $_POST['catname'];
		$ncat = $this->runquery("SELECT count(*) FROM categories WHERE categoryname='$ncatname'",'single');
		
		if($ncat['count(*)']>=1)
		{
			$this->inlinemessage('The category already exists','error','yes');
			$categoryform->render();
		}
		else
		{
			$this->dbinsert('categories',$save);
			
			$this->inlinemessage('The category has been saved','valid','no');
		}
	}
	else
	{
		$aid = $_POST['aid'];
		$this->dbupdate('categories',$save,"catid='$aid'");
		
		$this->inlinemessage('The category has been saved','valid','no');
	}
	
	
}
else
{
	$categoryform->render();
}
?>