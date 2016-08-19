<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadplugin('classForm/class.form');

if(isset($_GET['alert']))
{
	$this->loadstyles('backoffice');
}

$artid = $_GET['artid'];

$article = $this->runquery("SELECT * FROM articles WHERE articleid='$artid'",'single');

$catArray = array();

if(isset($_GET['artid']))
{
	$cid = $article['categoryid'];
	if($cid!=0)
	{
		$cname = $this->runquery("SELECT * FROM categories WHERE catid='$cid'",'multiple','all',1,'read');
	}
	else
	{
		$cname = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
	}
	
	$rid = $article['regionid'];
	if($rid!=0)
	{
		$rname = $this->runquery("SELECT * FROM region WHERE regionid='$rid'",'multiple','all',1,'read');
	}
	else
	{
		$rname = $this->runquery("SELECT * FROM region ORDER BY regionid ASC",'multiple','all',1,'read');
	}
}
else
{
	$cname = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
	
	$rname = $this->runquery("SELECT * FROM region ORDER BY regionid ASC",'multiple','all',1,'read');
}

$cCount = $this->getcount($cname);
$rCount = $this->getcount($rname);
	
//adds the first item as the selected category
if($cCount>=1)
{
	if(isset($_GET['artid'])&&$cid!=0)
	{				
		$cArray = $this->fetcharray($cname);
		$catArray[$cid] = $cArray['categoryname'];
		
		$cname = $this->runquery("SELECT * FROM categories ORDER BY catid ASC",'multiple','all',1);
		$cCount = $this->getcount($cname);
	}
	else
	{
		$catArray[''] = 'All ICHA Categories';
	}
}

for($j=0; $j<=($cCount-1); $j++)
{
	$caArray = $this->fetcharray($cname);
	$catArray[$caArray['catid']] = $caArray['categoryname'];
}

//compile regions
if($rCount>=1)
{
	if(isset($_GET['artid'])&&$rid!=0)
	{					
		$rArray = $this->fetcharray($rname);
		$reArray[$rArray['regionid']] = $rArray['region'];
		
		$rname = $this->runquery("SELECT * FROM region ORDER BY regionid ASC",'multiple','all',1,'read');
		$rCount = $this->getcount($rname);
	}
	else
	{
		$reArray[''] = 'No Region Selected';
	}
}

for($i=0; $i<=($rCount-1); $i++)
{
	$rArray = $this->fetcharray($rname);	
	$reArray[$rArray['regionid']] = $rArray['region'];
}

//Add None as a last list item
if(isset($_GET['artid']))
{
	$catArray[111] = 'All ICHA Categories';
	$reArray[111] = 'No Region Selected';
	
	$articleDate = date('F j, Y',$article['publishdate']);
	$expiryDate = date('F j, Y',$article['expirydate']);
}
else
{
	$articleDate = 'Click to select Date...';
	$expiryDate = 'Click to select Date...';
}

//get the article titles
$articles = $this->runquery("SELECT * FROM articles ORDER BY title ASC",'multiple','all');
$articleTCount = $this->getcount($articles);

$titleArray = array();
if(isset($article['parentid']))
{
	$pid = $article['parentid'];
	$parent = $this->runquery("SELECT * FROM articles WHERE articleid='$pid'",'single');
	
	$titleArray[$pid] = $parent['title'];
}
else
{
	$titleArray['0'] = 'Select Parent Article Title';
}

for($k=1; $k<=$articleTCount; $k++)
{
	$atitle = $this->fetcharray($articles);
	$titleArray[$atitle['articleid']] = $atitle['title'];
}

//get the journal source
if(strstr($article['body'],'[source]')!='')
{
	$source = $this->findText('[source]','[|source]',$article['body']);
	$spl_body = explode('[|source]',$article['body']);
	$body = $spl_body[1];
}
else
{
	$body = $article['body'];
}

//get the article type
if($article['atype']=='')
{
	$typeArray[''] = 'Select Type';
	$typeArray['special'] = 'Special Posting';
	$typeArray['technical'] = 'Recent Technical Update';
}
else
{
	if($article['atype']=='special')
	{
		$typeArray['special'] = 'Special Posting';
	}
	elseif($article['atype']=='technical')
	{
		$typeArray['technical'] = 'Recent Technical Update';
	}
	
	$typeArray['special'] = 'Special Posting';
	$typeArray['technical'] = 'Recent Technical Update';
}

$articleform = new form();

$articleform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
								  "width"=>'650',
								  "map"=>array(1,1,2,2,1,2,1,1,1),
								  "preventJQueryLoad" => false,
								  "preventJQueryUILoad" => false,
								  "action"=>''
								  ));

$articleform->addHidden('articlesave','articlesave');
$articleform->addHidden('aid',$artid);

$articleform->addHTML('<table width="100%"><tr class="producttable"><td><h1>Site Information: Add/Edit Article</h1> </td></tr></table>');

$articleform->addTextbox('Article Title','atitle',$article['title'],array('required'=>true));

$articleform->addSelect("Select Article Category:",'acats','',$catArray);
$articleform->addSelect("Select Article Region:",'aregs','',$reArray);

$articleform->addDate("Publish Date of Article:", "articledate",$articleDate);
$articleform->addDate("Expiry Date of Article:", "expirydate",$expiryDate);

$articleform->addTextbox('Full Journal Source','journalsource',$source);

$articleform->addSelect("Select Article Type:",'atype','',$typeArray);
$articleform->addSelect("Select Parent Article:",'aparent','',$titleArray);

$articleform->addWebEditor('Article Body','abody',$body,array('required'=>true));
$articleform->addRadio("Enabled :", "enabled", $article['enabled'], array("Yes", "No"),array('required'=>true));

$articleform->addButton('Save Article');

if(isset($_POST['articlesave']))
{
	$now = time();
	if($_POST['aregs']=='111'||$_POST['aregs']=='')
	{		
		$regid = 0;
	}
	else
	{
		$regid = $_POST['aregs'];
	}
	
	if($_POST['acats']=='111'||$_POST['acats']=='')
	{
		$catid = 0;
	}
	else
	{
		$catid = $_POST['acats'];
	}
	
	if($_POST['articledate']=='Click to select Date...')
	{
		$time = time();
	}
	else
	{
		$time = strtotime($_POST['articledate']);
	}
	
	if($_POST['expirydate']=='Click to select Date...')
	{
		$etime = '0';
	}
	else
	{
		$etime = strtotime($_POST['expirydate']);
	}
	
	if($now > $time)
	{
		$save = array(
					  'title'=>$_POST['atitle'],
					  'body'=>($_POST['journalsource']!='' ? '[source]'.$_POST['journalsource'].'[|source]' : '').$_POST['abody'],
					  'categoryid'=>$catid,
					  'regionid'=>$regid,
					  'publishdate'=>$time,
					  'expirydate'=>$etime,
					  'enabled'=>$_POST['enabled'],
					  'atype'=>$_POST['atype'],
					  'parentid'=>$_POST['aparent']
					  );
		
		if($_POST['aid']=='')
		{		
			$this->dbinsert('articles',$save);		
		}
		else
		{
			$aid = $_POST['aid'];
			$this->dbupdate('articles',$save,"articleid='$aid'");
		}
		
		$this->inlinemessage('The article has been saved.','valid');
		$articleform->render();
	}
	else
	{
		$this->inlinemessage('You cant select a date in the future.','error');
		$articleform->render();
	}
}
else
{
	$articleform->render();
}
?>