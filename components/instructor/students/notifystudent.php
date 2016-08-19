<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$emailhtml = "<p>Greetings,</p>
<p>This is to inform your submitted paper has been graded. The details are listed below:</p>
<p></p>
<p>Test/Assignment Name: ".$test['name']."</p>
<p>Course: ".$test['coursename']."</p>    
<p>Grade: ".$_POST['grade']."</p>
<p></p>
<p>Please login into the student portal to download the new submitted paper.</p>
<p>Thanks</p>
<p>Administrator</p>
<p>".SITENAME."</p>";

$emailtxt = strip_tags($emailhtml);

$this->multiPartMail($test['studentemail'],'Your submission for '.$test['name'].' has been graded',$emailhtml,$emailtxt,MAILFROM,SITENAME);
?>
