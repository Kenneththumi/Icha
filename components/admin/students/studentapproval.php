<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation;

$this->loadstyles('categories');
$this->loadplugin('classForm/class.form');

if(isset($_POST['approval']))
{
	$studentsave = array(
		'approved' => $_POST['approved']
	);
	
	$this->dbupdate('students',$studentsave,"students_id='".$_POST['id']."'");
	
    if($_POST['approved']=='Yes') {
        
        $studentinfo = $this->runquery("SELECT * FROM students WHERE students_id='".$_POST['id']."'",'single');
        
       //get the username from registration number
       $username = $studentinfo['registrationid'];
       $password = $this->createPassword();
        
       $userstudent = array(
           'username' => $username,
           'password' => $password,
           'sourceid' => $_POST['id'],
           'enabled' => 'yes',
           'accesslevelid' => '14',
           'usertype' => 'student'
       );
       
       $this->dbinsert('users',$userstudent);
        
        $this->loadextraClass('registration');
       	$send = new registration;
       
       $student = $this->runquery("SELECT * FROM students WHERE students_id='".$_POST['id']."'",'single');
       
       $user = $this->runquery("SELECT * FROM users WHERE sourceid='".$_POST['id']."'",'single');
       $username = $user['username'];
       $password = $user['password'];
	   
       	$send->sendConfirmation($student['emailaddress'], $student['registrationid'], $student['name']); 
	$this->inlinemessage('The status has been approved. Refresh the page to see the status','valid');
    }
    else if($_POST['approved']=='No') {
            $this->inlinemessage('The status has NOT been approved. Refresh the page to see the status','error');
    }
}

$student = $this->runquery("SELECT * FROM students WHERE students_id='".$_GET['id']."'",'single');

$approvalform = new form();

$approvalform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'95%',
                                  "map"=>array(1,2),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action"=>'',
								  "id" => 'approvalform'
                                  ));

$approvalform->addHidden('approval','approval');
$approvalform->addHidden('id',$_GET['id']);

$approvalform->addHTML('<h1>Student Approval</h1>');

$approvalform->addRadio('Approve Student Application?', 'approved',$student['approved'],array('Yes','No'),array('required'=>false));

$approvalform->addButton('Save Approval Status');
$approvalform->render();
?>