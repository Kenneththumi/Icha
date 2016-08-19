<?php
if($_SESSION['usertype']=='admin')
{
	$menuquery = $dbase->runquery("SELECT * FROM menus WHERE menutype = 'admin' ORDER BY linkorder ASC");
	$menucount = $dbase->getcount($menuquery);
}
else if($_SESSION['usertype']=='buyer')
{
	$buyerquery = $dbase->runquery("SELECT * FROM menus WHERE menutype = 'buyer' ORDER BY linkorder ASC");
	$buyercount = $dbase->getcount($buyerquery);
	
	
	//include the frontend menus
	$menuquery = $dbase->runquery("SELECT * FROM menus WHERE menutype = 'frontend' ORDER BY linkorder ASC");
	$menucount = $dbase->getcount($menuquery);
	
	$groupquery = $dbase->runquery("SELECT * FROM categorygroups ORDER BY groupname ASC");
	$groupcount = $dbase->getcount($groupquery);
}
else if($_SESSION['usertype']=='seller')
{
	$menuquery = $dbase->runquery("SELECT * FROM menus WHERE menutype = 'seller' ORDER BY linkorder ASC");
	$menucount = $dbase->getcount($menuquery);
}
else
{
	$menuquery = $dbase->runquery("SELECT * FROM menus WHERE menutype = 'frontend' ORDER BY linkorder ASC");
	$menucount = $dbase->getcount($menuquery);
	
	$groupquery = $dbase->runquery("SELECT * FROM categorygroups ORDER BY groupname ASC");
	$groupcount = $dbase->getcount($groupquery);
}

if(isset($buyercount))
{
	for($i=1; $i<=$buyercount; $i++)
	{
		$buyerarray = $dbase->fetcharray($buyerquery);
			
		echo '<a href="'.$buyerarray['menulink'].'">[ '.$buyerarray['menuname'].' ]</a>';
	}
	echo '<br/><br/>';
}

if(isset($menucount))
{
	for($i=1; $i<=$menucount; $i++)
	{
		$menuarray = $dbase->fetcharray($menuquery);
			
		echo '<a href="'.$menuarray['menulink'].'">[ '.$menuarray['menuname'].' ]</a>';
	}
}

if($groupcount>=1)
{
	echo '<ul id="dovetailmenu" class="dropdown">';
	for($k=1; $k<=$groupcount; $k++)
	{
		$grouparray = $dbase->fetcharray($groupquery);
		$namestrip = strtolower(str_replace(' ','',$grouparray['groupname']));
			
			$groupid = $grouparray['groupid'];
			
			$catquery = $dbase->runquery("SELECT * FROM categories WHERE groupid = '$groupid' ORDER BY categoryname ASC");
			$catcount = $dbase->getcount($catquery);
			
			if($catcount>=1)
			{
				$indicator = '  [+]';
			}
			
			echo '<li class="menu"><a href="?frontend=viewgroup&group='.$grouparray['groupid'].'">[ '.$grouparray['groupname'].' ]'.$indicator.'</a>';
			unset($indicator);
			
			if($catcount>=1)
			{
				echo '<ul class="links">';
				for($j=1; $j<=$catcount; $j++)
				{
					$catarray = $dbase->fetcharray($catquery);
					$catstrip = strtolower(str_replace(' ','',$catarray['categoryname']));
					
					echo '<li><a href="?frontend=viewcategory&category='.$catarray['catid'].'">[ '.$catarray['categoryname'].'</a></li>';
				}
				echo '</ul>';
			}
			echo '</li>';
	}
	echo '</ul>';
}
