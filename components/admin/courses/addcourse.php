<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();
$teacher = new user();

//load encryption
$this->loadplugin('encryption/encrypt');
$cipher = new encryption();

//process the steps
if(isset($_POST['savestep'])){
    
    $step = $_POST['savestep'];
    
    //check the start and end dates
    if(strtotime($_POST['startdate']) > strtotime($_POST['enddate'])){        
        redirect($link->geturl().'&msgerror=Please_enter_the_course_right_start_and_end_dates');
    }
    
    //check the type
    if($_POST['type']=='course'){
        
        $contributor = $teacher->returnUserSourceDetails($_POST['author'], 'instructor');
        
        $foldername = $contributor['foldername'];
        $cid = $_POST['author'];
    }

    if($_POST['pam'] == '<!DOCTYPE html>
<html>
<head>
</head>
<body>

</body>
</html>'){
        $pam = '';
    }
    else{
        $pam = $_POST['pam'];
    }
    
    $enddate_ =$_POST['enddate']!='' ? strtotime($_POST['enddate']) : 0; 
            
    $savestep = [
                    'coursename' => $_POST['coursename'],
                    'description'=>$_POST['desc'],
                    'enabled' => ($_POST['enabled']=='1' ? 'Yes' : 'No'),
                    'startdate' => $_POST['startdate']!='' ? strtotime($_POST['startdate']) : 0,
                    'enddate' => $enddate_,
                    'expirydate' => strtotime('+3 months',$enddate_),
                    'categoryid' => $_POST['category'],
                    'contributorid' => $cid,
                    'parentid' => ($_POST['type']=='unit' ? $_POST['parent'] : '0'),
                    'brochure' => json_encode(['filename'=>$_POST['filename'],'document'=>$_POST['docname']]),
                    'post_application_message' => $pam,
                    'featured' => $_POST['featured']
                ];
                
    $ds = DIRECTORY_SEPARATOR;
    $filepath = ABSOLUTE_MEDIA_PATH . 'training' .'/'. $foldername .'/'. 'courses' .'/';
    
    if(isset($_POST['courseid'])){
        
        $courseid = $_POST['courseid'];

       $course = $this->runquery("SELECT * FROM courses WHERE courseid='$courseid'",'single');
        
        //check the filename and rename if necessary   
        if($filepath . $course['coursename'] != $filepath . $_POST['coursename'])
        {
            rename($filepath . $course['coursename'], $filepath . $_POST['coursename']);
        }
        elseif(!is_dir($filepath . $_POST['coursename'])){
            mkdir($filepath . $_POST['coursename'],0777);
        }
        
        $this->dbupdate('courses',$savestep,"courseid='".$courseid."'");
        redirect($link->urlreturn('courses & units').'&msgvalid=The_course_has_been_edited');
    }
    else{
        
        $savestep['publishdate'] = time();
        
        $this->dbinsert('courses', $savestep);
        $courseid = mysql_insert_id();
        
        if(!is_dir($filepath . $_POST['coursename']))
        {
            mkdir($filepath . $_POST['coursename'],0777);
        }
        
        redirect($link->urlreturn('courses & units').'&msgvalid=The_course_has_been_added');
    }
}

//check for parent id
if(isset($_GET['id']))    
    $chk = $this->runquery("SELECT parentid FROM courses WHERE courseid = '".$_GET['id']."'",'single');


echo '<h1 class="pagetitle">Course Management</h1>';
    
echo '<div id="contentholder">';
        
     $type = 'course';        
     include (ABSOLUTE_PATH.'components/admin/courses/courseform.php');
        
echo '</div>';

