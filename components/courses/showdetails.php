<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$link = new navigation();

if (isset($_GET['cid'])) 
{
    $course = $this->runquery("SELECT * FROM courses WHERE courseid='".$_GET['cid']."'",'single');
    $lecturer = $this->runquery("SELECT * FROM contributors WHERE contributorid='".$course['contributorid']."'",'single');
    
    echo '<h1 class="articleTitle">'.$course['coursename'].'</h1>';
    
    echo '<div id="itemContainer">';
    echo '<div class="splitcontent col-xs-12">';
    
    if($course['startdate'] != 0){
        
        $startdate = date('l, d M Y',$course['startdate']);
        $enddate = date('l, d M Y',$course['enddate']);
        
        echo '<h4 style="margin-bottom: 20px; margin-top: 0px !important; width:auto; display: table; padding: 0px">From <strong>'.$startdate.'</strong> to <strong>'.$enddate.'</strong></h4>';
    }
    
    echo '<p class="fulltxt">'.$course['description'].'</p>';
    
    if($course['brochure'] != ''){
                    
        $doc = json_decode($course['brochure']);
        
        if(!is_null($doc->filename)){
            
            $brochure = json_decode($course['brochure']);

            $mlink = MEDIA_PATH.'pdf/'.$brochure->filename;
            echo '<a href="'.$mlink.'" target="_blank" class="download">Download Brochure</a>';
        }
    }
    
    //echo '<a href="?content=mod_subscribers&folder=same&file=subscriberwizard&step=1" class="register_login_button">Please register or login to start this class</a>';
    echo '</div>';
    
    echo '<div class="sidepanes col-xs-12">';
    include ABSOLUTE_PATH.'components/courses/coursepanel.php';
    echo '</div>';
    echo '</div>';
}
