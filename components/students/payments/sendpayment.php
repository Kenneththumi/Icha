<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');
$cipher = new encryption();

$student = $this->runquery("SELECT * FROM students WHERE students_id='".$_GET['studentid']."'",'single');

$course = $this->runquery("SELECT * FROM courses WHERE courseid='".$_GET['courseid']."'",'single');

$emailtxt = '
Dear '.$student['name'].',

Your application to take the course on '.$course['coursename'].' at the International Center for Humanitarian Affairs is being processed. Follow the instructions below to make payments for the course to make payments for the course. Afterwards your application will be approved and your credentails sent to this email address to start learning immediately. 

Course Name: '.$course['coursename'].'
    
'.SITE_PATH.'?content=com_students&folder=payments&file=paycourse&courseid='.$course['courseid'].'&studentid='.$_GET['studentid'].'

Thanks 
'.SITENAME;

$emailhtml = '<html><p>Dear '.$student['name'].'</p>

<p>Your application to take the course on '.$course['coursename'].' at the International Center for Humanitarian Affairs is being processed. Follow the instructions below to make payments for the course. Afterwards your application will be approved and your credentails sent to this email address to start learning immediately.</p>

<p>Please follow the link below to pay for the applied for course:</p>
<p>Course Name: '.$course['coursename'].'</p>

<a href="'.SITE_PATH.'?content=com_students&folder=payments&file=paycourse&courseid='.$course['courseid'].'&studentid='.$_GET['studentid'].'">Pay for Course</a>

<p>Thanks <br/>'.SITENAME.'</p></html>';
	
//echo $emailhtml; exit;
//send email
$this->multiPartMail($student['emailaddress'],'ICHA Payment Details for  '.$course['coursename'],$emailhtml,$emailtxt,MAILFROM,SITENAME);

//save the trial
$trial = $this->runquery("SELECT * FROM students_payment_trials WHERE students_id = '".$_GET['studentid']."' AND courseid = '".$_GET['courseid']."' AND emailaddress = '".$student['emailaddress']."'",'multiple','all');
$trialcount = $this->getcount($trial);

$trialsave = array(
        'students_id' => $_GET['studentid'],
        'courseid' => $_GET['courseid'],
        'emailaddress' => $student['emailaddress']
    );

if($trialcount=='0')
{
    $this->dbinsert('students_payment_trials',$trialsave);
}
else
{
    $gettrial = $this->fetcharray($trial);
    $this->dbupdate('students_payment_trials',$trialsave,"trialid = '".$gettrial['trialid']."'");
}

redirect($cipher->decrypt($_GET['url']));
?>