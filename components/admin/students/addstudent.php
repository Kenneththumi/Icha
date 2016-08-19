<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

$this->loadplugin('encryption/encrypt');
$cipher = new encryption();

$this->wrapscript("$(document).ready(function(){
                         $('.confirmdeletion').click(function() {
                                $.notification( 
                                        {
                                             title: 'Confirm Deletion:',
                                             img: \"plugins/owlnotifications/img/recyclebin.png\",
                                             content: '<p class=\"deletiontext\">Are you sure you want to delete? <a href=\"'+$(this).attr('rel')+'\">Yes</a><a href=\"".$link->geturl()."\">No</a></p>',
                                             fill: true,
                                             border: false,
                                             showTime: true
                                        }
                                );
                        });
                });");

//the image fancybox settings
$this->wrapscript("$(document).ready(function(){
                        $('a.imglink').fancybox({
                                        'width': 700,
                                        'height': 510,
                                        'autoDimensions': false,
                                        'autoScale': false,
                                        'transitionIn': 'elastic',
                                        'transitionOut': 'elastic',
                                        'enableEscapeButton' : true,
                                        'overlayShow' : true,
                                        'overlayColor' : '#FFFFFF',
                                        'overlayOpacity' : 0.3,
                                        'scrolling': 'auto',
                                        'hideOnOverlayClick': false,
                                        'type':'iframe',
                                        'onClosed': function() {
                                        window.location.reload();
                                      }
                                });
                         });");

    
//generate username/registration number based on format ICHA/ reg number / reg year if its manual stdnt reg by admin 
    $stds = $this->runquery("SELECT * FROM students ORDER BY students_id DESC",'single');

    $sid = $stds['students_id'];
    $sid++;

    $regno = sprintf('%05d', $sid);
    //echo '<script>alert("'.$regno.'")</script>'.die();
    $year = date('Y');

    $regid = 'ICHA/'.$regno.'/'.$year;
//


//approve
if(isset($_POST['cmdsave'])){
    
    $studentsave = array(
                          'name' => $_POST['sname'],
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
                          'approved' => $_POST['approved']
                      );
    
    if($_POST['students_id']==''){
        
        if(!empty($_POST['sname'])&&!empty($_POST['email'])&&!empty($_POST['mobile'])&&!empty($_POST['idno'])){
                //check for student
                $student = $this->runquery("SELECT * FROM students WHERE emailaddress='".$_POST['email']."'",'multiple','all');
                $studentcount = $this->getcount($student);

               if($studentcount =='0'){

                                $studentsave['paid'] = 'no';
                                $studentsave['registrationdate'] = time();   
                                $studentsave['registrationid'] = $regid; 
                            //echo '<script>alert("'.$regno.'")</script>';    
                        $savestudent = $this->dbinsert('students',$studentsave);

                        //added from registration class
                          $id = mysql_insert_id();


                         if($savestudent){

                                    //create the student folder
                                    $name = strtolower(str_replace(' ','',$_POST['sname']));
                                    $foldername = $this->shortentxt($name,'8','no');

                                    $ds = DIRECTORY_SEPARATOR;

                                    if(!is_dir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername))
                                    {
                                        //create student folder
                                        mkdir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername,0777);
                                        chmod(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername,0777);

                                        //create tests and assignments folder
                                        mkdir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername.'/tests_and_assignments',0777);
                                        chmod(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername.'/tests_and_assignments',0777);

                                        //create marked papers folder
                                        mkdir(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername.'/marked_papers',0777);
                                        chmod(ABSOLUTE_MEDIA_PATH.'training/students/'.$foldername.'/marked_papers',0777);

                                        //copy the config file from the file manager folder
                                        copy(ABSOLUTE_PATH . 'plugins/filemanager/filemanager/subconfig/student/config.php', ABSOLUTE_MEDIA_PATH . 'training/students/' . $foldername . '/config.php');
                                    }


                                    $foldersave = ['foldername' => $foldername]; 
                                    $this->dbupdate('students',$foldersave,"students_id = '$id'");

                                    if(!empty($_POST['newcourse'])){
                                        $course = $_POST['newcourse'];
                                    }else{
                                        $course ="0";
                                    }
                                    $student_course = array(
                                        'students_id' => $id,
                                        'courseid' => $course
                                    );


                                    $this->dbinsert('student_courses', $student_course);

                                }
                                else
                                {
                                    $this->print_last_error();
                                }
                        
                        }else{
                             redirect($link->urlreturn('Students','&msgvalid=Another_students_exists_with_similar_details'));         
                        }
                }else{
                    //$this->inlinemessage('Please Insert ALL required details','error');
                    redirect($link->urlreturn('Students','&msgvalid=Please_insert_All_Required_Fields (*)'));
                }
           }
    elseif($_POST['students_id']!=''){
        
        $this->dbupdate('students',$studentsave,"students_id='".$_POST['students_id']."'");
    }
    
    if($_POST['newcourse']!=''){
        $course = [
            'courseid' => $_POST['newcourse'],
            'students_id' => $_POST['students_id']
        ];
        if($_POST['students_id']!=''){
        $this->dbupdate('student_courses',$course,"students_id='".$_POST['students_id']."'");
        }
    }
        
    if($_POST['approved']=='Yes') {
        
        $studentinfo = $this->runquery("SELECT * FROM students WHERE students_id='".$_POST['students_id']."'",'single');
        
       //get the username from registration number
       if($studentinfo['registrationid']){
       $username = $studentinfo['registrationid'];
       }else{
           $username = $regid;
       }
       if(!isset($_GET['id'])){
             $_GET['id'] = $regno;
       }
       $password = $this->createPassword();
        
       $userstudent = array(
           'username' => $username,
           'password' => $password,
           'sourceid' => $_GET['id'],
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
	
    redirect($link->urlreturn('Students','&msgvalid=The_student_details_have_been_saved'));
}

$this->loadplugin('classForm/class.form');

echo '<h1 class="pagetitle">Student Management</h1>';

$student = $this->runquery("SELECT * FROM students WHERE students_id='".$_GET['id']."'",'single');

//delete stdent courses
if($_GET['task'] == 'deletecourse'){
    
    //delete the document record
   // $this->deleterow('student_courses','student_courses_id',$_GET['scid']);
     $data=['courseid'=>'NULL'];
     $this->dbupdate('student_courses',$data,"student_courses_id=".$_GET['scid']);
     
    redirect($cipher->decrypt($_GET['url']).'&msgvalid=The_course_has_been_Removed');
}

//deletion of documents
if($_GET['task']=='deletedocument'){
    
    $studentid = $_GET['studentid'];
    $docid = $_GET['docid'];
    
    $document = $this->runquery("SELECT * FROM documents WHERE docid='".$docid."'",'single');
    
    //delete the document
    unlink(ABSOLUTE_MEDIA_PATH.'training/students/'.$student['foldername'].'/'.$document['filename']);
    
    //delete the document record
    $this->deleterow('documents','docid',$docid);
    
    //delete the student documents record
    $this->rawquery("DELETE FROM students_documents WHERE students_id = '".$studentid."' AND documents_id = '".$docid."'");
    
    redirect($cipher->decrypt($_GET['url']).'&msgvalid=The_document_has_been_deleted');
}

//get list of courses
$courses = $this->runquery("SELECT "
                    . "student_courses.student_courses_id AS scid, "
                    . "courses.coursename AS coursename, "
                    . "courses.courseid AS courseid "
                    . "FROM courses "
                    . "INNER JOIN student_courses "
                    . "ON student_courses.courseid = courses.courseid "
                    . " WHERE students_id='".$_GET['id']."'",
                    'multiple','all');

$coursescount = $this->getcount($courses);

$this->loadplugin('encryption/encrypt');
$cipher = new encryption();

$table = '<table width="100%" border="0" cellpadding="10" class="tablelist">
  <tr>
    <td class="tabletitle"><strong>Course Applied</strong></td>
    <td><a href="?admin=com_admin&folder=students&file=addnewcourse&id='.$student['students_id'].'&alert=yes" class="imglink">'
            . 'Add New Course'
        . '</a>
    </td>
  </tr>';

if($coursescount >= 1){    
    for($r=1; $r<=$coursescount; $r++){

            $course = $this->fetcharray($courses);

            $cost = $this->runquery("SELECT "
                    . "prices.price AS price, "
                    . "currency.currencycode AS code "
                    . "FROM prices "
                    . "INNER JOIN currency "
                    . "ON prices.curid = currency.currencyid "
                    . "WHERE prices.courseid='".$course['courseid']."'",'single');

            //get payment confirmation
            $paychk = $this->runquery("SELECT * FROM paymentconfirmation WHERE sourceid='".$_GET['id']."' AND itemid='".$course['courseid']."'",'multiple','all');
            $paycount = $this->getcount($paychk);

            if($paycount>='1'){

                $payget = $this->fetcharray($paychk);        
                $payview = 'Confirmation Tracking ID: '.$payget['tracking_id'];
            }
            else{

                //save the trial
                $trial = $this->runquery("SELECT * FROM students_payment_trials WHERE students_id = '".$_GET['id']."' AND courseid = '".$course['courseid']."'",'multiple','all');        
                $trialcount = $this->getcount($trial);

                if($trialcount=='0'){

                    $payview = '<a href="?content=com_students&folder=payments&file=sendpayment&courseid='.$course['courseid'].'&studentid='.$_GET['id'].'&alert=yes&url='.$cipher->encrypt($link->geturl().'&msgvalid=The_payment_email_has_been_sent').'">Send Payment Link</a>';
                }
                else{

                    $payview = '<a href="?content=com_students&folder=payments&file=sendpayment&courseid='.$course['courseid'].'&studentid='.$_GET['id'].'&alert=yes&url='.$cipher->encrypt($link->geturl().'&msgvalid=The_payment_email_has_been_sent').'">Resend Payment Email</a>';
                }

                $payview = '';
            }

            $table .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
                        <td><strong>'.$course['coursename'].'</strong></td>
                        <td><a href="?admin=com_admin&folder=students&file=addstudent&task=deletecourse&url='.$cipher->encrypt($link->geturl()).'&scid='.$course['scid'].'">Delete Course
                            </a></td>
                      </tr>';
        }
}
else{
    
    $table .= '<tr>'
            . '<td colspan="3" class="tabletitle"><span style="color: red"><strong>No course found</strong></span></td>'
            . '</tr>';
    
    $allcourses = $this->runquery("SELECT * FROM courses",'multiple','all');
    $allcoursescount = $this->getcount($allcourses);
    
    $courselist[''] = 'Select Course';
    for($t=1; $t<=$allcoursescount; $t++){
        
        $acourse = $this->fetcharray($allcourses);
        $courselist[$acourse['courseid']] = $acourse['coursename'];
    }
}

 


$table .= '</table>';

$name = explode(' ',$student['name']);
$dob = explode('-',date('d-F-Y',$student['dateofbirth']));

$studentform = new form;
$studentform->setAttributes(array("includesPath"=> PLUGIN_PATH.'/classForm/includes',
                                  "width"=>'97%',
                                  "map" => array(2,3,3,1,2,2,1,1),
                                  "preventJQueryLoad" => true,
                                  "preventJQueryUILoad" => true,
                                  "action" => ''
                                  ));


$studentform->renderHead();

$studentform->addHidden('cmdsave', 'cmdsave');
$studentform->addHidden('students_id', $_GET['id']);



$studentform->addTextbox('Student Name', 'sname', $student['name'],array('required'=>true));
$studentform->addTextbox('Student Reg.', 'regid', (isset($student['registrationid'])? $student['registrationid'] :$regid),array('Disabled'=>true));

$studentform->addTextbox('ID or Passport No.', 'idno', $student['idno'],array('required'=>true));
$studentform->addEmail('Email Address', 'email', $student['emailaddress'],array('required'=>true));
$studentform->addTextbox('Mobile No', 'mobile',$student['mobile'],array('required'=>true));

$studentform->addCountry('Nationality', 'nation', $student['nationality']);
$studentform->addTextbox('Organisation', 'org', $student['organisation']);
$studentform->addTextbox('Organisation Address', 'orgaddress', $student['orgaddress']);

$studentform->addHTML('<p><strong>Emergency Contacts</strong></p>');

$emergency = json_decode($student['emergencydetails'],TRUE);
$names = explode(' ',$emergency['names']);

$studentform->addTextbox('First Name','emergencyfname',$names[0]);
$studentform->addTextbox('Last Name','emergencylname',$names[1]);

$studentform->addTextbox('Mobile No','emergencymobile',$emergency['mobile']);
$studentform->addTextbox('Email Address','emergencyemail',$emergency['email']);

$howe = ['word_of_mouth'=>'Word of Mouth',
         'print' => 'Newspapers or Magazines',
         'online' => 'Online'];

$studentform->addHidden('howe', $emergency['contact']);
$studentform->addHTML('<p><strong>Point of contact:</strong> '.$howe[$emergency['contact']].'</p>');

if(isset($_GET['id'])){
$studentform->addHTML($table);
}

if($coursescount == 0){
    $studentform->addSelect('Select New Course', 'newcourse', '', $courselist);
}

$docid = $_GET['id'];

$studentuser = new user;
$student_details = $studentuser->returnUserSourceDetails($docid,'student');

$docquery = $this->runquery("SELECT * FROM documents INNER JOIN students_documents ON students_documents.documents_id = documents.docid WHERE students_documents.students_id = '$docid'",'multiple','all');
$documentcount = $this->getcount($docquery);

if($documentcount>='1'){
    $doctable = '<table width="100%" border="0" cellpadding="10" class="tablelist">
      <tr>
        <td colspan="3" class="tabletitle"><strong>Attached Documents</strong></td>
      </tr>';

    for($r=1; $r<=$documentcount; $r++)
    {
        $document = $this->fetcharray($docquery);
        
        if(file_exists(ABSOLUTE_PATH.'media/training/students/'.$student['foldername'].'/'.$document['filename']))
        {
            $doclink = SITE_PATH.'media/training/students/'.$student['foldername'].'/'.$document['filename'];
            $target = 'target="_blank"';
        }
        else
        {
            $doclink = $link->geturl().'&msgerror=No_document_found';
        }
        
        $doctable .= '<tr '.($r%2==0 ? 'class="odd"' : 'class="even"').'>
        <td><strong>'.$document['docname'].'</strong></td>
        <td>
            <a href="'.$doclink.'" '.$target.'>
                Download File
            </a>        
        </td>
        <td>
            <a class="confirmdeletion" rel="'.$link->geturl().'&task=deletedocument&studentid='.$student['students_id'].'&docid='.$document['docid'].'&filename='.$document['filename'].'&url='.$cipher->encrypt($link->geturl().'&msgvalid=The_payment_email_has_been_sent').'" >
                <img src="'.STYLES_PATH.'df_template/images/delete.png" width="28" height="28">
            </a>
        </td>
      </tr>';
    }

    $doctable .= '</table>';  
    
}
else
{
    if($student['students_id']){
    $this->inlinemessage('No documents attached to '.$student_details['name'],'error');
    
    $doctable = '<table width="100%" border="0" cellpadding="10" class="tablelist">
      <tr>
        <td class="tabletitle"><strong>Attached Documents</strong></td>
      </tr>
      <tr>
        <td class="tabletitle">
        <a href="?admin=com_admin&folder=students&file=showstudentsdocs&alert=yes&id='.$student['students_id'].'" class="imglink">
            Upload Document
        </a>
        </td>
      </tr>
      </table>';
    }
}

$studentform->addHTML($doctable);

//$studentform->addSelect('How did he/she hear of us?', 'howe', '',
//    ['word_of_mouth'=>'Word of Mouth',
//        'print' => 'Newspapers or Magazines',
//        'online' => 'Online']);

$studentform->addRadio('Approve Student Application?', 'approved',$student['approved'],array('Yes','No'),array('required'=>false));

$studentform->addButton('Save Student Details', 'submit');

$studentform->render();
