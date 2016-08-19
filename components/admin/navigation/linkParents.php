<?php
if(file_exists('../../../config.php'))
{
	require('../../../config.php');
}
else
{
	echo 'File NOT found';
}

//define('ABSOLUTE_PATH',$_SERVER['DOCUMENT_ROOT'].'/chmp/');
define('LOAD_POINT', 1 );

//require(ABSOLUTE_PATH.'includes/header.php');
include(ABSOLUTE_PATH.'classes/db.class.php');

//initialize the db object
$dbase = new database;
$dbase->loadclasses();

$values = explode('_',$_GET['values']);
$count = count($values);

foreach($values as $key=>$value)
{
	if($value!=0&&$value!='')
	{
		$link = $dbase->runquery("SELECT * FROM menus WHERE menuid='".$value."'",'single');
		$links .= ucfirst($link['linkname']).', ';
	}
	else
	{
		$links = 'None Selected';

		if($count > 1)
		{
			$links .= ' (Deselect the No Parent option)';
		}

		break;
	}

	$count++;
}

echo 'Link Parents: <strong>'.$links.'</strong>';
?>