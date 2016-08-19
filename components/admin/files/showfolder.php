<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$tid = $_GET['id'];
    
if($_GET['show']=='submissions')
{
    $submission = $this->runquery("SELECT ".
        //the variables
        "students.foldername AS folder ".

        //main table
        "FROM student_tests_assignments".

        //students join
        " INNER JOIN students ON student_tests_assignments.students_id = students.students_id ".

        "WHERE student_tests_assignments_id='".$tid."' ",'single');

    if ($_GET['show']=='submissions') 
    {
        $show = 'students/'.$submission['folder'].'/tests_and_assignments'; 
    }
    elseif($_GET['show']=='markedpaper') 
    {
        $show = 'students/'.$submission['folder'].'/marked_papers'; 
    }
    
    $type = 0;
}
elseif($_GET['show']=='courses')
{
    $course = $this->runquery("SELECT coursename FROM courses WHERE courseid='$tid'",'single');
    
    $show = 'courses/'.$course['coursename'];
    $type = 2;
}
elseif($_GET['show']=='tests_assignments')
{
    $show = 'tests_and_assignments';
    $type = 0;
}

echo '<iframe width="880" height="380" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type='.$type.'&lang=eng&fldr='.$show.'"> </iframe>';
?>
