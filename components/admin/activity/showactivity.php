<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

echo '<h1>Site Article Activity</h1>';

$hits = $this->runquery("SELECT sum(hits) FROM articles",'single');

if($hits['sum(hits)']==0)
{
	$tHits = 0;
}
else
{
	$tHits = $hits['sum(hits)'];
}

$this->loadplugin('classForm/class.form');

$searchform = new form;

$searchform->setAttributes(array(
							"includesPath"=> PLUGIN_PATH.'/classForm/includes',
							"preventJQueryLoad" => true,
							"preventJQueryUILoad" => true,
							"action"=>$link->geturl(),
							"class"=>'addproduct'
							));

$searchform->addHidden('artbutton','artbutton');
$searchform->addTextbox('Enter Article Name:','artname','',array('id'=>'namesearch'));
$searchform->addDateRange("Date Range of Article:", "artdate",'Click to select Article Date Range ...');

$searchform->addButton('Search Articles','submit',array('id'=>'myformbutton'));

$searchform->render();

echo '<p>Total no of hits for all articles: '.$tHits.'</p>';

echo '<h3>Articles by hits ranking</h3>';

if(isset($_POST['artbutton'])&&($_POST['artname']!=''||$_POST['artdate']!='Click to select Article Date Range ...'))
{
	if($_POST['artname']!='')
	{
		$title = "title LIKE '%".$_POST['artname']."%'";
		$searchStr .= 'Name: '.$_POST['artname'];
	}
	
	if($_POST['artdate']!='Click to select Article Date Range ...')
	{
		if(strstr($_POST['artdate'],' - ')!='')
		{
			$splitdate = explode(' - ',$_POST['artdate']);
			$condition .= 'publishdate > '.strtotime($splitdate[0]).' AND publishdate < '.strtotime($splitdate[1]).' ';
			$searchStr .= 'Date Range: '.$_POST['artdate'].', ';
		}
		else
		{
			$sdate = $_POST['artdate'];
			$condition .= 'publishdate > '.strtotime($sdate).' ';
			$searchStr .= 'Date Range: '.$_POST['artdate'].', ';
		}
		
		$getArticles = $this->runquery("SELECT * FROM articles WHERE ".$condition,'multiple','all');
		$getCount = $this->getcount($getArticles);
		
		if($getCount > 1)
		{
			for($r=1; $r<=$getCount; $r++)
			{
				$getArray = $this->fetcharray($getArticles);
				
				$idList .= $getArray['articleid'].', ';
			}
			
			$idList = rtrim($idList,', ');
			$articleList = 'articleid IN ('.$idList.')';
		}
		else
		{
			$getArray = $this->fetcharray($getArticles);
			$articleList = "articleid = ".$getArray['articleid']."'";
		}
	}
	
	if($_POST['artname']!=''&&$_POST['artdate']!='Click to select Article Date Range ...')
	{
		$qCondition = $title." AND ".$articleList;
	}
	elseif($_POST['artname']==''||$_POST['artdate']!='Click to select Article Date Range ...')
	{
		$qCondition = $articleList;
	}
	elseif($_POST['artname']!=''||$_POST['artdate']=='Click to select Article Date Range ...')
	{
		$qCondition = $title;
	}
	
	$name = $_POST['artname'];

	$top10 = $this->runquery("SELECT * FROM articles WHERE ".$qCondition." ORDER BY hits DESC",'multiple','all');
}
else
{
	$top10 = $this->runquery("SELECT * FROM articles ORDER BY hits DESC",'multiple',10,$_GET['pageno']);
	$link->createPgNav("SELECT * FROM articles ORDER BY hits DESC",10);
}

$topCount = $this->getcount($top10);

//diaplay search title
if(isset($_POST['artbutton']))
{
	echo '<h3>Search result for "'.$searchStr.'"</h3>';
	
	if($topCount==0)
	{
		$this->inlinemessage('<a href="'.$link->urlreturn('site activity','','no').'">Back to full listing</a>','valid');
	}
}

if($topCount==0&&isset($_POST['artbutton']))
{
	$this->inlinemessage('No articles found matching: '.$_POST['artname'],'valid');
}


if($topCount!=0)
{
	echo '<table width="100%" border="0" cellpadding="10" class="tablelist">
	  <tr class="tabletitle">
		<td>Article Title</td>
		<td>No of Hits</td>
	  </tr>';
	 
	 for($j=1; $j<=$topCount; $j++)
	 {
		 $topArray = $this->fetcharray($top10);
		 
		 echo '<tr class="item">
				<td>'.$topArray['title'].'</td>
				<td>'.$topArray['hits'].'</td>
			  </tr>';
	 }
	 
	echo '</table>';
}
else
{
	$this->inlinemessage('There are currently no articles to rate','valid');
}
?>