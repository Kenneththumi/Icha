<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadstyles('categories');

$cats = $this->runquery("SELECT * FROM categories ORDER BY categoryname ASC");
$catscount = $this->getcount($cats);

echo '<h1 class="categoryheading">All Listed Categories</h1> <div id="titletext">* Click title to open</div>';			


$this->wrapscript('function divToggle(id)
				   {
						$("#"+id).slideToggle("fast");
					}
				  
				  $(document).ready(function() 
							{ 						
								$("div.subholder").hide();								
							});');

for($i=1; $i<=$catscount; $i++)
{
	$cat = $this->fetcharray($cats);	
	$cid = $cat['catid'];
	
	$subs = $this->runquery("SELECT * FROM subcategories WHERE catid='$cid'");
	$subscount = $this->getcount($subs);
	
	echo '<div id="catbox">';
	echo '<div id="cattitle" class="title'.$i.'" onClick="divToggle(\'div'.$i.'\')">';
	echo '<a href="?content=com_products&catid='.$cid.'"><span>'.$cat['categoryname'].'</span></a>';
	echo '<span id="smalltext">>></span>';
	echo '</div>';
	echo '<div id="div'.$i.'" class="subholder">';
	
	if($subscount==0)
	{
		echo 'No Subcategories listed under "'.$cat['categoryname'].'"';
	}
	else
	{
		for($j=1; $j<=$subscount; $j++)
		{
			$sub = $this->fetcharray($subs);
			
			echo '<a href="'.SITE_PATH.'?content=com_products&catid='.$cid.'&subcatid='.$sub['subcatid'].'">'.$sub['subcategoryname'].'</a><br/>';
		}
	}
	
	echo '</div>';
	echo '</div>';
}
?>