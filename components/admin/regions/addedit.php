<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadstyles('backoffice');
$this->loadplugin('classForm/class.form');

if(isset($_GET['regionid']))
{
	$rid = $_GET['regionid'];
	$rname = $this->runquery("SELECT * FROM region WHERE regionid='$rid'",'multiple','all',$_GET['pageno'],'read');
}

//adds the first item as the selected region
$regionArray = array();
if(isset($_GET['regionid']))
{	
	$regionid = $_GET['regionid'];
	$rArray = $this->fetcharray($rname);
	
	if($rArray['parentregionid']!=0)
	{
		$pid = $rArray['parentregionid'];
		$pQuery = $this->runquery("SELECT * FROM region WHERE regionid='$pid'",'single');
		
		$regionArray[$pQuery['regionid']] = $pQuery['region'];
	}
	else
	{
		$rid = '';
		$regionArray[$rid] = 'No Parent Selected';
	}
}
else
{
	$rid = '';
	$regionArray[$rid] = 'No Parent Selected';
}

$fullQ = $this->runquery("SELECT * FROM region ORDER BY region ASC");
$fullCount = $this->getcount($fullQ);

for($j=1; $j<=$fullCount; $j++)
{
	$fArray = $this->fetcharray($fullQ);
	$regionArray[$fArray['regionid']] = $fArray['region'];
}

//Add None as a last list item
if(isset($_GET['regionid']))
{
	$regionArray[111] = 'No Parent Selected';
}

$regionform = new form();

$regionform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'580',
								  "map"=>array(1,1,1),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$regionform->addHidden('articlesave','articlesave');
$regionform->addHidden('aid',$regionid);

$regionform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Site Information: Add/Edit Regions</h1> </td></tr></table>');

$regionform->addTextbox('Region Name:','regionname',$rArray['region'],array('required'=>true));
$regionform->addSelect('Select Parent Region:','regionparent','',$regionArray);
$regionform->addRadio("Enabled :", "enabled", $rArray['enabled'], array("yes", "no"),array('required'=>true));

$regionform->addButton('Save Region','submit',array("id"=>"myformbutton"));


if(isset($_POST['articlesave']))
{
	if($_POST['regionparent']=='111'||$_POST['regionparent']=='')
	{
		$parentid = 0;
	}
	else
	{
		$parentid = $_POST['regionparent'];
	}
	
	$save = array(
				  'region'=>$_POST['regionname'],
				  'parentregionid'=>$parentid,
				  'enabled'=>$_POST['enabled']
				  );
	
	if($_POST['aid']=='')
	{
		$nregionname = $_POST['regionname'];
		$ncat = $this->runquery("SELECT count(*) FROM region WHERE region='$nregionname'",'single');
		
		if($ncat['count(*)']>=1)
		{
			$this->inlinemessage('The region already exists','error','yes');
			$regionform->render();
		}
		else
		{
			$this->dbinsert('region',$save);
			
			$this->inlinemessage('The region has been saved. Please refresh your browser to see the new status','valid','no');
			$regionform->render();
		}
	}
	else
	{
		$aid = $_POST['aid'];
		$this->dbupdate('region',$save,"regionid='$aid'");
		
		$this->inlinemessage('The region has been saved. Please refresh your browser to see the new status','valid','no');
	}
	
	
}
else
{
	$regionform->render();
}
?>