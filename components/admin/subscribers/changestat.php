<?php
define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/');
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

if($dbase->dbupdate('subscribers',$statuschg,"subid='$sid'"))
{
	echo $newstatus;
}

?>