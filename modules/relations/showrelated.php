<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(isset($_GET['pid']))
{
    $publication = $this->runquery("SELECT * FROM publications WHERE publicationid='".$_GET['pid']."'",'single');
    
    $course = $this->runquery("SELECT * FROM courses WHERE courseid='".$publication['courseid']."'",'single');
    
    $allcourses = $this->runquery("SELECT * FROM publications WHERE courseid='".$courseid."'",'multiple','all');
   $allcount = $this->getcount($allcourses);
   
   echo '<h1 class="ptitle">Course related to selected publication: </h1>';
   echo '<ul><li>'.($course['coursename']=='' ? 'None specified' : $course['coursename']).'</li></ul>';
}
elseif(isset($_GET['cid']))
{
    $courseid = $_GET['cid'];
    $allcourses = $this->runquery("SELECT * FROM publications WHERE courseid='".$courseid."'",'multiple','all');
   $allcount = $this->getcount($allcourses);
}

echo '<h1 class="ptitle">Other related publications</h1>';

echo '<ul>';
for($r=1; $r<=$allcount; $r++)
{
    $all = $this->fetcharray($allcourses);
    
    echo '<li>'.($all['title']=='' ? 'Not Soecified' : $this->shortentxt($all['title'],40)).'</li>';
}
echo '</ul>';
?>
