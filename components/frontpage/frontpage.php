<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

if(!isset($_GET['admin']))
{
    
}
elseif(($_SESSION['usertype']=='admin'||$_SESSION['usertype']=='superadmin')&&isset($_GET['admin']))
{
	echo '<h1 class="pagetitle">ICHA Dashboard</h1>';
}
?>