<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadplugin('classForm/class.form');
$application = new form;

echo '<h1 class="articleTitle">Course Registration</h1>';

echo '<div id="itemContainer">';

if(isset($_POST['cmdsave'])){
    
    include(ABSOLUTE_PATH.'plugins/realperson/jquery.realperson.php');
    
    if (rpHash($_POST['defaultReal']) == $_POST['defaultRealHash']){
        
	  $this->loadextraClass('registration');
	  $stud = new registration;
	  
	  $details = array(
                              'name' => $_POST['fname'].' '.$_POST['lname'],
                              'idno' => $_POST['idno'],
                              'emailaddress' => $_POST['email'],
                              'mobile' => $_POST['mobile'],
                              'nationality' => $_POST['nation'],
                              'organisation' => $_POST['org'],
                              'orgaddress' => $_POST['orgaddress'],
                              'emergencydetails' => json_encode([
                                  'names' => $_POST['emergencyfname'].' '.$_POST['emergencylname'],
                                  'mobile' => $_POST['emergencymobile'],
                                  'email' => $_POST['emergencyemail'],
                                  'contact' => $_POST['howe']
                              ]),
                              'course' => $_POST['course']
                          );
	  
	  $student = $stud->registerStudent($details);
	  $courseid = $_POST['course'];
	  
	  if($student['status']=='new'){
              
              //notify the admin
              $stud->notifyAdmin($student['id'], $details['course']);

              require_once ABSOLUTE_PATH.'components/courses/uploadstudentdocs.php';        
	  }
	  elseif($student['status']!='new'){
              
              if($student['status']=='student_present'){
                  
                  $this->inlinemessage('You appear to have previously registered with us. Please use the member login above to enter your profile','valid');
              }
              elseif($student['status']=='no_documents'){
                  
                  $this->inlinemessage('Your email '.$details['emailaddress'].' has been registered. Please upload your documents','valid');

                  $stud_id = $student['id'];			  
                  require_once ABSOLUTE_PATH.'components/courses/uploadstudentdocs.php';     
              }
	  }
	}
    else {
        //$this->inlinemessage('The security letters are incorrect','error');
        redirect($_POST['url'].'&msgerror=The_security_letters_are_incorrect');
    }
}
echo '</div>';
