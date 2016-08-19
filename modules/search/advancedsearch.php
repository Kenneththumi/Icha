<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$cname = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
$rname = $this->runquery("SELECT * FROM region ORDER BY regionid ASC",'multiple','all',1,'read');

$cCount = $this->getcount($cname);
$rCount = $this->getcount($rname);

if(!isset($_GET['catid']))
{
	$catArray[''] = 'No Category Selected';
}
else
{
	$cid = $_GET['catid'];
	
	$cats = $this->runquery("SELECT * FROM categories WHERE catid='$cid'",'single');
	
	$catArray[$cid] = $cats['categoryname'];
}

$reArray[''] = 'No Region Selected';

for($j=0; $j<=($cCount-1); $j++)
{
	$caArray = $this->fetcharray($cname);
	$catArray[$caArray['catid']] = $caArray['categoryname'];
}

for($i=0; $i<=($rCount-1); $i++)
{
	$rArray = $this->fetcharray($rname);	
	$reArray[$rArray['regionid']] = $rArray['region'];
}

$this->loadplugin('classForm/class.form');

$searchform = new form();

$searchform->setAttributes(array(
								 "includesPath" => PLUGIN_PATH.'/classForm/includes',
								 "width" => 600,
								 "noAutoFocus" => 1,
								 "map"=>array(1,1,1,1,1,1,1),
								 "preventJQueryLoad" => true,
								 "preventJQueryUILoad" => true,
								 "action"=>'?content=mod_search&folder=same&file=searchresults&stype=advanced'
								 ));

$searchform->addHidden("cmd","search");

if(!isset($_GET['catid']))
{
	$searchform->addHTML('<h1 class="articleTitle">Advanced Article Search</h1>');
}
else
{
	$searchform->addHTML('<h1 class="articleTitle">'.ucfirst($cats['categoryname']).' Article Search</h1>');
}

$searchform->addTextBox('Name of Article','articlesearch','');
$searchform->addTextBox('ICHA Number: ','csuno','');

$searchform->addSelect("Select Article Category:",'acats','',$catArray);
$searchform->addSelect("Select Article Region:",'aregs','',$reArray);

$searchform->addDate("Date of Article:", "articledate",'Click to select Article Date...');

$searchform->addButton('Search Articles','submit',array("id"=>"myformbutton"));

$searchform->render();
?>