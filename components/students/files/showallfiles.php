<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$cid = $_SESSION['sourceid'];
$ds = DIRECTORY_SEPARATOR;
$student = new user();

echo '<h1 class="pagetitle">Your Assignment Files & Papers</h1>';

if(empty($_SESSION['subfolder']))
{
    $details = $student->returnUserSourceDetails($_SESSION['sourceid'], 'student');
    
    $getfolder = $this->runquery("SELECT contributors.foldername AS fname FROM contributors ".
                                        "INNER JOIN courses ON courses.contributorid = contributors.contributorid ".
                                        "INNER JOIN student_courses ON student_courses.courseid = courses.courseid ".
                                        "WHERE students_id = '".$details['students_id']."'",'single');
    
    //$_SESSION['subfolder'] = 'training/'.$details['foldername'];
    $show = 'training/'.$getfolder['fname'].'/students/'.$details['foldername'].'/';;
}

echo '<iframe width="999" height="450" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng&fldr='.$show.'"> </iframe>';
?>