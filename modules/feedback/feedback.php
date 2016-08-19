<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//$this->loadstyles('backoffice');
$this->loadplugin('classForm/class.form');

//include PLUGIN_PATH.'recaptcha/recaptchalib.php';
//$publickey = '6LcwOPQSAAAAAJC-7mlqx8hQHBQMp5nY2RullRP6';

$student = new user();
$student_details = $student->returnUserSourceDetails($_SESSION['sourceid'],'student');

echo '<h1 class="pagetitle">Contact Administration</h1>';

//process the feedback
if(isset($_POST['emailsave']))
{
	$subject = 'ICHA Student Feedback: '.$_POST['fsubject'];

	$htmlbody = '<html><p>Greetings,</p>
                <p>Below is feedback from the Students feedback form</p>

		<p>Sender Name: '.$_POST['sname'].'</p>
                <p>Email Address: '.$_POST['email'].'</p>
		<p>Subject: '.$_POST['fsubject'].'</p>
		<p></p>
		<p>Sender Comments: '.$_POST['fcomments'].'</p>
		
		<p>'.SITENAME.'</p></html>';
	
	$txtbody = strip_tags($htmlbody);
	
	$this->multiPartMail(MAILADMIN,$subject,$htmlbody,$txtbody,$from,'noreply@kenyacompare.com');
	
	$this->inlinemessage('The message has been sent to the administration','valid');
}


$feedback = new form();

$feedback->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'98%',
                                  'map' => array(2,1,1,1),
                                  "preventJQueryLoad" => false,
                                  "preventJQueryUILoad" => false,
                                  "action"=>''
                                  ));

$feedback->addHidden('emailsave','emailsave');

//$feedback->addHTML('');

$feedback->addTextbox('Your Name','sname',$student_details['name'],array('required'=>true));
$feedback->addTextbox('Your Email','email',$student_details['emailaddress'],array('required'=>true));

$feedback->addTextbox('Subject','fsubject','',array('required'=>true));
$feedback->addWebEditor('Message','fcomments','',array('required'=>true));

//$feedback->addCaptcha('Enter the letters below.',array("required"=>true));

$feedback->addButton('Send Comments');

$feedback->render();
?>