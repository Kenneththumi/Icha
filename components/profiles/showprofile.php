<?php
//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

echo '<h1 class="fullarticleTitle">Our Team</h1>';
echo '<div id="itemContainer">';

if(!isset($_GET['filter'])){
    
    $cid = $_GET['cid'];

    $contributor = $this->runquery("SELECT * FROM ourteam WHERE teamid='$cid'",'single');
    $img = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$contributor['teamid']."'",'single');

    echo '<table width="100%" border="0" cellpadding="10" style="margin-top: 30px;">
      <tr>
        <td width="98%" rowspan="2" valign="top">
        <h1 class="articleTitle">'.str_replace('-',' ',$contributor['name']).'</h1>';
    
    if($img == TRUE){
        echo '<header style="float: left; margin-right: 10px; margin-bottom: 20px;">'; 
            echo '<img width="275px" src="'.MEDIA_PATH.'profilepics/'.$img['filename'].'" >';
        echo '</header>';
    }  
    
    echo '<small style="padding-bottom: 15px;">'.$contributor['designation'].'</small><p></p>';
    echo $contributor['contactinfo'];
    
    echo '</td>
      </tr>
      <tr>
        <td valign="top">
        ';
    echo '</td>
      </tr>
    </table>';
}
 else {
     
    $contributors = $this->runquery("SELECT * FROM ourteam WHERE category = 'Core Staff' ORDER BY listorder ASC",'multiple','all');
    $contributorcount = $this->getcount($contributors);
    
    echo '<div class="teamlist">';
    
    for($t=1; $t<=$contributorcount; $t++){
        
        $contributor = $this->fetcharray($contributors);
        $img = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$contributor['teamid']."'",'single');
        
        if($contributor['category']!=$headerchk){
            
            echo '<div class="header">
                        <h2><a>Management Team</a></h2>
                    </div>';
        }
        
        echo '<div id="profileList">'; 

        if($img == TRUE){
            echo '<header>'; 
                echo '<img src="'.MEDIA_PATH.'profilepics/'.$img['filename'].'" >';
            echo '</header>';
            
            $class = 'profileBody';
            $textlimit = 150;
        }       
        else{
            $class = 'fullwidthProfileBody';
            $textlimit = 250;
        }

        echo '<div class="'.$class.'">'
            . '<h2><a href="?content=com_profiles&folder=same&file=showprofile&cid='.$contributor['teamid'].'">'
            . str_replace('-', ' ', $contributor['name']).'</a></h2>'
            . '<p><small>'.$contributor['designation'].'</small></p><p></p>'
                
                .strip_tags($this->shortentxt(strip_tags($contributor['contactinfo']),$textlimit))
                
                .'<p><a class="read_more" href="?content=com_profiles&folder=same&file=showprofile&cid='.$contributor['teamid'].'">'
                . 'Read More'
                . '</a></p>'
            .'</div>';

        echo '</div>';
        
        $headerchk = $contributor['category'];
    }
    
    $contributors = $this->runquery("SELECT * FROM ourteam WHERE category != 'Core Staff'  ORDER BY category ASC,listorder ASC",'multiple','all');
    $contributorcount = $this->getcount($contributors);
    
    for($t=1; $t<=$contributorcount; $t++){
        
        $contributor = $this->fetcharray($contributors);
        $img = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$contributor['teamid']."'",'single');
        
        if($contributor['category']!=$headerchk){
            
            echo '<hr class="teamdivider">';
            echo '<div class="header">
                        <h2><a>'.$contributor['category'].'</a></h2>
                    </div>';
        }
        
            echo '<div id="profileList">'; 

            if($img == TRUE){
                echo '<header class="teamimg">'; 
                    echo '<img src="'.MEDIA_PATH.'profilepics/'.$img['filename'].'" >';
                echo '</header>';
                $class = 'profileBody';
                $textlimit = 160;
            }       
            else{
                $class = 'fullwidthProfileBody';
                $textlimit = 250;
            }

            echo '<div class="'.$class.'">'
                . '<h2><a href="?content=com_profiles&folder=same&file=showprofile&cid='.$contributor['teamid'].'">'
                . str_replace('-', ' ', $contributor['name']).'</a></h2>'
                . '<p><small>'.$contributor['designation'].'</small></p><p></p>'

                    .strip_tags($this->shortentxt(strip_tags($contributor['contactinfo']),$textlimit))

                    .'<p><a class="read_more" href="?content=com_profiles&folder=same&file=showprofile&cid='.$contributor['teamid'].'">'
                    . 'Read More'
                    . '</a></p>'
                .'</div>';

            echo '</div>';
        
        $headerchk = $contributor['category'];
    }
    
    echo '</div>';
}

echo '</div>';
