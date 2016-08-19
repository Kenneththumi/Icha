<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$cid = $_SESSION['sourceid'];
$ds = DIRECTORY_SEPARATOR;

echo '<h1 class="pagetitle">Course & Student Files</h1>';

$instructor = new user();
$details = $instructor->returnUserSourceDetails($_SESSION['sourceid'], 'instructor');

$name = strtolower($this->strClean($details['name']));
$foldername = $this->shortentxt($name,'8','no');

//create instructor folder
if(!is_dir(ABSOLUTE_MEDIA_PATH.'training/'.$foldername))
{
    //create instructor folder
    mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$foldername,0777);
    
    //create courses folder
    mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$foldername .$ds. 'courses',0777);
    
    //create students folder
    mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$foldername .$ds. 'students',0777);
    
    //create tests and assignments folder
    mkdir(ABSOLUTE_MEDIA_PATH.'training/'.$foldername .$ds. 'tests_and_assignments',0777);
    
    $foldersave = array('foldername' => $foldername);
    $this->dbupdate('contributors',$foldersave,"contributorid = '$cid'");
}
else{
    //get the foldername
    $folder = $this->runquery("SELECT * FROM contributors WHERE  contributorid= '$cid' ORDER BY contributorid ASC",'single');

    $foldername = $folder['foldername'];
}

if(!file_exists(ABSOLUTE_MEDIA_PATH . 'training/' . $foldername . '/config.php')){
    //copy the config file from the filemanager folder
    copy(ABSOLUTE_PATH . 'plugins/filemanager/filemanager/subconfig/instructor/config.php', ABSOLUTE_MEDIA_PATH . 'training/' . $foldername . '/config.php');
}

//create courses folders
$courses = $this->runquery("SELECT * FROM courses WHERE  contributorid= '$cid' ORDER BY courseid ASC",'multiple','all');
$coursecount = $this->getcount($courses);

for($r=1; $r<=$coursecount; $r++)
{
    $course = $this->fetcharray($courses);
    
    if(!is_dir(ABSOLUTE_MEDIA_PATH . 'training' .$ds. $foldername .$ds. 'courses' .$ds. $course['coursename']))
    {
        mkdir(ABSOLUTE_MEDIA_PATH . 'training' .$ds. $foldername .$ds. 'courses' .$ds. $course['coursename'],0777);
    }
}

if(empty($_SESSION['subfolder']))
{
    $_SESSION['subfolder'] = 'training/'.$foldername;
    //var_dump($_SESSION['subfolder']);
    
    $show = 'training/'.$details['foldername'];
}

echo '<iframe width="999" height="450" frameborder="0" src="'.PLUGIN_PATH.'/tinymce/plugins/filemanager/dialog.php?type=2&lang=eng&fldr='.$show.'"> </iframe>';
?>