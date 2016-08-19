<?php
//insert the instructor profile
$contributors = $this->runquery("SELECT * FROM contributors WHERE category='instructor' ORDER BY listorder ASC",'multiple',$params['items']);
$count = $this->getcount($contributors);

echo '<div class="tbpanes">';
for($t=1; $t<=$count; $t++)
{
    $contributor = $this->fetcharray($contributors);
    
    $img = $this->runquery("SELECT * FROM imgmanager WHERE articleid='".$contributor['contributorid']."'",'single');
    
    echo '<div class="profile_holder">';
        echo '<h2>'.str_replace('-',' ',$contributor['designation']).'</h2>';
        echo '<div class="profilepic">
                <img src="'.MEDIA_PATH.'profilepics/'.$img['filename'].'">
              </div>';

        echo '<div class="profiledetails">';
            echo '<h2>'.str_replace('-',' ',$contributor['name']).'</h2>';
            //echo '<p><strong>Email Address:</strong> '.$contributor['emailaddress'].'</p>';
            //echo '<p><strong>Telephone:</strong> '.$contributor['telephone'].'</p>';
        echo '</div>';

        echo '<div class="profileinfo">';

        if(str_word_count($contributor['contactinfo'])>=50)
        {
            echo $this->shortentxt(strip_tags($contributor['contactinfo']),120);
            echo '<a href="?content=com_profiles&folder=same&file=showprofile&cid='.$contributor['contributorid'].'" class="smallmore">Read More</a>';
        }
        echo '</div>';
    echo '</div>';
}
echo '</div>';
?>
