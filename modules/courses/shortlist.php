<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadscripts('jquery.scrollable','yes');

//load slider css
echo '<link href="'.SITE_PATH.'modules/leadarticle/css/scrollable.css" rel="stylesheet" type="text/css" />';

$coursechk = $this->runquery("SELECT * FROM courses "
        . "WHERE enabled = 'Yes' AND parentid = '0' "
        . "ORDER BY courseid DESC",'multiple',6);

$count  = $this->getcount($coursechk);

if($count >= 1){
    
    $this->wrapscript('$(function() {
                        // initialize scrollable
                        $(".eventlist").scrollable({circular: true})
                                        .autoscroll({ 
                                            autoplay: true, 
                                            interval: 15000 
                                        });
                    });');
    
    $slidecount = ($count/3);
    
    echo '<div class="eventnav">'
            . '<a class="prev browse left"></a>'
            . '<a class="next browse right"></a>'
        . '</div>';
    
    echo '<div class="eventlist">';
    echo '<div class="items">';
    for($r=1; $r<=$slidecount; $r++){
        
        echo '<div class="slide">'; 
        for($t=1; $t<=3; $t++){

            $course = $this->fetcharray($coursechk);

            $date = date('l, d M Y',$course['publishdate']);
            
            $alink = '?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];

               echo '<div class="itemList'.($t == 1 ? '1' : '').'">';
               
               echo '<p><a href="'.$alink.'">'.$this->shortenTxt($course['coursename'],40).'</a></p>';  
               
               $lastweek = strtotime("-1 week");
               if($course['startdate'] != '0' && $course['startdate'] >= $lastweek){
                
                    $date = date('l, d M Y',$course['startdate']);
                    echo '<a href="'.$alink.'" class="smalllink">Start Date: '.$date.'</a>'; 
                }
               
               echo '<a href="'.$alink.'" class="startdate">'
                    . 'Apply Now'
                    . '</a>';
                
               echo '</div>';                             
        }
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}