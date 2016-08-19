<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

//$this->loadscripts('jquery.anythingslider.min','yes');
$this->loadscripts('jquery.scrollable','yes');

//load slider css
echo '<link href="'.SITE_PATH.'modules/leadarticle/css/scrollable.css" rel="stylesheet" type="text/css" />';

$lead = $this->runquery("SELECT * FROM articles WHERE lead='yes' ORDER BY articleid ASC",'multiple','all');
$leadCount = $this->getcount($lead);

if($leadCount > 1)
{
   $this->wrapscript('$(function() {
                        // initialize scrollable
                        $(".scrollable, .scrolltext").scrollable({circular: true})
                                        .autoscroll({ 
                                            autoplay: true, 
                                            interval: 15000 
                                        });
                    });');
   
}

echo '<div class="scrollable">';
echo '<div class="items">';

$leadarticles = $this->runquery("SELECT * FROM articles WHERE lead='yes' ORDER BY articleid ASC",'multiple','all');
$artCount = $this->getcount($leadarticles);

for($i=1; $i<=$leadCount; $i++){
    
    $leadart = $this->fetcharray($lead);

    $artid = $leadart['articleid'];
    $img = $this->runquery("SELECT filename FROM imgmanager WHERE articleid='".$artid."'",'single');

    $artBody = strip_tags($leadart['body']);

    echo '<div>';
    echo '<img src="'.SITE_PATH.'media/banners/'.$img['filename'].'" class="leadimg img-responsive" />';

    $art = $this->fetcharray($leadarticles);

    $artid = $art['articleid'];	
    $artBody = strip_tags($art['body']);

    $cutoff = 170;
    $titlecut = 70;

    //remove the source
    if(strstr($artBody,'[source]')!=''){
        $source = $this->findText('[source]','[|source]',$art['body']);
        $spl_body = explode('[|source]',$artBody);
        $body = $spl_body[1];
    }
    else{
        $body = $artBody;
    }

    echo '<div class="article">
        <a href="?content=com_articles&artid=8">'
            . '<h1 class="leadtitle">'.$this->shortentxt($art['title'],$titlecut).'</h1>
        </a>
        <a href="?content=com_articles&artid=8">'
            . '<p class="leadbody">'.$this->shortentxt($body,$cutoff).'</p>
        </a>
    </div>';

    echo '</div>';
}

echo '</div>';
echo '</div>';

$this->wrapscript('$(function() {
                        // initialize scrollable
                        $(".banner_courses").scrollable({
                                                circular: true,
                                                prev: ".left",
                                                next: ".right"})
                                        .autoscroll({ 
                                            autoplay: false, 
                                            interval: 15000 
                                        });
                    });');

$start = strtotime('-1 months');
$end = strtotime('+2 months');

$coursechk = $this->runquery("SELECT * FROM courses "
        . "WHERE enabled = 'Yes' AND parentid = '0' "
        . "AND startdate >= ".$start." "
        . "AND startdate <= ".$end." "
        . "OR featured = '1' "
        . "ORDER BY featured DESC,courseid DESC",'multiple',6);

$count  = $this->getcount($coursechk);

$slidecount = (($count / 3) < 1 ? 1 : ($count / 3));

echo '<div class="banner_courses" id="bannertext">';
echo '<h2>Upcoming Courses'
        . '<div class="coursenav">'
            . '<a class="browse left"></a>'
            . '<a class="browse right"></a>'
        . '</div>'
    . '</h2>'
    . '<div class="items">';

    for($r=1; $r<=$slidecount; $r++){
        
        echo '<div class="slide">'; 

        for($t=1; $t<=3; $t++){
            
            $course = $this->fetcharray($coursechk);                

            $alink = '?content=com_courses&folder=same&file=showdetails&cid='.$course['courseid'];
            $description = $this->shortentxt(strip_tags($course['description']), 100);

            echo '<h3><a href="'.$alink.'">'.$this->shortenTxt(ucwords(strtolower($course['coursename'])),25).'</a></h3>';
            echo  '<p>'.$description.'</p>'; 
            
            $lastweek = strtotime("-1 week");
            if($course['startdate'] != '0' && $course['startdate'] >= $lastweek){
                
                $date = date('l, d M Y',$course['startdate']);
                echo '<a class="smalldate" href="'.$alink.'" class="startdate">Start Date: '.$date.'</a>'; 
            }
        }
        
        echo '</div>';
    }

    echo '</div>';

echo '</div>';