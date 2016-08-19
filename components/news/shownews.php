<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 class="fullarticleTitle">ICHA Events</h1>';

if(isset($_GET['filter'])){
    
    if($_GET['filter']=='policy'){
        $policy = 'where focusarea="Policy Briefs"';
    }
    
    echo '<div id="itemContainer">';

    $events = $this->runquery("SELECT * FROM events ".$policy." ORDER BY eventid DESC",'multiple',5);
    $eventcount = $this->getcount($events);

    echo '<h1>Latest Events</h1>';

    if($eventcount >= 1){

        for($t=1; $t<=$eventcount; $t++){

            $event = $this->fetcharray($events);

            $time = explode('-',date('D-M,d-Y' ,$event['startdate']));
            $etime = explode('-',date('D-M,d-Y' ,$event['enddate']));

            $alink = '?content=com_events&folder=same&file=eventdetails&id='.$event['eventid'];
            $decription = $this->shortentxt(strip_tags($event['description']), 150);

            echo '<div class="itemList">';
                echo '<div class="start_end">';
                    echo '<header>';
                    echo '<time pubdate="" datetime="'.date('d M Y H:i:s' ,$event['startdate']).'">'
                            . $time[0]
                            .'<small>'.$time[1].'</small>'
                            . '</time>';

                    echo '<h2><a href="'.$alink.'">'.$event['title'].'</a></h2>';
                    echo '</header>'
                    . '</div>'; 

                echo '<div class="itemBody">';
                    echo $decription;
                    echo '<p><a href="'.$alink.'" class="read_more">Read More</a></p>';
                echo '</div>';
            echo '</div>';
        }
    }
    else{

        $this->inlinemessage('No news articles found','error');
    }

    echo '</div>';
}