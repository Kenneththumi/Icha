<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$student = $this->runquery("SELECT * FROM students WHERE students_id='".$_GET['studentid']."'",'single');

$course = $this->runquery("SELECT * FROM courses WHERE courseid='".$_GET['courseid']."'",'single');

//get list of courses
$cost = $this->runquery("SELECT prices.price AS price, currency.currencycode AS code FROM prices INNER JOIN currency ON prices.curid = currency.currencyid WHERE prices.courseid='".$course['courseid']."'",'single');

echo '<h1 class="fullarticleTitle">Payment for '.$course['coursename'].'</h1>';

//include the payment file
require_once ABSOLUTE_PATH.'components/pesapal/paymentform.php';

echo '<table width="100%" border="0" cellpadding="10">
  <tr>
    <td colspan="3" class="tableitem"><strong>Student Name</strong>: '.$student['name'].'</td>
  </tr>
  <tr>
    <td class="tableitem" width="30%">'.$course['coursename'].'</td>
    <td class="tableitem" width="30%">Course Cost: '.$cost['code'].' '.$cost['price'].'</td>
    <td class="tableitem" width="30%"></td>
  </tr>
  <tr>
    <td colspan="3" class="tableitem">
   '.$course['description'].'
    </td>
  </tr>
  <tr>
    <td class="tableitem"><strong>Publish Date</strong>: '.date('d F Y',$course['publishdate']).'</td>
    <td class="tableitem"><strong>Start Date</strong>: '.date('d F Y',$course['startdate']).'</td>
    <td class="tableitem"><strong>End Date</strong>: '.date('d F Y',$course['enddate']).'</td>
  </tr>
  <tr>
    <td class="tableitem" colspan="3">&nbsp;</td>
  </tr>
</table>';


?>