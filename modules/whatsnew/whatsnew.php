<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$artQuery = $this->runquery("SELECT * FROM articles WHERE lead != 'yes' AND enabled='Yes' AND categoryid!='19' ORDER BY publishdate DESC",'multiple',8);
$artCount = $this->getcount($artQuery);

$artArray = $this->fetcharray($artQuery);

//get the date difference
$timenow = time();
$publishdate = $artArray['publishdate'];
$dateDiff = $timenow-$publishdate;
		
$days = floor(((($dateDiff/60)/60)/24));

//echo '<img src="'.STYLES_PATH.'ichatemplate/images/subscribetoday.png" width="202" height="45" />';
echo '<h1 class="moduleheading">What\'s New</h1>';

if($days <= 7)
{
	echo '<table width="95%" border="0" cellpadding="5">';
	
	for($i=1; $i<=$artCount; $i++)
	{
		$artArray = $this->fetcharray($artQuery);
		
		echo '<tr>
			<td>
			<a href="?content=com_articles&artid='.$artArray['articleid'].'" class="subscribe"><strong>'.ucfirst($artArray['title']).'</strong></a>';
			
			if($artArray['hits']!=0)
			{
				echo '<p class="articleDate">'.date('l, jS \of F Y',$artArray['publishdate']).'</p>';
			}
			
			echo '</td>
		  </tr>';
	}
	echo '</table>';
}
else
{
	$artCount = 5;
	echo '<table width="95%" border="0" cellpadding="5">';
	
	for($i=1; $i<=$artCount; $i++)
	{
		$artArray = $this->fetcharray($artQuery);
		
		echo '<tr>
			<td><a href="?content=com_articles&artid='.$artArray['articleid'].'" class="subscribe"><strong>'.ucfirst($artArray['title']).'</strong></a>
			<p class="articleDate">'.date('l, jS \of F Y',$artArray['publishdate']).'</p>
			</td>
		  </tr>';
	}
	echo '</table>';
}

?>