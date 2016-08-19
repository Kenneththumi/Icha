<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$allcourses = $this->runquery("SELECT * FROM courses ",'multiple','all');
$coursecount = $this->getcount($allcourses);

echo '<h1 class="fullarticleTitle">Training & Education</h1>';

echo '<div id="itemContainer">';

$catcheck = array();
for($r=1; $r<=$coursecount; $r++){
    
    $onecourse = $this->fetcharray($allcourses);
    
    $category = $this->runquery("SELECT name FROM categories WHERE categoryid='".$onecourse['categoryid']."'",'single');
    
    if(!in_array($onecourse['categoryid'], $catcheck)){
        
        echo '<h1 class="fullpage">'.$category['name'].'</h1>';

        $catcourses = $this->runquery("SELECT * FROM courses WHERE categoryid='".$onecourse['categoryid']."'",'multiple',5);

        for($t=1; $t<=5; $t++){

            $course = $this->fetcharray($catcourses);

            if(!is_null($course['courseid'])){
                
                $time = explode('-',date('D-M,d-Y' ,$course['publishdate']));

                $alink = '?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];
                $decription = $this->shortentxt(strip_tags($course['description']), 150);

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
                        
                        echo $decription;                        
                        echo '<p><a href="'.$alink.'" class="read_more">Read More</a></p>';
                    echo '</div>';
                echo '</div>';
            }
        }
    }
    
    $catcheck[] = $onecourse['categoryid'];
    $catcheck = array_filter($catcheck);
}

echo '</div>';