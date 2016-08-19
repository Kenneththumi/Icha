<?php
if($_SERVER['HTTP_HOST']!='localhost')
{
	define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/');
}
else
{
	define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/chmp/');
}

define('LOAD_POINT', 1 );

include_once(ABSOLUTE_PATH.'classes/db.class.php');
$dbase = new database;

$status = explode('_',$_GET['status']);

$sid = $status[0];
$current = $status[1];

if($current=='no')
{
	$newstatus = 'yes';
}
elseif($current=='yes')
{
	$newstatus = 'no';
}

$statuschg = array('enabled'=>$newstatus);

if($dbase->dbupdate('users',$statuschg,"sourceid='$sid'"))
{
	echo $newstatus;
}

?>