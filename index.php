<?php
require('config.php');

require_once('includes/header.php');
include_once('classes/db.class.php');

//phpinfo();
error_reporting(E_ALL & ~E_NOTICE);

//initialize the db object
$dbase = new database;

//loads all the core files required for the app
$dbase->loadclasses();

//$dbase->catchFatalErrors();

//initialize the template object
$skin = new template;

//initialize the navigation object
$nav = new navigation;

//the links should be sending the ids for each category rather than the name
if(isset($_GET['alert']))
{
	if(isset($_GET['content'])){
            $name = $_GET['content'];
	}
	else if(isset($_GET['student'])){
            $name = $_GET['student'];
	}
	else if(isset($_GET['instructor'])){
            $name = $_GET['instructor'];
	}
	else if(isset($_GET['admin'])){
            $name = $_GET['admin'];
	}
	
        $skin->loadcss(array('ichatemplate','basestyles_css'));
	$skin->loadcontent($name,$_GET['folder'],$_GET['file']);
}
else
{
	include($skin->loadtemplate());
}
?>