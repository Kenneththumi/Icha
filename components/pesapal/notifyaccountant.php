<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$id = $_GET['sourceid'];

if($paymentsave['membertype']=='student')
{
    //get student
    $getvalue = $this->runquery("SELECT ".
            "students.name AS name,".
            "students.emailaddress AS email,".
            "courses.coursename AS course, ".
            "courses.courseid AS courseid ".
            "FROM student_courses ".
            "INNER JOIN students ON student_courses.students_id = students.students_id ".
            "INNER JOIN courses ON student_courses.courseid = courses.courseid ".
            "WHERE students.students_id='$id'",'single');

    //send the email
    $emailtxt = '
Greetings,

This is to inform you that a new student has registered for '.$getvalue['course'].', please log into the Administrator and verify the details below.

Tracking ID: '.$paymentsave['tracking_id'].'
Name: '.$getvalue['name'].'
Email Address: '.$getvalue['emailaddress'].'

Thanks 
'.SITENAME;

    $emailhtml = '<html><p>Greetings,</p><p>This is to inform you that a new student has registered for '.$getvalue['course'].', please log into the Administrator and verify the details below.</p>
    
    <p>Tracking ID: '.$paymentsave['tracking_id'].'</p>
    <p>Name: '.$getvalue['name'].'</p>
    <p>Email Address: '.$getvalue['emailaddress'].'</p>
        <p></p>
    <p>Thanks <br/>'.SITENAME.'</p></html>';

    //send email
    $this->multiPartMail(MAILFINANCE,'New '.$paymentsave['membertype'].' payment for '.$getvalue['course'],$emailhtml,$emailtxt,MAILFROM,SITENAME);
}
elseif($paymentsave['membertype']=='subscriber')
{
    $subscriber = $this->runquery("SELECT * FROM subscribers WHERE subid='$id'",'single');
    
    $getvalue = $this->runquery("SELECT ".
            "title ".
            "FROM publications ".
            "WHERE publicationid='".$paymentsave['itemid']."'",'single');    
    
    
    //send the email
    $emailtxt = '
Greetings ,

This is to inform you that subscriber has paid and downloaded a publication: '.$getvalue['title'].', please log into the Administrator and verify the details below.

Tracking ID: '.$paymentsave['tracking_id'].'
Name: '.$subscriber['name'].'
Email Address: '.$subscriber['email'].'

Thanks 
'.SITENAME;

    $emailhtml = '<html><p>Greetings</p><p>This is to inform you that a new student has registered for '.$getvalue['title'].', please log into the Administrator and verify the details below.</p>

    <p>Tracking ID: '.$paymentsave['tracking_id'].'</p>
    <p>Name: '.$getvalue['name'].'</p>
    <p>Email Address: '.$getvalue['email'].'</p>
    <p>Thanks <br/>'.SITENAME.'</p></html>';

    //send email
    $this->multiPartMail(MAILFINANCE,'New '.$paymentsave['membertype'].' payment for publication',$emailhtml,$emailtxt,MAILFROM,SITENAME);
}
?>
