<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadstyles('backoffice');

echo '<h1>Disable Subscribers</h1>';

$id = $_GET['id'];

$status = array(
  'enabled' => $_GET['status']
);  

$this->dbupdate('subscribers',$status,"subid='".$id."'");  
//$this->print_last_error();

if($_GET['status']=='yes')
{
    $this->inlinemessage('The '.count($ids).' subscribers have been enabled','valid');
}
else
{
    $this->inlinemessage('The '.count($ids).' subscribers have been disabled','valid');
}

?>