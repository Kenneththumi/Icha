<?php
require('../../../config.php');
define('LOAD_POINT', 1 );

include_once(ABSOLUTE_PATH.'classes/db.class.php');
 
$dbase = new database;

$sentname = $_GET['q'];
$namefind = $dbase->runquery("SELECT articleid,title FROM articles ORDER BY publishdate DESC",'multiple','all');
$namecount = $dbase->getcount($namefind);

$name = array();
for($j=1; $j<=$namecount; $j++)
{
	$namearray = $dbase->fetcharray($namefind);
	$name[$j] = '['.$namearray['articleid'].'] '.$namearray['title'];
}

if($namecount==0)
{
	$name[0] = 'No Results Found';
	echo $name[0];
}
else
{
	$error = 0;
	foreach($name as $key=>$rname)
	{
		if(strstr(strtolower($rname),$sentname)!='')
		{
			echo $rname."\n";
		}
		else
		{
			$error++;
		}
	}
}
?>