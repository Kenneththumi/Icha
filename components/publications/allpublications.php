<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 class="fullarticleTitle">Resources & Tools</h1>';

echo '<div id="itemContainer">';

$this->loadmodule('filter-research','research');
/*
$publications = $this->runquery("SELECT * FROM publications WHERE ptype='publication' ORDER BY publishdate DESC",'multiple',5);
$publicationcount = $this->getcount($publications);

    if($publicationcount >= 1){
        
        for($r=1; $r<=$publicationcount; $r++){

            $publication = $this->fetcharray($publications);
            $time = explode('-',date('D-M,d-Y' ,$publication['publishdate']));

            $alink = '?content=com_publications&folder=same&file=publicationdetails&pid='.$publication['publicationid'];
            $decription = $this->shortentxt(strip_tags($publication['body']), 150);

            //echo '<div class="itemList">';        
                echo '<header>';
                    echo '<time pubdate="" datetime="'.date('d M Y H:i:s' ,$publication['publishdate']).'">'
                            . $time[0]
                            .'<small>'.$time[1].'</small>'
                            . '</time>';
                    echo '<h2><a href="'.$alink.'">'.$publication['title'].'</a></h2>';
                echo '</header>';    

                echo '<div class="itemBody">';
                    echo $decription;
                    echo '<p><a href="'.$alink.'" class="read_more">Read More</a></p>';
                echo '</div>';
            //echo '</div>';
        }
    }
    else{
        
        $this->inlinemessage('No publications found','error');
    }
    

echo '<div class="one-half">';
    echo '<h1>Research Papers</h1>';

    $papers = $this->runquery("SELECT * FROM publications WHERE ptype='researchpaper' ORDER BY publishdate DESC",'multiple',5);
    $papercount = $this->getcount($papers);
    
    if($papercount >= 1){

        for($t=1; $t<=$papercount; $t++){

            $paper = $this->fetcharray($papers);
            $time = explode('-',date('D-M,d-Y' ,$paper['publishdate']));

            $alink = '?content=com_publications&folder=same&file=publicationdetails&pid='.$paper['publicationid'];
            $decription = $this->shortentxt(strip_tags($paper['body']), 150);

            echo '<div class="itemList">';        
                echo '<header>';
                    echo '<time pubdate="" datetime="'.date('d M Y H:i:s' ,$paper['publishdate']).'">'
                            . $time[0]
                            .'<small>'.$time[1].'</small>'
                            . '</time>';
                    echo '<h2><a href="'.$alink.'">'.$paper['title'].'</a></h2>';
                echo '</header>';    

                echo '<div class="itemBody">';
                    echo $decription;
                    echo '<p><a href="'.$alink.'" class="read_more">Read More</a></p>';
                echo '</div>';
            echo '</div>';
        }
    }
    else{

        $this->inlinemessage('No research papers found','error');
    }

echo '</div>';
*/

echo '</div>';
    