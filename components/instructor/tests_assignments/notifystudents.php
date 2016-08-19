<?php
//get the students enrolled for this course
/*
$getstudents = $this->runquery("SELECT students.emailaddress AS email, courses.coursename AS coursename FROM students INNER JOIN  student_courses ON student_courses.students_id = students.students_id INNER JOIN courses ON student_courses.students_id = courses.courseid WHERE courses.contributorid = '".$_SESSION['sourceid']."'",'multiple','all');
 
$getcount = $this->getcount($getstudents);
 * 
 */

$students = $_POST['student_select'];

foreach($students as $email)
{
    
    $emailhtml = "<p>Greetings,</p>
<p>This is to inform you that a new ".$_POST['tatype']." has been added to your work schedule. The details are listed below:</p>
<p>".$_POST['tatype']." name: ".$_POST['tatitle']."</p>
<p>Due Date: ".$_POST['duedate']."</p>
<p>Please login into the student portal to download the new work.</p>
<p>Thanks</p>
<p>".SITENAME."</p>";
    
    $emailtxt = strip_tags($emailhtml);
    
    $this->multiPartMail($email,'New '.$_POST['tatype'].' for '.$_POST['tatitle'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
}
?>
