<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

$this->loadplugin('encryption/encrypt');
$code = new encryption();

$link =  new navigation();

$event = $this->runquery("SELECT * FROM events WHERE eventid='".$_GET['id']."' ORDER BY startdate DESC",'single');

$sdate = date('d-M-Y',$event['startdate']);
$sdate = explode('-',$sdate);

$edate = date('d-M-Y',$event['enddate']);
$edate = explode('-',$edate);

echo '<h1 class="fullarticleTitle">'.$event['title'].'</h1>';

echo '<div id="itemContainer">';
echo '<table width="100%" border="0" cellpadding="10" class="eventstable col-xs-12">
      <tr>
        <td width="12%" valign="top"><div id="eventsdate">
            <span class="number">'.$sdate[0].'</span>
            <span class="month">'.$sdate[1].'</span>
        </div>
        <div id="eventedate">
            <span class="number">'.$edate[0].'</span>
            <span class="month">'.$edate[1].'</span>
        </div>
        </td>
        <td width="75%"><h3>Venue: '.$event['venue'].'</h3>';

echo '<p>'.$event['description'].'</p>';

if($event['enddate'] > time())
{
    if(!isset($_SESSION['username'])||$_SESSION['username']!='Guest User')
    {
        $url = '?content=mod_login&folder=same&file=usersignup&from=events&id='.$event['eventid'].'&url='.$code->encrypt($link->geturl());
        
        $button = 'Register';
    }
    else
    {
        $url = '?content=com_events&folder=same&file=eventbooking&id='.$event['eventid'];
        $button = 'Book Event';
    }
    
    echo '<a class="readmore" href="'.$url.'">'.$button.'</a>';
    $set = '1';
}

echo '</td>
      </tr>
    </table>';

echo '</div>';
