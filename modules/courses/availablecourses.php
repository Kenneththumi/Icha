<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(!isset($params['items']))
{
    $course = $this->runquery("SELECT * FROM courses WHERE enabled = 'Yes' AND parentid = '0' ORDER BY startdate DESC",'single');
    $count = '1';
}
else
{
    $coursechk = $this->runquery("SELECT * FROM courses WHERE enabled = 'Yes' AND parentid = '0' ORDER BY startdate DESC",'multiple',$params['items']);
    $count  = $this->getcount($coursechk);
}

echo '<div id="courseinfo">';
echo '<h1 class="articleTitle">Available Courses</h1>';

for($r=1; $r<=$count; $r++){
    
    if(isset($params['items']))
    {
        $course = $this->fetcharray($coursechk);
    }
    
    $date = date('d-M-Y',$course['startdate']);
    $date = explode('-',$date);

    echo '<div class="course">';
    echo '<div id="coursedate">
            <span class="number">
            <img src="'.MEDIA_PATH.'images/calendaricon.png" width="20" height="20" >
            </span>
            <span class="month"></span>
        </div>';

    echo '<div class="info">
            <span class="title">
                <a href="?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'].'">'.$course['coursename'].'</a>
            </span>
            <span class="publishdate"></span>
          </div>';
    echo '</div>';
}
echo '</div>';
?>
