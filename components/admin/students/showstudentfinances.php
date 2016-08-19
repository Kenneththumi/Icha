<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadstyles('categories');

$link = new navigation();

$studid = $_GET['id'];

$student = new user;
$student_details = $student->returnUserSourceDetails($studid,'student');

//process form inputs
if(isset($_POST['savemeans'])){
    
    if($_POST['approved']=='Yes'){
        
        $paymentmeans = [
            'students_id' => $_POST['studentid'],
            'courseid' => $_POST['courseid'],
            'means' => $_POST['means']
        ];
        
        $this->dbinsert('students_payment_trials',$paymentmeans);
        $this->dbupdate('students',['paid'=>'Yes'],"students_id='".$_POST['studentid']."'");
        
        $this->inlinemessage('The payment means has been saved. Refresh the page to see the status','valid');
    }
}

//check payment confirmation
$courses = $this->runquery("SELECT courses.coursename AS coursename, courses.courseid AS courseid FROM courses INNER JOIN student_courses ON student_courses.courseid = courses.courseid  WHERE students_id='".$studid."'",'multiple','all');
$coursescount = $this->getcount($courses);

$table = '<form action="" method="post">'
        . '<table width="100%" border="0" cellpadding="5" class="tablelist">';

$table .= '<tr class="odd">
    <td><strong>Registration ID</strong></td>
    <td colspan="2">'.$student_details['registrationid'].'</td>
  </tr>';

$table .= '<tr class="odd">
    <td><strong>Name</strong></td>
    <td>'.$student_details['name'].'</td>
  </tr>';

for($r=1; $r<=$coursescount; $r++){
    
    $course = $this->fetcharray($courses);
    
    $payments = $this->runquery("SELECT * FROM students_payment_trials WHERE students_id='".$studid."' AND courseid='".$course['courseid']."'",'multiple','all');
    $paymentcount = $this->getcount($payments);
    
    if($paymentcount == 0){
        
        $nochecked = 'checked="checked"';
    }
    else{
        
        $payment = $this->fetcharray($payments);
        $payview = $payment['means'];
        $yeschecked = 'checked="checked"';
    }
    
    $table .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
                    <input type="hidden" name="courseid" value="'.$course['courseid'].'" />
                    <input type="hidden" name="studentid" value="'.$studid.'" />
                    <td><strong>Course Name:</strong></td>
                    <td>'.$course['coursename'].'</td>
                </tr>
                <tr>
                    <td><strong>Payment made</strong></td>
                    <td>
                        <div style="float:left;">
                            <input style="float:left;" type="radio" '.$yeschecked.' value="Yes" id="approved0" name="approved">
                            <label style="cursor: pointer; margin-top:20px;" for="approved0">Yes</label>
                        </div>
                        <div style="float:left;">
                            <input style="float:left" type="radio" '.$nochecked.' value="No" id="approved0" name="approved">
                            <label style="cursor: pointer; margin-top:20px;" for="approved0">No</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                    If Yes, please enter <strong>Means of Payment</strong> i.e. MPesa, Bank Account etc<br/>
                    <textarea name="means" style="margin-top: 10px; width:100%; border:1px solid lightgrey; padding: 5px;">'
                        .$payview
                    .'</textarea>'
            . '</td>'
            . '</tr>';  
}

$table .= '</table>'
        . '<p style="text-align: right">'
            . '<input type="submit" name="savemeans" value="Save Payment Means" />'
        . '</p>'
        . '</form>';

echo '<h1>Student Finances</h1>';
echo $table;

