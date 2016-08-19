<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

if(isset($_GET['filter']))
{
    $focus = $this->runquery("SELECT * FROM focusareas WHERE alias='".$_GET['filter']."'",'single');
    
    $focus_condition = " WHERE focusarea='".$focus['areaid']."' ";
}

$eventchk = $this->runquery("SELECT * FROM events ".$focus_condition." ORDER BY startdate DESC",'multiple','all');

$eventcount = $this->getcount($eventchk);

echo '<h1 class="fullarticleTitle">Our Calendar of Events</h1>';

echo '<div id="itemContainer">';

if($eventcount>='1')
{
    if(isset($_GET['filter']))
    {
        echo '<p class="linkcourse"><strong>Focus Area: '.$focus['name'].'</strong></p>';
    }
    //$this->loadplugin('calendar/index');

    for($p=1; $p<=$eventcount; $p++)
    {
        $event = $this->fetcharray($eventchk);

        $sdate = date('d-M-Y',$event['startdate']);
        $sdate = explode('-',$sdate);

        $edate = date('d-M-Y',$event['enddate']);
        $edate = explode('-',$edate);

        if($event['enddate'] < time()&&!isset($set))
        {
            echo '<p></p><h1 class="fullarticleTitle">Archived Events</h1>';
            $set='1';
        }

        echo '<table width="100%" border="0" cellpadding="10" class="eventstable">
          <tr>
            <td width="10%" rowspan="2" valign="top"><div id="eventsdate">
                <span class="number">'.$sdate[0].'</span>
                <span class="month">'.$sdate[1].'</span>
            </div></td>
            <td width="10%" rowspan="2" valign="top"><div id="eventedate">
                <span class="number">'.$edate[0].'</span>
                <span class="month">'.$edate[1].'</span>
            </div></td>
            <td width="52%"><h3 class="eventtitle"><a href="?content=com_events&folder=same&file=eventdetails&id='.$event['eventid'].'">'.$event['title'].'</a></h3></td>
            <td width="16%"><a class="readmore" href="?content=com_events&folder=same&file=eventdetails&id='.$event['eventid'].'">Read More</a></td>
          </tr>
          <tr>
            <td colspan="2" >'.$event['description'].'</td>
          </tr>
        </table>';
    }
}
 else {
    $this->inlinemessage('No listed events','valid');
}

echo '</div>';
