<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(!isset($params['items']))
{
    $event = $this->runquery("SELECT * FROM events ORDER BY startdate DESC",'single');
    $count = '1';
}
else
{
    $eventchk = $this->runquery("SELECT * FROM events ORDER BY startdate DESC",'multiple',$params['items']);
    $count  = $this->getcount($eventchk);
}

echo '<div id="eventinfo">';
echo '<h1 class="articleTitle">Upcoming Events</h1>';

for($r=1; $r<=$count; $r++)
{
    if(isset($params['items']))
    {
        $event = $this->fetcharray($eventchk);
    }
        
    $date = date('d-M-Y',$event['startdate']);
    $date = explode('-',$date);

    echo '<div class="event">';
    echo '<div id="eventdate">
            <span class="number">'.$date[0].'</span>
            <span class="month">'.$date[1].'</span>
        </div>';

    echo '<div id="info">
            <span class="title"><a href="?content=com_events&folder=same&file=eventdetails&id='.$event['eventid'].'">'.$event['title'].'</a></span>
            <span class="venue">Venue: '.$event['venue'].'</span>
          </div>';
    echo '</div>';
}
echo '</div>';
?>
