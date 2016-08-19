<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if($_GET['show']=='courses')
{
    $cid = $_GET['id'];
    $course = $this->runquery("SELECT * FROM courses WHERE courseid='$cid'",'single');
    
    $show = 'courses/'.$course['coursename'];
}
elseif ($_GET['show']=='tests_assignments') {
    $show = 'tests_and_assignments';
}

echo '<iframe width="880" height="380" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng&fldr='.$show.'"> </iframe>';
?>
