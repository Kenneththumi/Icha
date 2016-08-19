<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$catid = $_GET['catid'];

$category = $this->runquery("SELECT * FROM categories WHERE categoryid = '$catid'",'single');

$courses = $this->runquery("SELECT * FROM courses WHERE categoryid='$catid' ORDER BY courseid DESC",'multiple','all');
$coursecount = $this->getcount($courses);

echo '<h1 class="fullarticleTitle">'.ucfirst($category['name']).'</h1>';

echo '<div id="itemContainer">';

if($coursecount >= 1){
    
    for($r=1; $r<=$coursecount; $r++){
        
        $course = $this->fetcharray($courses);
        $time = explode('-',date('D-M,d-Y' ,$course['publishdate']));
        
        $alink = '?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];
        $description = $this->shortentxt(strip_tags($course['description']), 150);
        
        echo '<div class="itemList">';   
        
            echo '<header>';
            /**
                echo '<time pubdate="" datetime="'.date('d M Y H:i:s' ,$course['publishdate']).'">'
                        . $time[0]
                        .'<small>'.$time[1].'</small>'
                        . '</time>';
             * 
             */
            echo '<h2><a href="'.$alink.'">'.$course['coursename'].'</a></h2>';
            echo '</header>';    
            
            echo '<div class="itemBody">';
                
                $lastweek = strtotime("-1 week");
                if($course['startdate'] != '0' && $course['startdate'] >= $lastweek){
                
                    $date = date('l, d M Y',$course['startdate']);
                    echo '<a class="smalldate" href="'.$alink.'" class="startdate">Start Date: '.$date.'</a>'; 
                }
                
                echo $description;
                
                echo '<p>';
                
                $brochure = json_decode($course['brochure']);
                if($brochure->filename != ''){
                    
                    $mlink = MEDIA_PATH.'pdf/'.$brochure->filename;
                    echo '<a href="'.$mlink.'" target="_blank" class="download">Download Brochure</a>';
                }
                
                echo '<a href="'.$alink.'" class="read_more">Read More</a></p>';
            echo '</div>';
        echo '</div>';
    }
}
else{
    
    $this->inlinemessage('No courses found under '.$category['name'],'warning');
}

echo '</div>';