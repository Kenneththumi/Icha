<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');


echo '<h1 class="articleTitle">ICHA Subscriber Sign Up</h1>';
//load the step wizard
$params = array(
                    'stepno' => 4,
                    'currentstep' => (!isset($_GET['step']) ? '1' : $_GET['step']),
                    'stepnames' => array('1'=>'Step 1','2'=>'Step 2','3'=>'Step 3','4'=>'Step 4'),
                    'stepdesc' => array('1'=>'Add Personal Details','2'=>'Choose Courses','3'=>'Select Course Plan','4'=>'Course Payment')
                );
                
$this->loadmodule('stepgen','stepwizard',$params);

if($_GET['step']=='1')
{
    include (ABSOLUTE_PATH.'modules/subscribers/stepone.php');
}

if($_GET['step']=='2')
{
    include (ABSOLUTE_PATH.'modules/subscribers/steptwo.php');
}
?>
